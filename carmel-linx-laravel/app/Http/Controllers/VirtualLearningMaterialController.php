<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\VirtualLearningMaterial;
use App\Models\BatchSubject;
use App\Models\Student;

class VirtualLearningMaterialController extends Controller
{
    /**
     * Upload / Publish new learning material or pre-class notice.
     */
    public function uploadMaterial(Request $request)
    {
        $userId = Session::get('userId') ?: Session::get('mobile_no');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized. Please log in.'], 401);
        }

        $request->validate([
            'batch_subject_id' => 'required|integer',
            'room_type' => 'required|string|in:Theory,Practical,Practicum,Drawing',
            'experiment_or_topic_no' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'pre_class_instruction' => 'nullable|string',
            'material_type' => 'required|string|in:pdf,video,video_clip,image,document,link',
            'target_date' => 'nullable|date',
            'is_pre_class_notice' => 'nullable|boolean',
        ]);

        $batchSubject = BatchSubject::find($request->input('batch_subject_id'));
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject not found.'], 404);
        }

        $filePath = null;
        $videoUrl = null;
        $materialType = $request->input('material_type');

        if (in_array($materialType, ['pdf', 'image', 'document'])) {
            if (!$request->hasFile('file')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Please select a file to upload.'], 422);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = '/storage/' . $file->storeAs('learning_materials', $fileName, 'public');
        } elseif ($materialType === 'video_clip') {
            // Direct video file upload (MP4 / WebM / OGG — max 25 MB)
            if (!$request->hasFile('file')) {
                return response()->json(['status' => 'ERROR', 'message' => 'Please select a video file to upload.'], 422);
            }
            $request->validate([
                'file' => 'required|file|mimes:mp4,webm,ogg|max:25600',
            ]);
            $file = $request->file('file');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $filePath = '/storage/' . $file->storeAs('learning_materials/videos', $fileName, 'public');
        } elseif (in_array($materialType, ['video', 'link'])) {
            $rawUrl = trim($request->input('video_url', ''));
            if (empty($rawUrl)) {
                return response()->json(['status' => 'ERROR', 'message' => 'Please enter a valid video or resource URL.'], 422);
            }
            $videoUrl = $this->formatEmbedUrl($rawUrl);
        }

        try {
            $material = VirtualLearningMaterial::create([
                'batch_subject_id' => $batchSubject->id,
                'subject_code' => $batchSubject->subject_code,
                'classroom_id' => $batchSubject->classroom_id,
                'room_type' => $request->input('room_type'),
                'experiment_or_topic_no' => trim($request->input('experiment_or_topic_no')),
                'title' => trim($request->input('title')),
                'pre_class_instruction' => trim($request->input('pre_class_instruction', '')),
                'material_type' => $materialType,
                'file_path' => $filePath,
                'video_url' => $videoUrl,
                'is_pre_class_notice' => $request->boolean('is_pre_class_notice', true),
                'target_date' => $request->input('target_date') ?: now()->addDay()->toDateString(),
                'uploaded_by' => $userId,
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Study material & pre-class notice published successfully.',
                'material' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to save material: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all materials for a specific subject (For Staff & Student Views).
     */
    public function getSubjectMaterials($subjectId)
    {
        $materials = VirtualLearningMaterial::where('batch_subject_id', $subjectId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'SUCCESS',
            'materials' => $materials
        ]);
    }

    /**
     * Get active pre-class alerts for the logged-in student.
     */
    public function getStudentPreClassAlerts()
    {
        $regNo = Session::get('userId') ?: Session::get('reg_no');
        $userRole = Session::get('userRole');

        if (!$regNo || $userRole !== 'Student') {
            return response()->json([
                'status' => 'SUCCESS',
                'success' => true,
                'alerts' => [],
                'notices' => [],
                'materials' => []
            ]);
        }

        $student = Student::where('reg_no', $regNo)->first();
        if (!$student) {
            return response()->json([
                'status' => 'SUCCESS',
                'success' => true,
                'alerts' => [],
                'notices' => [],
                'materials' => []
            ]);
        }

        // Fetch materials targeted for this student's classroom
        $materials = DB::table('virtual_learning_materials as vlm')
            ->leftJoin('student_material_read_receipts as smrr', function ($join) use ($regNo) {
                $join->on('vlm.id', '=', 'smrr.material_id')
                     ->where('smrr.reg_no', '=', $regNo);
            })
            ->leftJoin('batch_subjects as bs', 'vlm.batch_subject_id', '=', 'bs.id')
            ->where('vlm.classroom_id', $student->classroom_id)
            ->select(
                'vlm.*',
                'bs.subject_name',
                'bs.subject_code',
                DB::raw('IF(smrr.read_at IS NOT NULL, 1, 0) as is_read'),
                DB::raw('vlm.experiment_or_topic_no as topic_no'),
                DB::raw('vlm.pre_class_instruction as description'),
                DB::raw('vlm.target_date as target_class_date'),
                DB::raw('vlm.material_type as resource_type'),
                DB::raw('vlm.video_url as external_url')
            )
            ->orderBy('vlm.created_at', 'desc')
            ->get();

        $unreadNotices = $materials->filter(function($m) {
            return $m->is_pre_class_notice && !$m->is_read;
        })->values();

        return response()->json([
            'status' => 'SUCCESS',
            'success' => true,
            'alerts' => $materials,
            'notices' => $unreadNotices->count() > 0 ? $unreadNotices : $materials,
            'materials' => $materials
        ]);
    }

    /**
     * Mark an alert as read by a student.
     */
    public function markAlertAsRead(Request $request, $id = null)
    {
        $regNo = Session::get('userId') ?: Session::get('reg_no');
        $materialId = $request->input('material_id', $id);

        if (!$regNo || !$materialId) {
            return response()->json(['status' => 'ERROR', 'success' => false, 'message' => 'Invalid payload.']);
        }

        DB::table('student_material_read_receipts')->updateOrInsert(
            ['material_id' => $materialId, 'reg_no' => $regNo],
            ['read_at' => now()]
        );

        return response()->json(['status' => 'SUCCESS', 'success' => true, 'message' => 'Acknowledged successfully.']);
    }

    /**
     * Delete a learning material (Staff only).
     */
    public function deleteMaterial($id)
    {
        $userId = Session::get('userId') ?: Session::get('mobile_no');
        if (!$userId) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized.'], 401);
        }

        $material = VirtualLearningMaterial::find($id);
        if (!$material) {
            return response()->json(['status' => 'ERROR', 'message' => 'Material not found.'], 404);
        }

        if ($material->file_path) {
            $relativePath = str_replace('/storage/', '', $material->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $material->delete();

        return response()->json(['status' => 'SUCCESS', 'message' => 'Material deleted successfully.']);
    }

    /**
     * Convert YouTube/Vimeo links to iframe-embeddable format.
     */
    private function formatEmbedUrl($url)
    {
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|shorts\/))([\w-]{11})/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }
        return $url;
    }
}
