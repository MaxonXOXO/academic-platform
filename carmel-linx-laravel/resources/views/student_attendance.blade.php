<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Attendance Review — {{ $student->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --app-bg: #090d16;
            --card-bg: rgba(17, 24, 39, 0.95);
            --card-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-emerald: #10b981;
            --accent-amber: #f59e0b;
            --accent-rose: #f43f5e;
            --accent-purple: #8b5cf6;
        }

        body {
            background-color: var(--app-bg);
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.88rem;
            min-height: 100vh;
            -webkit-tap-highlight-color: transparent;
        }

        .mobile-container {
            max-width: 520px;
            margin: 0 auto;
            min-height: 100vh;
            background-color: var(--app-bg);
            position: relative;
        }

        /* Header Bar */
        .mobile-header {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 12px 16px;
        }

        /* App Cards */
        .app-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.5);
        }

        /* Hero Attendance Dial */
        .attendance-dial {
            width: 105px;
            height: 105px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--accent-emerald);
            background: rgba(16, 185, 129, 0.08);
            margin: 0 auto;
        }
        .attendance-dial.warning {
            border-color: var(--accent-amber);
            background: rgba(245, 158, 11, 0.08);
        }
        .attendance-dial.danger {
            border-color: var(--accent-rose);
            background: rgba(244, 63, 94, 0.08);
        }

        /* Timeline Items */
        .timeline-item {
            background: rgba(30, 41, 59, 0.6);
            border-left: 4px solid var(--accent-cyan);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 8px;
        }
        .timeline-item.present { border-left-color: var(--accent-emerald); }
        .timeline-item.absent { border-left-color: var(--accent-rose); }
        .timeline-item.not-marked { border-left-color: #64748b; }
        .timeline-item.special-hour { border-left-color: var(--accent-purple); }

        .badge-app {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 700;
        }

        .avatar-mobile {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--accent-cyan);
            object-fit: cover;
        }

        .time-pill {
            font-size: 0.68rem;
            color: #94a3b8;
            font-weight: 600;
            background: rgba(15, 23, 42, 0.6);
            padding: 2px 6px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.05);
        }
    </style>
</head>
<body>

    <div class="mobile-container">

        <!-- Top App Bar -->
        <div class="mobile-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <a href="/dashboard/student" class="text-secondary me-1 text-decoration-none">
                    <i class="fa-solid fa-arrow-left fs-5 text-cyan"></i>
                </a>
                <div>
                    <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">Attendance Review</h6>
                    <small class="text-secondary" style="font-size: 0.72rem;">Live Academic Daily Tracker</small>
                </div>
            </div>
            <a href="/dashboard/student" class="btn btn-sm btn-outline-info px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                <i class="fa-solid fa-house me-1"></i> Dashboard
            </a>
        </div>

        <!-- Scrollable Content View -->
        <div class="p-3">

            <!-- Student Profile Card -->
            <div class="app-card">
                <div class="d-flex align-items-center gap-3">
                    @if($student->photo_url)
                        <img src="{{ $student->photo_url }}" alt="{{ $student->name }}" class="avatar-mobile">
                    @else
                        <div class="avatar-mobile bg-dark text-cyan d-flex align-items-center justify-content-center fw-bold fs-5">
                            {{ strtoupper(substr($student->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-grow-1">
                        <h6 class="fw-extrabold text-white mb-0" style="font-size: 1rem;">{{ $student->name }}</h6>
                        <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                            <span class="badge bg-cyan bg-opacity-20 text-cyan badge-app">Reg: {{ $student->reg_no }}</span>
                            <span class="badge bg-purple bg-opacity-20 text-purple badge-app">Sem {{ $student->semester }} ({{ $student->branch }})</span>
                        </div>
                    </div>
                </div>

                @if($tutor && $tutor->name)
                <div class="mt-3 pt-2 border-top border-secondary border-opacity-25 d-flex align-items-center justify-content-between">
                    <span class="text-secondary small" style="font-size: 0.78rem;">Class Tutor: <strong>{{ $tutor->name }}</strong></span>
                    @if($tutor->mobile_no)
                    <a href="tel:{{ $tutor->mobile_no }}" class="btn btn-sm btn-success px-2 py-0.5 rounded-pill" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-phone me-1"></i> Call Tutor
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Hero Attendance Gauge (Strictly 6 Working Hours) -->
            <div class="app-card text-center">
                <span class="text-secondary uppercase text-[11px] fw-bold d-block mb-2">Overall Attendance (6 Working Hours)</span>
                <div class="attendance-dial {{ $overallAttendancePct >= 75 ? '' : ($overallAttendancePct >= 65 ? 'warning' : 'danger') }}">
                    <span class="fw-extrabold fs-4 {{ $overallAttendancePct >= 75 ? 'text-emerald-400' : ($overallAttendancePct >= 65 ? 'text-amber-400' : 'text-rose-400') }}">
                        {{ number_format($overallAttendancePct, 1) }}%
                    </span>
                </div>
                <div class="mt-2">
                    <span class="badge {{ $totalConductedClasses == 0 ? 'bg-secondary' : ($overallAttendancePct >= 75 ? 'bg-success' : ($overallAttendancePct >= 65 ? 'bg-warning text-dark' : 'bg-danger')) }} badge-app">
                        {{ $totalConductedClasses == 0 ? 'No Attendance Marked Yet' : ($overallAttendancePct >= 75 ? 'Good Standing (Eligible for Exams)' : ($overallAttendancePct >= 65 ? 'Warning: Low Attendance' : 'Critical: Condonation Alert')) }}
                    </span>
                </div>
                <small class="text-secondary d-block mt-2" style="font-size: 0.75rem;">
                    Attended: <strong>{{ $totalAttendedClasses }}</strong> / Total Conducted: <strong>{{ $totalConductedClasses }}</strong> Hours
                </small>
            </div>

            <!-- Today's Hour-Wise Attendance Grid -->
            <div class="app-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-clock me-1 text-cyan"></i> Today's Timetable & Attendance
                    </h6>
                    <small class="text-secondary" style="font-size: 0.72rem;">{{ \Carbon\Carbon::now()->format('d M Y') }}</small>
                </div>

                @foreach($hourlyStatus as $pNum => $pData)
                <div class="timeline-item {{ $pNum === 7 ? 'special-hour' : strtolower(str_replace(' ', '-', $pData['status'])) }}">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-1.5 mb-1">
                                <span class="badge {{ $pNum === 7 ? 'bg-purple text-white' : 'bg-secondary' }}" style="font-size: 0.68rem;">P{{ $pNum }}</span>
                                <span class="time-pill">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $pData['time_slot'] }}
                                </span>
                            </div>
                            <strong class="text-white d-block" style="font-size: 0.85rem;">{{ $pData['subject_name'] }}</strong>
                            <small class="text-secondary d-block mt-0.5" style="font-size: 0.72rem;">
                                {{ $pData['topic'] }}
                            </small>
                        </div>
                        <span class="badge {{ $pData['badge_class'] }} badge-app">
                            {{ $pData['status'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Leave Request Provision (Addon Feature) -->
            <div class="app-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="fw-bold text-warning mb-0" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-file-signature me-1"></i> Leave Request & History
                    </h6>
                    <button type="button" onclick="toggleLeaveForm()" class="btn btn-sm btn-outline-warning px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-plus me-1"></i> Apply Leave
                    </button>
                </div>

                <!-- Collapsible Leave Application Form -->
                <div id="leaveFormCard" class="d-none bg-dark bg-opacity-60 p-3 rounded-3 border border-secondary border-opacity-25 mb-3">
                    <h6 class="fw-bold text-white mb-2" style="font-size: 0.82rem;">New Leave Application</h6>
                    <form id="mobileLeaveForm" onsubmit="submitStudentLeave(event)">
                        <input type="hidden" name="semester" value="{{ $student->semester }}">
                        <div class="mb-2">
                            <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Leave Date</label>
                            <input type="date" name="leave_date" required class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Number of Days</label>
                            <select name="no_of_days" required class="form-select form-select-sm bg-slate-900 text-white border-secondary border-opacity-25">
                                <option value="1">1 Day (Full Day)</option>
                                <option value="0.5">0.5 Day (Half Day)</option>
                                <option value="2">2 Days</option>
                                <option value="3">3 Days</option>
                                <option value="4">4 Days</option>
                                <option value="5">5 Days</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-secondary mb-1" style="font-size: 0.72rem;">Reason for Absence</label>
                            <textarea name="reason" required rows="2" placeholder="State valid reason (e.g. Medical, Family Emergency)..." class="form-control form-control-sm bg-slate-900 text-white border-secondary border-opacity-25" style="font-size: 0.78rem;"></textarea>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="parent_informed" id="chkParentInformed" value="1">
                            <label class="form-check-label text-secondary" for="chkParentInformed" style="font-size: 0.75rem;">
                                Parent / Guardian informed tutor
                            </label>
                        </div>
                        <div id="leaveFormStatus" class="d-none small mb-2 font-bold"></div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" onclick="toggleLeaveForm()" class="btn btn-sm btn-secondary px-3" style="font-size: 0.75rem;">Cancel</button>
                            <button type="submit" id="btnSubmitLeave" class="btn btn-sm btn-warning px-3 fw-bold" style="font-size: 0.75rem;">Submit to Tutor</button>
                        </div>
                    </form>
                </div>

                <!-- Recent Leave Applications List -->
                <div class="space-y-2">
                    @forelse($leaveRecords as $record)
                    <div class="p-2.5 rounded-3 bg-dark bg-opacity-40 border border-secondary border-opacity-25 d-flex align-items-center justify-content-between mb-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-white" style="font-size: 0.82rem;">
                                    <i class="fa-regular fa-calendar-minus me-1 text-warning"></i>{{ \Carbon\Carbon::parse($record->leave_date)->format('d M Y') }}
                                </span>
                                <span class="badge bg-secondary" style="font-size: 0.65rem;">{{ $record->no_of_days }} {{ $record->no_of_days == 1 ? 'Day' : 'Days' }}</span>
                            </div>
                            <small class="text-secondary d-block" style="font-size: 0.72rem;">Reason: {{ $record->reason }}</small>
                            <small class="text-slate-400 d-block mt-0.5" style="font-size: 0.68rem;">
                                {{ $record->parent_informed ? '✓ Parent Informed' : 'Parent Not Informed' }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if(strtolower($record->status) === 'approved')
                                <span class="badge bg-success text-white badge-app">Approved</span>
                            @elseif(strtolower($record->status) === 'rejected')
                                <span class="badge bg-danger text-white badge-app">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark badge-app">Pending Review</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-secondary py-3" style="font-size: 0.78rem;">
                        No leave applications submitted yet.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Subject-Wise Attendance Breakdown Table -->
            <div class="app-card">
                <h6 class="fw-bold text-info mb-3" style="font-size: 0.9rem;">
                    <i class="fa-solid fa-layer-group me-1"></i> Subject-Wise Attendance Breakdown
                </h6>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="font-size: 0.78rem;">
                        <thead>
                            <tr class="text-secondary border-bottom border-secondary border-opacity-25">
                                <th>Subject</th>
                                <th class="text-center">Attended</th>
                                <th class="text-center">Conducted</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($subjectStats as $stat)
                            <tr class="border-bottom border-secondary border-opacity-10">
                                <td>
                                    <strong class="text-white d-block">{{ $stat['subject_code'] }}</strong>
                                    <small class="text-secondary">{{ $stat['subject_name'] }}</small>
                                </td>
                                <td class="text-center fw-bold text-emerald-400">{{ $stat['attended'] }} hrs</td>
                                <td class="text-center text-slate-300">{{ $stat['conducted'] }} hrs</td>
                                <td class="text-end">
                                    <span class="badge {{ $stat['percentage'] >= 75 ? 'bg-success' : ($stat['percentage'] >= 65 ? 'bg-warning text-dark' : 'bg-danger') }} badge-app">
                                        {{ number_format($stat['percentage'], 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-secondary py-3">No subject logs recorded yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <script>
        function toggleLeaveForm() {
            const card = document.getElementById('leaveFormCard');
            card.classList.toggle('d-none');
        }

        function submitStudentLeave(event) {
            event.preventDefault();
            const form = event.target;
            const btn = document.getElementById('btnSubmitLeave');
            const statusDiv = document.getElementById('leaveFormStatus');

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Submitting...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const payload = {
                semester: form.semester.value,
                leave_date: form.leave_date.value,
                no_of_days: form.no_of_days.value,
                reason: form.reason.value,
                parent_informed: form.parent_informed.checked ? 1 : 0,
                status: 'Pending'
            };

            fetch('/api/mentoring/leave/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = 'Submit to Tutor';

                if (data.status === 'SUCCESS') {
                    statusDiv.className = 'small mb-2 font-bold text-success';
                    statusDiv.innerText = 'Leave application submitted successfully! Refreshing...';
                    statusDiv.classList.remove('d-none');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    statusDiv.className = 'small mb-2 font-bold text-danger';
                    statusDiv.innerText = data.message || 'Failed to submit leave application.';
                    statusDiv.classList.remove('d-none');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = 'Submit to Tutor';
                statusDiv.className = 'small mb-2 font-bold text-danger';
                statusDiv.innerText = 'Network error. Please try again.';
                statusDiv.classList.remove('d-none');
            });
        }
    </script>

</body>
</html>
