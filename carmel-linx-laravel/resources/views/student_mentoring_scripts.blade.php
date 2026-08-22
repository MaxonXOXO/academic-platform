<script>
function switchStudentMentoringTab(tabId) {
  document.querySelectorAll('.smd-content-pane').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.smd-tab').forEach(el => {
    el.classList.remove('bg-slate-800/80', 'text-blue-400', 'border', 'border-slate-700/60', 'shadow-md');
    el.classList.add('text-slate-400');
  });

  const pane = document.getElementById(tabId);
  if (pane) pane.classList.remove('hidden');
  const btn = document.getElementById('tabBtn_' + tabId);
  if (btn) {
    btn.classList.remove('text-slate-400');
    btn.classList.add('bg-slate-800/80', 'text-blue-400', 'border', 'border-slate-700/60', 'shadow-md');
  }
}


  let diaryChartInstance = null;

  function renderDiaryAcademicProgress(data) {
    const container = document.getElementById('smdAcademicReport');
    if (!container) return;

    if (!data || !data.academics || Object.keys(data.academics).length === 0) {
      container.innerHTML = '<p class="text-slate-500 text-xs text-center py-8">No academic stats available yet.</p>';
      return;
    }

    // Calculate total activity points
    let activityPoints = 0;
    if (data.extracurricular) {
      data.extracurricular.forEach(ex => {
        if (ex.status === 'Verified') {
          activityPoints += (parseFloat(ex.points_awarded) || 0);
        }
      });
    }

    // Sort semesters and prepare chart/table data
    const sems = Object.keys(data.academics).sort((a,b) => parseInt(a) - parseInt(b));
    let totalCgpa = 0;
    let chartLabels = [];
    let chartData = [];
    let htmlRows = '';

    sems.forEach(sem => {
      // Find board summary for this semester if it exists
      let bSummary = null;
      if (data.board) bSummary = data.board.find(b => b.semester == sem);
      
      let sgpa = bSummary ? parseFloat(bSummary.sgpa) || 0 : 0;
      let cgpa = bSummary ? parseFloat(bSummary.cgpa) || 0 : 0;
      let pts = bSummary ? parseFloat(bSummary.activity_points) || 0 : 0;
      
      if (cgpa > 0) totalCgpa = cgpa; // latest cgpa overrides
      
      chartLabels.push(`S${sem}`);
      chartData.push(sgpa);

      let rows = '';
      const subjects = data.academics[sem] || [];
      if (subjects.length > 0) {
        subjects.forEach(sub => {
           let attPercent = 0;
           // Estimate attendance based on the raw subjects data if present, or just show '-'
           let att = '-';
           let attColor = 'text-emerald-400 font-bold';
           if (sub.attendance_percentage !== undefined && sub.attendance_percentage !== null) {
              att = sub.attendance_percentage + '%';
              attColor = (sub.attendance_percentage < 75) ? 'text-rose-400 font-black' : 'text-emerald-400 font-bold';
           }
           
           rows += `<tr class="border-b border-slate-800/50 hover:bg-slate-900/30 transition-premium">
              <td class="p-2 whitespace-nowrap">
                <div class="font-black text-slate-200 text-xs">${sub.subject_code}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">${sub.subject_name}</div>
              </td>
              <td class="p-2 text-center border-l border-slate-800/50"><span class="text-xs ${attColor}">${att}</span></td>
            </tr>`;
        });
      } else {
        rows = `<tr><td colspan="2" class="p-4 text-center text-xs text-slate-500 font-bold">No subjects available</td></tr>`;
      }

      htmlRows += `
        <div class="mb-5">
          <div class="flex justify-between items-end mb-2 px-1">
            <h5 class="text-xs font-black text-slate-400 uppercase tracking-widest">Semester ${sem}</h5>
            <div class="flex gap-2">
              <div class="bg-slate-900 border border-slate-800 px-2 py-1 rounded flex items-center gap-1.5 shadow-inner">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">SGPA:</span>
                <span class="text-[11px] font-black text-white">${sgpa > 0 ? sgpa : '-'}</span>
              </div>
              <div class="bg-amber-950/20 border border-amber-900/40 px-2 py-1 rounded flex items-center gap-1.5 shadow-inner">
                <span class="text-[10px] text-amber-500 font-bold uppercase tracking-widest">Pts:</span>
                <span class="text-[11px] font-black text-amber-400">${pts > 0 ? pts : '-'}</span>
              </div>
            </div>
          </div>
          <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl overflow-x-auto shadow-sm">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-900/80 border-b border-slate-800 text-[10px] uppercase tracking-wider font-black text-slate-500">
                  <th class="p-2 w-3/4">Subject</th>
                  <th class="p-2 w-1/4 text-center border-l border-slate-800/50">Attend.</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-800/30">${rows}</tbody>
            </table>
          </div>
        </div>`;
    });

    const displayCgpa = totalCgpa > 0 ? totalCgpa : '-';
    const displayPts = activityPoints > 0 ? activityPoints : '-';

    let html = `
      <div class="grid grid-cols-2 gap-4 mb-5">
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-4 flex flex-col items-center justify-center shadow-inner">
          <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total CGPA</span>
          <span class="text-2xl font-black text-white">${displayCgpa}</span>
        </div>
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-xl p-4 flex flex-col items-center justify-center shadow-inner">
          <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Activity Points</span>
          <span class="text-2xl font-black text-amber-400">${displayPts}</span>
        </div>
      </div>
      <div class="bg-slate-950/40 border border-slate-800/60 rounded-xl p-3 mb-6 h-40 flex justify-center items-center">
        <canvas id="diaryCgpaChart"></canvas>
      </div>
    `;

    container.innerHTML = html + htmlRows;

    // Render Chart for Mentoring Diary
    setTimeout(() => {
      const cvs = document.getElementById('diaryCgpaChart');
      if (cvs) {
        if (diaryChartInstance) diaryChartInstance.destroy();
        const ctx = cvs.getContext('2d');

        diaryChartInstance = new Chart(ctx, {
          type: 'line',
          data: {
            labels: chartLabels,
            datasets: [{
              label: 'SGPA',
              data: chartData,
              borderColor: '#f59e0b',
              backgroundColor: 'rgba(245, 158, 11, 0.1)',
              borderWidth: 2,
              pointBackgroundColor: '#fff',
              fill: true,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, max: 10, ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(30, 41, 59, 0.5)' } },
              x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { display: false } }
            }
          }
        });
      }
    }, 150);
  }

function loadStudentMentoringDiary() {
  const regQuery = window.TARGET_REG_NO ? '?reg_no=' + window.TARGET_REG_NO : '';
  fetch('/api/student/mentoring/diary' + regQuery, {
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
  .then(res => res.json())
  .then(data => {
    console.log('[Diary API] status:', data.status, 'has data:', !!data.data, 'academics keys:', data.data ? Object.keys(data.data.academics || {}) : 'none');
    if (data.status === 'SUCCESS') {
      populateMentoringUI(data.data || {});
    } else {
      console.error('[Diary API] Error:', data.message);
      const c = document.getElementById('smdAcademicReport');
      if (c) c.innerHTML = '<p class="text-rose-400 text-xs text-center py-8">Could not load diary data. Please refresh.</p>';
    }
  })
  .catch(err => console.error('[Diary API] Fetch error:', err));
}

function populateMentoringUI(data) {
  if (!data) { console.error('[Diary] populateMentoringUI called with null data'); return; }
  console.log('[Diary] populateMentoringUI academics keys:', Object.keys(data.academics || {}));

  // Populate mentor sidebar & header with student name
  const studentName = (data.student && data.student.name) ? data.student.name : (window.TARGET_REG_NO || '');
  const sidebarEl = document.getElementById('sidebarStudentName');
  if (sidebarEl) sidebarEl.innerText = studentName;
  const headerNameEl = document.getElementById('headerStudentName');
  if (headerNameEl) headerNameEl.innerText = (data.student && data.student.name) ? data.student.name + ' (' + window.TARGET_REG_NO + ')' : window.TARGET_REG_NO;
  const diaryHeaderEl = document.getElementById('diaryStudentNameHeader');
  if (diaryHeaderEl) diaryHeaderEl.innerText = studentName;

  // Student Quick Info Header Card mapping
  if (data.student) {
    const headerNameCard = document.getElementById('diaryHeaderStudentName');
    if (headerNameCard) headerNameCard.innerText = data.student.name || studentName;

    const sbteCard = document.getElementById('diaryHeaderStudentSbteNo');
    const sbteLabel = document.getElementById('diaryHeaderStudentSbteLabel');
    if (sbteCard) {
      if (data.student && data.student.sbte_reg_no) {
        sbteCard.innerText = data.student.sbte_reg_no;
        if (sbteLabel) sbteLabel.innerText = "PRN No:";
      } else if (data.student && data.student.reg_no) {
        sbteCard.innerText = data.student.reg_no;
        if (sbteLabel) sbteLabel.innerText = "Reg No:";
      } else {
        sbteCard.innerText = "-";
      }
    }

    const semCard = document.getElementById('diaryHeaderStudentSem');
    if (semCard) {
      let sVal = null;
      if (data.student) {
        sVal = data.student.semester || data.student.current_semester || (data.student.classroom ? data.student.classroom.current_semester : null);
      }
      semCard.innerText = sVal ? (String(sVal).startsWith('S') ? sVal : 'S' + sVal) : '-';
    }

    const branchCard = document.getElementById('diaryHeaderStudentBranch');
    if (branchCard) branchCard.innerText = data.student.branch || '-';

    const batchCard = document.getElementById('diaryHeaderStudentBatch');
    if (batchCard) batchCard.innerText = data.student.classroom_id || '-';

    const topBranchEl = document.getElementById('topHeaderBranch');
    if (topBranchEl) topBranchEl.innerText = data.student.branch || '-';

    const topBatchEl = document.getElementById('topHeaderBatch');
    if (topBatchEl) topBatchEl.innerText = data.student.classroom_id || '-';

    // Sidebar Photo
    const photoImg = document.getElementById('sidebarStudentPhoto');
    const photoPlaceholder = document.getElementById('sidebarStudentPhotoPlaceholder');
    if (photoImg && photoPlaceholder) {
      if (data.student.photo_url) {
        photoImg.src = data.student.photo_url;
        photoImg.classList.remove('hidden');
        photoPlaceholder.classList.add('hidden');
      } else {
        photoImg.classList.add('hidden');
        photoPlaceholder.classList.remove('hidden');
      }
    }

    // Diary Header Photo
    const dPhotoImg = document.getElementById('diaryStudentPhoto');
    const dPhotoPlaceholder = document.getElementById('diaryStudentPhotoPlaceholder');
    if (dPhotoImg && dPhotoPlaceholder) {
      if (data.student.photo_url) {
        dPhotoImg.src = data.student.photo_url;
        dPhotoImg.classList.remove('hidden');
        dPhotoPlaceholder.classList.add('hidden');
      } else {
        dPhotoImg.classList.add('hidden');
        dPhotoPlaceholder.classList.remove('hidden');
      }
    }
  }

  // Populate Personal Info
  if (data.profile) {
    document.getElementById('smd_annual_income').value = data.profile.annual_income || '';
    document.getElementById('smd_residential_status').value = data.profile.residential_status || 'Day Scholar';
    document.getElementById('smd_scholarships').value = data.profile.scholarships || '';
    document.getElementById('smd_fee_waiver').checked = data.profile.is_fee_waiver == 1;
    document.getElementById('smd_guardian_name').value = data.profile.guardian_name || '';
    document.getElementById('smd_guardian_relationship').value = data.profile.guardian_relationship || '';
    document.getElementById('smd_guardian_mobile').value = data.profile.guardian_mobile || '';
    document.getElementById('smd_guardian_address').value = data.profile.guardian_address || '';
  }

  // Populate Extended Profile Info
  if (data.extended_profile) {
    const ep = data.extended_profile;
    if (document.getElementById('mdGender')) document.getElementById('mdGender').value = ep.gender || '';
    if (document.getElementById('mdReligion')) document.getElementById('mdReligion').value = ep.religion || '';
    if (document.getElementById('mdCaste')) document.getElementById('mdCaste').value = ep.caste || '';
    if (document.getElementById('mdReservation')) document.getElementById('mdReservation').value = ep.reservation || '';
    if (document.getElementById('mdQuota')) document.getElementById('mdQuota').value = ep.quota || '';
    if (document.getElementById('mdIsDisabled')) document.getElementById('mdIsDisabled').value = ep.is_physically_disabled !== undefined ? ep.is_physically_disabled : '0';
    if (document.getElementById('mdDisabilityCat')) document.getElementById('mdDisabilityCat').value = ep.disability_category || '';
    if (document.getElementById('mdGuardianOcc')) document.getElementById('mdGuardianOcc').value = ep.guardian_occupation || '';
    if (document.getElementById('mdFamilyIncome')) document.getElementById('mdFamilyIncome').value = ep.monthly_family_income || '';
    if (document.getElementById('mdVehiclePass')) document.getElementById('mdVehiclePass').value = ep.has_vehicle_pass !== undefined ? ep.has_vehicle_pass : '0';
    if (document.getElementById('mdVehiclePassId')) document.getElementById('mdVehiclePassId').value = ep.vehicle_pass_id || '';
    if (document.getElementById('mdCommAddress')) document.getElementById('mdCommAddress').value = ep.communication_address || '';
  } else {
    // Reset to empty/default values if no extended profile exists yet
    if (document.getElementById('mdGender')) document.getElementById('mdGender').value = '';
    if (document.getElementById('mdReligion')) document.getElementById('mdReligion').value = '';
    if (document.getElementById('mdCaste')) document.getElementById('mdCaste').value = '';
    if (document.getElementById('mdReservation')) document.getElementById('mdReservation').value = '';
    if (document.getElementById('mdQuota')) document.getElementById('mdQuota').value = '';
    if (document.getElementById('mdIsDisabled')) document.getElementById('mdIsDisabled').value = '0';
    if (document.getElementById('mdDisabilityCat')) document.getElementById('mdDisabilityCat').value = '';
    if (document.getElementById('mdGuardianOcc')) document.getElementById('mdGuardianOcc').value = '';
    if (document.getElementById('mdFamilyIncome')) document.getElementById('mdFamilyIncome').value = '';
    if (document.getElementById('mdVehiclePass')) document.getElementById('mdVehiclePass').value = '0';
    if (document.getElementById('mdVehiclePassId')) document.getElementById('mdVehiclePassId').value = '';
    if (document.getElementById('mdCommAddress')) document.getElementById('mdCommAddress').value = '';
  }

  // Store syllabus list globally for dropdowns
  window.smdSyllabusList = data.syllabus_list || {};
  window.smdAcademicsList = data.academics || {};

  // Populate Internal Academic Progress tab in mentoring diary
  renderDiaryAcademicProgress(data);

    // Reset dropdown to default and render empty list or Sem 1
    const semSelect = document.getElementById('smdBoardSemSelect');
    if (semSelect) {
      semSelect.value = "1";
      renderStudentBoardExams();
    }

    // Populate Board Exams (Summary Table)
    const bList = document.getElementById('smdBoardList');
    if (bList) {
      bList.innerHTML = '';
      if (data.board) {
        data.board.forEach(b => {
          addBoardRow(b.semester, b.sgpa, b.cgpa, b.activity_points, b.id);
        });
      }
    }

  // Populate Family
  const fList = document.getElementById('smdFamilyList');
  if (fList) {
    fList.innerHTML = '';
    if (data.family) {
      data.family.forEach(f => {
        addFamilyRow(f.name, f.relationship, f.education, f.occupation, f.contact_no, f.id);
      });
    }
  }

  // Populate Education
  const eList = document.getElementById('smdEducationList');
  if (eList) {
    eList.innerHTML = '';
    if (data.education) {
      data.education.forEach(e => {
        addEducationRow(e.course, e.institution, e.year_of_completion, e.total_percentage, e.id);
      });
    }
  }

  // Populate Extracurricular
  const exList = document.getElementById('smdExtraList');
  if (exList) {
    exList.innerHTML = '';
    let totalPts = 0;
    let splitPts = {};
    if (data.extracurricular && data.extracurricular.length > 0) {
      data.extracurricular.forEach(ex => {
        if (ex.status === 'Verified') {
          let pts = parseFloat(ex.points_awarded) || 0;
          totalPts += pts;
          let seg = ex.segment || 'General';
          splitPts[seg] = (splitPts[seg] || 0) + pts;
        }
        addExtraRow(ex);
      });
    } else {
      exList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-600">No extra-curricular records.</td></tr>';
    }
    
    // Update progress bar
    const totalPtsEl = document.getElementById('studentTotalActivityPoints');
    if (totalPtsEl) totalPtsEl.innerText = totalPts;
    const progressEl = document.getElementById('studentActivityProgressBar');
    if (progressEl) progressEl.style.width = Math.min(100, totalPts) + '%';
    
    let splitHtml = '';
    if (Object.keys(splitPts).length > 0) {
      for (const [seg, pts] of Object.entries(splitPts)) {
        splitHtml += `
          <div class="flex justify-between items-center py-1">
            <span class="text-xs text-slate-300">${seg}</span>
            <span class="text-xs font-bold text-emerald-400">${pts}</span>
          </div>`;
      }
    } else {
      splitHtml = '<div class="text-xs text-slate-500 py-1">No verified points yet.</div>';
    }
    const splitListEl = document.getElementById('studentActivitySplitList');
    if (splitListEl) splitListEl.innerHTML = splitHtml;
  }

      

        

        // Populate Leaves
    const lList = document.getElementById('smdLeavesTable');
    if (lList) {
      lList.innerHTML = '';
      if (data.leaves && data.leaves.length > 0) {
        data.leaves.forEach(lv => {
          const tr = document.createElement('tr');
          tr.className = 'border-b border-slate-800/40';
          const statColor = lv.status === 'Approved' ? 'text-green-400' : (lv.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
          const safeLv = JSON.stringify(lv).replace(/'/g, "&apos;").replace(/\"/g, "&quot;");
          const parentInformedHtml = lv.parent_informed ? '<span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px]">Informed</span>' : '';
          
          // Tutor view: show Approve/Reject buttons for Pending leaves
          const isTutorView = !!document.getElementById('sidebarStudentName');
          let actionHtml = '';
          if (isTutorView && lv.status === 'Pending') {
            actionHtml = `
              <button onclick="approveLeave(${lv.id}, 'Approved')" class="px-2 py-1 bg-green-700/30 text-green-400 hover:bg-green-600 hover:text-white rounded text-[10px] font-bold mr-1 transition-premium">Approve</button>
              <button onclick="approveLeave(${lv.id}, 'Rejected')" class="px-2 py-1 bg-red-700/30 text-red-400 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition-premium">Reject</button>
            `;
          } else if (isTutorView) {
            actionHtml = `<button onclick='editLeave(${safeLv})' class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer"><span class="material-symbols-rounded text-sm">edit</span></button>`;
          } else {
            actionHtml = `<button onclick='editLeave(${safeLv})' class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer"><span class="material-symbols-rounded text-sm">edit</span></button>`;
          }

          tr.innerHTML = `
            <td class="p-3">${lv.semester || '-'}</td>
            <td class="p-3">${lv.leave_date || '-'}</td>
            <td class="p-3">${lv.no_of_days ? lv.no_of_days + ' day(s)' : '-'} ${parentInformedHtml}</td>
            <td class="p-3 max-w-[200px] truncate" title="${lv.reason || ''}">${lv.reason || '-'}</td>
            <td class="p-3 font-bold ${statColor}">${lv.status || 'Pending'}</td>
            <td class="p-3 text-right">${actionHtml}</td>
          `;
          lList.appendChild(tr);
        });
      } else {
        lList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">No leave records found.</td></tr>';
      }
    }

    // Populate Meetings
  const mList = document.getElementById('smdMeetingsList');
  if (mList) {
    mList.innerHTML = '';
    if (data.meetings && data.meetings.length > 0) {
      data.meetings.forEach(m => {
        const isTutorView = !!document.getElementById('sidebarStudentName');
        let deleteBtn = '';
        if (isTutorView) {
          deleteBtn = `<button onclick="deleteMeeting('${m.diary_id}')" class="text-red-400 hover:text-red-300 transition-colors flex items-center gap-0.5 cursor-pointer ml-3 font-bold text-[10px]"><span class="material-symbols-rounded text-sm">delete</span> Delete</button>`;
        }
        mList.innerHTML += `
          <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-800 space-y-2">
            <div class="flex justify-between items-start">
              <div class="flex items-center gap-2">
                <span class="text-xs bg-emerald-900/40 text-emerald-300 px-2 py-0.5 rounded font-bold border border-emerald-800/40">${m.date || '-'}</span>
                <span class="text-xs text-indigo-400 font-bold uppercase tracking-wider">${m.category || 'Meeting'}</span>
              </div>
              <div class="flex items-center">
                <span class="text-[10px] text-slate-500 font-bold">${m.logged_by_name || ''}</span>
                ${deleteBtn}
              </div>
            </div>
            <p class="text-xs text-slate-200 font-semibold leading-relaxed">${m.discussion_notes || ''}</p>
            ${m.action_taken ? '<p class="text-[10px] text-slate-400 mt-1"><span class="font-black text-slate-300 uppercase tracking-wide">Action:</span> ' + m.action_taken + '</p>' : ''}
          </div>
        `;
      });
    } else {
      mList.innerHTML = '<div class="text-center py-8 text-slate-500 text-sm">No meeting logs yet. Click "Record Session" to add the first one.</div>';
    }
  }

  // Populate Disciplinary Actions
  const dList = document.getElementById('smdDisciplineTable');
  if (dList) {
    dList.innerHTML = '';
    if (data.disciplinary && data.disciplinary.length > 0) {
      data.disciplinary.forEach(dc => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-slate-800/40';
        const safeDc = JSON.stringify(dc).replace(/'/g, "&apos;").replace(/\"/g, "&quot;");
        const isTutorView = !!document.getElementById('sidebarStudentName');
        let deleteBtn = '';
        if (isTutorView) {
          deleteBtn = `<button onclick="deleteDisc(${dc.id})" class="text-red-400 hover:text-red-300 text-xs cursor-pointer"><span class="material-symbols-rounded text-sm">delete</span></button>`;
        }
        tr.innerHTML = `
          <td class="p-3">${dc.date || '-'}</td>
          <td class="p-3 max-w-[200px] truncate" title="${dc.description || '-'}">${dc.description || '-'}</td>
          <td class="p-3">${dc.action_taken || '-'}</td>
          <td class="p-3 text-right">
            <button onclick='editDisc(${safeDc})' class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer mr-2"><span class="material-symbols-rounded text-sm">edit</span></button>
            ${deleteBtn}
          </td>
        `;
        dList.appendChild(tr);
      });
    } else {
      dList.innerHTML = '<tr><td colspan="4" class="p-6 text-center text-slate-500">Clean disciplinary record.</td></tr>';
    }
  }
}

function renderStudentBoardExams() {
  const semSelect = document.getElementById('smdBoardSemSelect');
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!semSelect || !sbList) return;

  // Save current UI state back to window.smdAcademicsList before switching
  syncBoardExamsToGlobal();

  const sem = semSelect.value;
  sbList.innerHTML = '';

  if (!sem) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">Select a semester to view subjects.</td></tr>';
    return;
  }

  const academics = window.smdAcademicsList || {};
  const subjects = academics[sem];

  if (!subjects || subjects.length === 0) {
    sbList.innerHTML = '<tr><td colspan="6" class="p-6 text-center text-slate-500">No subjects found for this semester.</td></tr>';
    return;
  }

  subjects.forEach(sub => {
    let br = sub.board_result || {};
    sbList.innerHTML += `
      <tr class="border-b border-slate-800/40 hover:bg-slate-900/50 board-grade-row transition-premium" data-sem="${sem}" data-code="${sub.subject_code}">
        <td class="p-3 text-slate-300 font-bold bg-slate-900/40 font-mono text-sm tracking-wider">${sub.subject_code}</td>
        <td class="p-3 text-slate-200 text-sm font-bold uppercase truncate max-w-[200px]" title="${sub.subject_name}">${sub.subject_name}</td>
        <td class="p-2"><input type="month" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-sm text-white bg-exam-my focus:border-blue-500 outline-none transition-premium" value="${br.exam_month_year || ''}"></td>
        <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-sm text-white bg-grade uppercase font-bold text-center focus:border-blue-500 outline-none transition-premium" value="${br.grade || ''}" placeholder="e.g. A+"></td>
        <td class="p-2">
          <select class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-sm text-white bg-pass font-bold focus:border-blue-500 outline-none transition-premium">
            <option value="1" ${br.passed == 1 || br.passed === undefined ? 'selected' : ''} class="text-emerald-400">Yes</option>
            <option value="0" ${br.passed == 0 && br.passed !== undefined ? 'selected' : ''} class="text-red-400">No</option>
          </select>
        </td>
        <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1.5 text-sm text-white font-bold bg-chances text-center focus:border-blue-500 outline-none transition-premium" value="${br.chances_taken || 1}" min="1"></td>
      </tr>
    `;
  });
}

function syncBoardExamsToGlobal() {
  const sbList = document.getElementById('smdSubjectBoardList');
  if (!sbList) return;
  const rows = sbList.querySelectorAll('.board-grade-row');
  if (rows.length === 0) return;

  const sem = rows[0].getAttribute('data-sem');
  if (!sem || !window.smdAcademicsList[sem]) return;

  rows.forEach(row => {
    let code = row.getAttribute('data-code');
    let sub = window.smdAcademicsList[sem].find(s => s.subject_code === code);
    if (sub) {
      sub.board_result = {
        exam_month_year: row.querySelector('.bg-exam-my').value,
        grade: row.querySelector('.bg-grade').value,
        passed: row.querySelector('.bg-pass').value,
        chances_taken: row.querySelector('.bg-chances').value || 1
      };
    }
  });
}

function addFamilyRow(name='', rel='', edu='', occ='', con='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 family-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-name" value="${name}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-rel" value="${rel}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-edu" value="${edu}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-occ" value="${occ}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white f-con" value="${con}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdFamilyList').appendChild(tr);
}

function addEducationRow(course='', inst='', year='', marks='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 edu-row';
  tr.innerHTML = `
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-course" value="${course}" placeholder="e.g. SSLC"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-inst" value="${inst}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-year" value="${year}"></td>
    <td class="p-2"><input type="text" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white e-marks" value="${marks}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  document.getElementById('smdEducationList').appendChild(tr);
}

function addExtraRow(ex) {
    if (!ex || typeof ex !== 'object') return;
    const tr = document.createElement('tr');
    tr.className = 'border-b border-slate-800/40 extra-row';
    const statusColor = ex.status === 'Verified' ? 'text-green-400' : (ex.status === 'Rejected' ? 'text-red-400' : 'text-amber-400');
    
    // HTML Escaping helper to safely output JSON
    const safeEx = JSON.stringify(ex).replace(/'/g, "&apos;").replace(/"/g, "&quot;");
    
    tr.innerHTML = `
      <td class="p-3 text-white text-center">${ex.semester || '-'}</td>
      <td class="p-3 text-white truncate max-w-[200px]" title="${ex.activity_name || ''}">${ex.activity_name || '-'}</td>
      <td class="p-3 text-white">${ex.activity_segment || '-'}</td>
      <td class="p-3 font-bold text-blue-400">${ex.points_claimed || '0'}</td>
      <td class="p-3 font-bold ${statusColor}">${ex.status || 'Pending'}</td>
      <td class="p-3 text-right">
        <button onclick="editStudentActivity(${safeEx})" class="text-indigo-400 hover:text-indigo-300 text-xs cursor-pointer mr-2"><span class="material-symbols-rounded text-sm">edit</span></button>
      </td>
    `;
    document.getElementById('smdExtraList').appendChild(tr);
  }

function addBoardRow(sem='', sgpa='', cgpa='', act='', id='') {
  const tr = document.createElement('tr');
  tr.className = 'border-b border-slate-800/40 board-row';
  tr.innerHTML = `
    <td class="p-2"><input type="number" class="w-16 bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sem" value="${sem}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-sgpa" value="${sgpa}"></td>
    <td class="p-2"><input type="number" step="0.01" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-cgpa" value="${cgpa}"></td>
    <td class="p-2"><input type="number" class="w-full bg-slate-900 border border-slate-700 rounded p-1 text-xs text-white b-act" value="${act}"></td>
    <td class="p-2 text-right"><button onclick="if(confirm('Are you sure you want to remove this row? This action cannot be undone.')) this.closest('tr').remove();" class="text-red-400 hover:text-red-300 text-xs cursor-pointer">&times;</button></td>
  `;
  const list = document.getElementById('smdBoardList');
  if (list) list.appendChild(tr);
}

function saveStudentMentoringData() {
    // Make sure the currently visible semester is synced to the global list before saving
    syncBoardExamsToGlobal();

    const boardGrades = [];
    if (window.smdAcademicsList) {
      for (const sem in window.smdAcademicsList) {
        if (Array.isArray(window.smdAcademicsList[sem])) {
          window.smdAcademicsList[sem].forEach(sub => {
            if (sub.board_result && sub.board_result.grade) {
              boardGrades.push({
                semester: sem,
                subject_code: sub.subject_code,
                exam_month_year: sub.board_result.exam_month_year,
                grade: sub.board_result.grade,
                passed: sub.board_result.passed,
                chances_taken: sub.board_result.chances_taken || 1
              });
            }
          });
        }
      }
    }

    const payload = {
      reg_no: window.TARGET_REG_NO,
      profile: {
        annual_income: document.getElementById('smd_annual_income').value,
        residential_status: document.getElementById('smd_residential_status').value,
        scholarships: document.getElementById('smd_scholarships').value,
        is_fee_waiver: document.getElementById('smd_fee_waiver').checked ? 1 : 0,
        guardian_name: document.getElementById('smd_guardian_name').value,
        guardian_relationship: document.getElementById('smd_guardian_relationship').value,
        guardian_mobile: document.getElementById('smd_guardian_mobile').value,
        guardian_address: document.getElementById('smd_guardian_address').value
      },
      extended_profile: {
        gender: document.getElementById('mdGender') ? document.getElementById('mdGender').value : null,
        religion: document.getElementById('mdReligion') ? document.getElementById('mdReligion').value : null,
        caste: document.getElementById('mdCaste') ? document.getElementById('mdCaste').value : null,
        special_category: document.getElementById('mdReservation') ? document.getElementById('mdReservation').value : null,
        reservation: document.getElementById('mdReservation') ? document.getElementById('mdReservation').value : null,
        quota: document.getElementById('mdQuota') ? document.getElementById('mdQuota').value : null,
        is_physically_disabled: document.getElementById('mdIsDisabled') ? document.getElementById('mdIsDisabled').value : 0,
        disability_category: document.getElementById('mdDisabilityCat') ? document.getElementById('mdDisabilityCat').value : null,
        guardian_occupation: document.getElementById('mdGuardianOcc') ? document.getElementById('mdGuardianOcc').value : null,
        monthly_family_income: document.getElementById('mdFamilyIncome') ? document.getElementById('mdFamilyIncome').value : null,
        has_vehicle_pass: document.getElementById('mdVehiclePass') ? document.getElementById('mdVehiclePass').value : 0,
        vehicle_pass_id: document.getElementById('mdVehiclePassId') ? document.getElementById('mdVehiclePassId').value : null,
        communication_address: document.getElementById('mdCommAddress') ? document.getElementById('mdCommAddress').value : null
      },
      family: Array.from(document.querySelectorAll('.family-row')).map(row => ({
      name: row.querySelector('.f-name').value,
      relationship: row.querySelector('.f-rel').value,
      education: row.querySelector('.f-edu').value,
      occupation: row.querySelector('.f-occ').value,
      contact_no: row.querySelector('.f-con').value
    })).filter(f => f.name !== ''),
    education: Array.from(document.querySelectorAll('.edu-row')).map(row => ({
      course: row.querySelector('.e-course').value,
      institution: row.querySelector('.e-inst').value,
      year_of_completion: row.querySelector('.e-year').value,
      total_percentage: row.querySelector('.e-marks').value
    })).filter(e => e.course !== ''),
    // Extra-curricular handled via separate popup modal now
    extracurricular: [],
    board: Array.from(document.querySelectorAll('.board-row')).map(row => ({
      semester: row.querySelector('.b-sem').value,
      sgpa: row.querySelector('.b-sgpa').value,
      cgpa: row.querySelector('.b-cgpa').value,
      activity_points: row.querySelector('.b-act').value
    })).filter(b => b.semester !== ''),
    board_grades: boardGrades
  };

  fetch('/api/student/mentoring/save-all', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(payload)
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'SUCCESS') {
      alert('Changes saved successfully!');
    } else {
      alert('Failed to save data: ' + (data.message || 'Unknown error'));
    }
  })
  .catch(err => {
    console.error(err);
    alert('Error communicating with server: ' + err.message);
  });
}

function showGlobalAlert(msg, type) {
  // A simple alert function for the student dashboard
  alert(msg);
}

function downloadMentoringPdf() {
  const regNo = window.TARGET_REG_NO || '{{ session("userId") }}';
  window.open('/diary/' + regNo + '/print', '_blank');
}

function openStudentActivityModal() {
    document.getElementById("studentActivityId").value = "";
    document.getElementById("studentActivityForm").reset();
    document.getElementById("studentActivityModalTitle").innerText = "Add Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function editStudentActivity(act) {
    document.getElementById("studentActivityId").value = act.activity_id || "";
    document.getElementById("studentActivitySemester").value = act.semester || "1";
    document.getElementById("studentActivitySegment").value = act.segment || "NCC";
    document.getElementById("studentActivityName").value = act.activity_name || "";
    document.getElementById("studentActivityLevel").value = act.level || "";
    document.getElementById("studentActivityPtsClaimed").value = act.points_claimed || 0;
    document.getElementById("studentActivityModalTitle").innerText = "Edit Activity";
    document.getElementById("addStudentActivityModal").classList.remove("hidden");
    document.getElementById("addStudentActivityModal").classList.add("flex");
}

function closeStudentActivityModal() {
    document.getElementById("addStudentActivityModal").classList.add("hidden");
    document.getElementById("addStudentActivityModal").classList.remove("flex");
}

function saveStudentActivity(e) {
    e.preventDefault();
    const payload = {
        reg_no: window.TARGET_REG_NO || '',
        activity_id: document.getElementById("studentActivityId").value,
        semester: document.getElementById("studentActivitySemester").value,
        segment: document.getElementById("studentActivitySegment").value,
        activity_name: document.getElementById("studentActivityName").value,
        level: document.getElementById("studentActivityLevel").value,
        points_claimed: document.getElementById("studentActivityPtsClaimed").value,
    };

    fetch("/api/student/mentoring/extra-curricular/save", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resData => {
        if(resData.status === "SUCCESS") {
            alert("Activity saved successfully!");
            closeStudentActivityModal();
            loadStudentMentoringDiary();
        } else {
            alert("Error: " + (resData.message || "Failed to save activity."));
        }
    })
    .catch(err => {
        console.error(err);
        alert("Error saving activity: " + err.message);
    });
}

  // Leave Record Functions
  function openLeaveModal() {
    if(document.getElementById("leaveForm")) document.getElementById("leaveForm").reset();
    if(document.getElementById("leaveId")) document.getElementById("leaveId").value = "";
    if(document.getElementById("leaveModalTitle")) document.getElementById("leaveModalTitle").innerText = "Add Leave Record";
    if(document.getElementById("addLeaveModal")) {
      document.getElementById("addLeaveModal").classList.remove("hidden");
      document.getElementById("addLeaveModal").classList.add("flex");
    }
  }
  function editLeave(lv) {
    if(document.getElementById("leaveId")) document.getElementById("leaveId").value = lv.id || "";
    if(document.getElementById("leaveSem")) document.getElementById("leaveSem").value = lv.semester || 1;
    
    if(document.getElementById("leaveDays")) document.getElementById("leaveDays").value = lv.no_of_days || "";
    
    if(document.getElementById("leaveDateFrom")) {
        let dates = (lv.leave_date || "").split(" to ");
        document.getElementById("leaveDateFrom").value = dates[0] || "";
        if(document.getElementById("leaveDateTo") && dates.length > 1) {
            document.getElementById("leaveDateTo").value = dates[1] || "";
        }
    }
    
    if(document.getElementById("leaveReason")) document.getElementById("leaveReason").value = lv.reason || "";
    if(document.getElementById("leaveStatus")) document.getElementById("leaveStatus").value = lv.status || "Pending";
    if(document.getElementById("leaveParent")) document.getElementById("leaveParent").checked = lv.parent_informed ? true : false;
    if(document.getElementById("leaveModalTitle")) document.getElementById("leaveModalTitle").innerText = "Edit Leave Record";
    if(document.getElementById("addLeaveModal")) {
      document.getElementById("addLeaveModal").classList.remove("hidden");
      document.getElementById("addLeaveModal").classList.add("flex");
    }
  }
  function closeLeaveModal() {
    if(document.getElementById("addLeaveModal")) {
      document.getElementById("addLeaveModal").classList.add("hidden");
      document.getElementById("addLeaveModal").classList.remove("flex");
    }
  }
  function saveLeave(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("leaveId").value,
      reg_no: window.TARGET_REG_NO || '',
      semester: document.getElementById("leaveSem").value,
      leave_date: (() => {
        let from = document.getElementById("leaveDateFrom").value;
        let to = document.getElementById("leaveDateTo") ? document.getElementById("leaveDateTo").value : "";
        return to ? from + " to " + to : from;
      })(),
      no_of_days: document.getElementById("leaveDays") ? document.getElementById("leaveDays").value : "",
      reason: document.getElementById("leaveReason").value,
      status: document.getElementById("leaveStatus").value,
      parent_informed: document.getElementById("leaveParent").checked
    };
    fetch("/api/mentoring/leave/save", {
      method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try {
                const errJson = JSON.parse(text);
                throw new Error(errJson.message || "Server Error");
            } catch(ex) {
                throw new Error("HTTP " + res.status + ": " + text.substring(0, 50));
            }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === "SUCCESS") {
        alert("Leave record saved successfully!");
        closeLeaveModal();
        loadStudentMentoringDiary(); // Reload UI
      } else alert("Error: " + resData.message);
    }).catch(err => {
        alert("Save Error: " + err.message);
    });
  }

  // Disciplinary Functions
  function openDiscModal() {
    if(document.getElementById("discForm")) document.getElementById("discForm").reset();
    if(document.getElementById("discId")) document.getElementById("discId").value = "";
    if(document.getElementById("discModalTitle")) document.getElementById("discModalTitle").innerText = "Record Incident";
    if(document.getElementById("addDiscModal")) {
      document.getElementById("addDiscModal").classList.remove("hidden");
      document.getElementById("addDiscModal").classList.add("flex");
    }
  }
  function editDisc(d) {
    if(document.getElementById("discId")) document.getElementById("discId").value = d.id || "";
    if(document.getElementById("discDate")) document.getElementById("discDate").value = d.date || "";
    if(document.getElementById("discDesc")) document.getElementById("discDesc").value = d.description || "";
    if(document.getElementById("discAction")) document.getElementById("discAction").value = d.action_taken || "";
    if(document.getElementById("discModalTitle")) document.getElementById("discModalTitle").innerText = "Edit Incident";
    if(document.getElementById("addDiscModal")) {
      document.getElementById("addDiscModal").classList.remove("hidden");
      document.getElementById("addDiscModal").classList.add("flex");
    }
  }
  function closeDiscModal() {
    if(document.getElementById("addDiscModal")) {
      document.getElementById("addDiscModal").classList.add("hidden");
      document.getElementById("addDiscModal").classList.remove("flex");
    }
  }
  function saveDisc(e) {
    e.preventDefault();
    const data = {
      id: document.getElementById("discId").value,
      reg_no: window.TARGET_REG_NO || '',
      date: document.getElementById("discDate").value,
      description: document.getElementById("discDesc").value,
      action_taken: document.getElementById("discAction").value
    };
    fetch("/api/mentoring/disciplinary/save", {
      method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(res => res.json()).then(resData => {
      if (resData.status === "SUCCESS") {
        closeDiscModal();
        loadStudentMentoringDiary();
      } else alert("Error: " + resData.message);
    });
  }


  // Mentor Meeting Functions
  function openSessionModal() {
    if(document.getElementById("sessionForm")) document.getElementById("sessionForm").reset();
    if(document.getElementById("sessionId")) document.getElementById("sessionId").value = "";
    if(document.getElementById("sessionModalTitle")) document.getElementById("sessionModalTitle").innerText = "Record Mentoring Session";
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.remove("hidden");
      document.getElementById("addSessionModal").classList.add("flex");
    }
  }
  function editSession(s) {
    if(document.getElementById("sessionId")) document.getElementById("sessionId").value = s.id || "";
    if(document.getElementById("sessionSem")) document.getElementById("sessionSem").value = s.semester || 1;
    if(document.getElementById("sessionDate")) document.getElementById("sessionDate").value = s.date || "";
    if(document.getElementById("sessionDiscussion")) document.getElementById("sessionDiscussion").value = s.discussion_points || "";
    if(document.getElementById("sessionAction")) document.getElementById("sessionAction").value = s.action_items || "";
    if(document.getElementById("sessionModalTitle")) document.getElementById("sessionModalTitle").innerText = "Edit Mentoring Session";
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.remove("hidden");
      document.getElementById("addSessionModal").classList.add("flex");
    }
  }
  function closeSessionModal() {
    if(document.getElementById("addSessionModal")) {
      document.getElementById("addSessionModal").classList.add("hidden");
      document.getElementById("addSessionModal").classList.remove("flex");
    }
  }
  function saveMentoringSession(e) {
    e.preventDefault();
    const data = {
      reg_no: window.TARGET_REG_NO || '',
      date: document.getElementById("sessionDate").value,
      category: 'Mentoring',
      discussion_notes: document.getElementById("sessionDiscussion").value,
      action_taken: document.getElementById("sessionAction").value
    };
    fetch("/api/mentoring/diary/add", {
      method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(data)
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try {
                const errJson = JSON.parse(text);
                throw new Error(errJson.message || "Server Error");
            } catch(ex) {
                throw new Error("HTTP " + res.status + ": " + text.substring(0, 50));
            }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === "SUCCESS") {
        closeSessionModal();
        loadStudentMentoringDiary();
      } else alert("Error: " + resData.message);
    }).catch(err => {
        alert("Save Error: " + err.message);
    });
  }

  // Leave Approval Function (Tutor/Mentor only)
  function approveLeave(leaveId, decision) {
    if (!confirm('Are you sure you want to ' + decision.toLowerCase() + ' this leave request?')) return;
    fetch('/api/mentoring/leave/approve', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ id: leaveId, status: decision })
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try { const j = JSON.parse(text); throw new Error(j.message || 'Server Error'); } catch(e) { throw new Error('HTTP ' + res.status); }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === 'SUCCESS') {
        loadStudentMentoringDiary();
      } else {
        alert('Error: ' + resData.message);
      }
    }).catch(err => {
      alert('Approval Error: ' + err.message);
    });
  }

  // Delete Meeting Function (Tutor/Mentor only)
  function deleteMeeting(diaryId) {
    if (!confirm('Are you sure you want to delete this meeting log?')) return;
    fetch('/api/mentoring/diary/delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ diary_id: diaryId })
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try { const j = JSON.parse(text); throw new Error(j.message || 'Server Error'); } catch(e) { throw new Error('HTTP ' + res.status); }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === 'SUCCESS') {
        loadStudentMentoringDiary();
      } else {
        alert('Error: ' + resData.message);
      }
    }).catch(err => {
      alert('Delete Error: ' + err.message);
    });
  }

  // Delete Disciplinary Action Function (Tutor/Mentor only)
  function deleteDisc(id) {
    if (!confirm('Are you sure you want to delete this disciplinary action record?')) return;
    fetch('/api/mentoring/disciplinary/delete', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify({ id: id })
    }).then(async res => {
        if (!res.ok) {
            const text = await res.text();
            try { const j = JSON.parse(text); throw new Error(j.message || 'Server Error'); } catch(e) { throw new Error('HTTP ' + res.status); }
        }
        return res.json();
    }).then(resData => {
      if (resData.status === 'SUCCESS') {
        loadStudentMentoringDiary();
      } else {
        alert('Error: ' + resData.message);
      }
    }).catch(err => {
      alert('Delete Error: ' + err.message);
    });
  }

</script>
