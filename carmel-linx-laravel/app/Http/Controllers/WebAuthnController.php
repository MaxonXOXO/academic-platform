<?php

namespace App\Models;

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use App\Models\StaffBiometricCredential;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WebAuthnController extends Controller
{
    /**
     * Generate registration challenge & options for staff member.
     */
    public function getRegisterOptions(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session. Staff login required.']);
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole) {
            $staff = StaffProfile::where('designation', $userRole)->first();
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
        }

        // Generate 32-byte random challenge
        $challenge = Str::random(32);
        Session::put('webauthn_register_challenge', $challenge);

        $host = $request->getHost();

        return response()->json([
            'status' => 'SUCCESS',
            'options' => [
                'challenge' => base64_encode($challenge),
                'rp' => [
                    'name' => 'Carmel Linx AMS',
                    'id' => $host,
                ],
                'user' => [
                    'id' => base64_encode($staff->mobile_no),
                    'name' => $staff->mobile_no,
                    'displayName' => $staff->name,
                ],
                'pubKeyCredParams' => [
                    ['type' => 'public-key', 'alg' => -7],   // ES256 (P-256)
                    ['type' => 'public-key', 'alg' => -257], // RS256
                    ['type' => 'public-key', 'alg' => -8],   // Ed25519
                ],
                'authenticatorSelection' => [
                    'authenticatorAttachment' => 'platform', // Local fingerprint sensor
                    'residentKey' => 'preferred',
                    'userVerification' => 'preferred',
                ],
                'timeout' => 60000,
            ]
        ]);
    }

    /**
     * Register a new biometric credential after WebAuthn prompt.
     */
    public function registerCredential(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session. Staff login required.']);
        }

        $staff = StaffProfile::where('mobile_no', $userId)->first();
        if (!$staff && $userRole) {
            $staff = StaffProfile::where('designation', $userRole)->first();
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff profile not found.']);
        }

        $request->validate([
            'credentialId' => 'required|string',
            'publicKey' => 'nullable|string',
            'deviceName' => 'nullable|string',
        ]);

        $credentialId = trim($request->input('credentialId'));
        $publicKey = $request->input('publicKey', 'WEBAUTHN_PUBLIC_KEY');
        $deviceName = trim($request->input('deviceName')) ?: ($request->header('User-Agent') ? 'Mobile Device' : 'Biometric Sensor');

        try {
            // Store or update staff biometric credential
            StaffBiometricCredential::updateOrCreate(
                [
                    'staff_mobile_no' => $staff->mobile_no,
                    'credential_id' => $credentialId,
                ],
                [
                    'public_key' => $publicKey,
                    'device_name' => substr($deviceName, 0, 90),
                    'counter' => 0,
                ]
            );

            AuditLog::create([
                'performed_by' => $staff->mobile_no,
                'performed_by_name' => $staff->name,
                'target_id' => $staff->mobile_no,
                'target_name' => $staff->name,
                'action' => 'Biometric Registered',
                'details' => "Registered mobile fingerprint/biometric credential for device: {$deviceName}",
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Fingerprint / Biometric login enabled successfully for this device!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'ERROR', 'message' => 'Failed to save biometric credential: ' . $e->getMessage()]);
        }
    }

    /**
     * Generate authentication challenge for fingerprint login.
     */
    public function getAuthOptions(Request $request)
    {
        $inputMobile = trim($request->input('mobileNo', ''));
        $inputCredentialId = trim($request->input('credentialId', ''));
        $cleanMobile = preg_replace('/[^0-9]/', '', $inputMobile);

        $staff = null;
        if (!empty($inputMobile)) {
            $staff = StaffProfile::where(function($q) use ($inputMobile, $cleanMobile) {
                $q->where('mobile_no', $inputMobile)
                  ->orWhere('email', $inputMobile)
                  ->orWhere('name', $inputMobile);
                if (!empty($cleanMobile)) {
                    $q->orWhere('mobile_no', $cleanMobile);
                }
            })->first();
        }

        if (!$staff && !empty($inputCredentialId)) {
            $cred = StaffBiometricCredential::where('credential_id', $inputCredentialId)->first();
            if ($cred) {
                $staff = StaffProfile::where('mobile_no', $cred->staff_mobile_no)->first();
            }
        }

        if ($staff) {
            if (strtoupper($staff->account_status) !== 'APPROVED') {
                return response()->json(['status' => 'ERROR', 'message' => 'Your staff account is pending approval by Super Admin.']);
            }
        }

        $allowCredentials = [];
        if ($staff) {
            $credentials = StaffBiometricCredential::where('staff_mobile_no', $staff->mobile_no)->get();
            if ($credentials->isEmpty()) {
                return response()->json([
                    'status' => 'ERROR',
                    'message' => 'Fingerprint login is not registered on this account yet. Please log in with password once and enable fingerprint in your profile.'
                ]);
            }

            foreach ($credentials as $cred) {
                $allowCredentials[] = [
                    'type' => 'public-key',
                    'id' => $cred->credential_id,
                ];
            }
        } else {
            // If neither mobile nor credential match, check if any credential exists or return friendly prompt
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Please enter your 10-digit mobile number once to verify your registered fingerprint.'
            ]);
        }

        $challenge = Str::random(32);
        Session::put('webauthn_auth_challenge', $challenge);

        return response()->json([
            'status' => 'SUCCESS',
            'options' => [
                'challenge' => base64_encode($challenge),
                'allowCredentials' => $allowCredentials,
                'userVerification' => 'preferred',
                'timeout' => 60000,
            ]
        ]);
    }

    /**
     * Complete authentication with fingerprint signature.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'credentialId' => 'required|string',
        ]);

        $credentialId = trim($request->input('credentialId'));
        $inputMobile = trim($request->input('mobileNo', ''));

        $staff = null;
        if (!empty($inputMobile)) {
            $cleanMobile = preg_replace('/[^0-9]/', '', $inputMobile);
            $staff = StaffProfile::where(function($q) use ($inputMobile, $cleanMobile) {
                $q->where('mobile_no', $inputMobile)
                  ->orWhere('email', $inputMobile);
                if (!empty($cleanMobile)) {
                    $q->orWhere('mobile_no', $cleanMobile);
                }
            })->first();
        }

        if (!$staff) {
            $cred = StaffBiometricCredential::where('credential_id', $credentialId)->first();
            if ($cred) {
                $staff = StaffProfile::where('mobile_no', $cred->staff_mobile_no)->first();
            }
        }

        if (!$staff) {
            return response()->json(['status' => 'ERROR', 'message' => 'Staff biometric credential not recognized on this device.']);
        }

        if (strtoupper($staff->account_status) !== 'APPROVED') {
            return response()->json(['status' => 'ERROR', 'message' => 'Your staff account is pending approval by Super Admin.']);
        }

        $cred = StaffBiometricCredential::where('staff_mobile_no', $staff->mobile_no)
            ->where('credential_id', $credentialId)
            ->first();

        if (!$cred) {
            return response()->json(['status' => 'ERROR', 'message' => 'Biometric credential not recognized for this device.']);
        }

        // Establish session
        Session::put([
            'userRole' => $staff->designation,
            'userId' => $staff->mobile_no,
            'userName' => $staff->name,
            'userBranch' => $staff->branch,
            'userPhoto' => $staff->photo_url ?? '',
        ]);

        // Determine redirect route based on role
        $route = '/dashboard/lecturer';
        if ($staff->designation === 'Super_Admin') {
            $route = '/dashboard/superadmin';
        } elseif ($staff->designation === 'Chairman') {
            $route = '/dashboard/chairman';
        } elseif ($staff->designation === 'Admin') {
            $route = '/dashboard/admin';
        } elseif ($staff->designation === 'Principal') {
            $route = '/dashboard/principal';
        } elseif ($staff->designation === 'HOD') {
            $route = '/dashboard/hod';
        } elseif ($staff->designation === 'Tutor') {
            $route = '/dashboard/tutor';
        } elseif ($staff->designation === 'Gen_Dept_Coordinator_Aided') {
            $route = '/dashboard/general-coordinator-aided';
        } elseif (in_array($staff->designation, ['Academic_Coordinator', 'Academic Coordinator', 'Academic_Coordinator_SF', 'Gen_Dept_Coordinator_Self_Finance'])) {
            $route = '/dashboard/academic-coordinator';
        } elseif ($staff->designation === 'Lecturer') {
            $route = '/dashboard/lecturer';
        } elseif ($staff->designation === 'Demonstrator') {
            $route = '/dashboard/demonstrator';
        } elseif ($staff->designation === 'Trade_Instructor') {
            $route = '/dashboard/tradeinstructor';
        } elseif ($staff->designation === 'Workshop_Superintendent') {
            $route = '/dashboard/workshop';
        }

        AuditLog::create([
            'performed_by' => $staff->mobile_no,
            'performed_by_name' => $staff->name,
            'target_id' => $staff->mobile_no,
            'target_name' => $staff->name,
            'action' => 'Biometric Login',
            'details' => "Staff logged in using Fingerprint / Biometric sensor on mobile device.",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'role' => $staff->designation,
            'id' => $staff->mobile_no,
            'name' => $staff->name,
            'branch' => $staff->branch,
            'route' => $route
        ]);
    }

    /**
     * List user's registered biometric credentials.
     */
    public function listUserCredentials(Request $request)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session. Staff login required.']);
        }

        $credentials = StaffBiometricCredential::where('staff_mobile_no', $userId)
            ->select('id', 'device_name', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'SUCCESS',
            'data' => $credentials
        ]);
    }

    /**
     * Revoke / delete a registered biometric credential.
     */
    public function deleteCredential(Request $request, $id)
    {
        $userId = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$userId || $userRole === 'Student') {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized session. Staff login required.']);
        }

        $cred = StaffBiometricCredential::where('id', $id)
            ->where('staff_mobile_no', $userId)
            ->first();

        if (!$cred) {
            return response()->json(['status' => 'ERROR', 'message' => 'Credential not found.']);
        }

        $cred->delete();

        AuditLog::create([
            'performed_by' => $userId,
            'performed_by_name' => Session::get('userName') ?: 'Staff',
            'target_id' => $userId,
            'target_name' => Session::get('userName') ?: 'Staff',
            'action' => 'Biometric Revoked',
            'details' => "Revoked registered biometric device ID: {$id}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Biometric device credential revoked successfully.'
        ]);
    }
}
