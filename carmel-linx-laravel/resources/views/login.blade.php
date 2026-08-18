<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - AMS</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSRF Token & System Support Metadata -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="author" content="Dhanush.A - Technical Support & Architecture, Carmel Polytechnic College">
  
  <style>
    body {
        font-family: "Plus Jakarta Sans", sans-serif;
    }
    @media (max-width: 1440px) {
      html, body {
        font-size: 13px !important;
      }
      .p-5, .p-6, .md\:p-6 {
        padding: 1rem !important;
      }
      .mb-4 {
        margin-bottom: 0.75rem !important;
      }
      .space-y-3 > :not([hidden]) ~ :not([hidden]) {
        margin-top: 0.5rem !important;
      }
    }
    select option {
      background-color: #0f172a !important;
      color: #ffffff !important;
    }
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .slide-up {
      animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUp {
      from { transform: translateY(30px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .loader-spinner {
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Mobile Login & Registration Screen Adjustments */
    @media (max-width: 767px) {
      body {
        align-items: flex-start !important;
        padding-top: 1.25rem !important;
        padding-bottom: 2rem !important;
      }

      /* Make card container wide and spacious on mobile */
      .max-w-md {
        max-width: 95% !important;
        width: 95% !important;
        padding: 1.5rem 1.25rem !important;
        margin-top: 0.25rem !important;
      }

      /* Role tab buttons on mobile */
      #tabStudent,
      #tabStaff {
        font-size: 1rem !important; /* 16px */
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
      }
      #tabStudent .material-symbols-rounded,
      #tabStaff .material-symbols-rounded {
        font-size: 1.25rem !important; /* 20px */
      }

      /* Labels and Helper Text */
      #loginSection label,
      #registerSection label {
        font-size: 0.92rem !important; /* ~14.7px */
        font-weight: 700 !important;
        margin-bottom: 0.4rem !important;
      }

      #staffLoginFields p {
        font-size: 0.82rem !important; /* 13px */
        color: #94a3b8 !important; /* slate-400 */
        margin-top: 0.35rem !important;
      }

      /* High readability input & select fields */
      #loginUserId,
      #loginMobileId,
      #loginPassword,
      #registerSection input[type="text"],
      #registerSection input[type="email"],
      #registerSection input[type="number"],
      #registerSection input[type="password"],
      #registerSection select {
        font-size: 1.15rem !important; /* ~18.5px */
        padding-top: 0.9rem !important;
        padding-bottom: 0.9rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        border-radius: 0.85rem !important;
      }

      /* Large, touch-friendly primary buttons */
      #loginSection button[type="submit"],
      #staffBiometricBtnContainer button {
        font-size: 1.05rem !important; /* 17px */
        padding-top: 0.85rem !important;
        padding-bottom: 0.85rem !important;
        border-radius: 0.85rem !important;
      }

      #bioBtnText,
      #loginBtnText {
        font-size: 1.05rem !important;
        font-weight: 700 !important;
      }

      #staffBiometricBtnContainer .material-symbols-rounded {
        font-size: 1.35rem !important; /* 21.5px */
      }

      /* High-contrast, touchable footer links */
      #loginSection .text-amber-400,
      #loginSection .text-blue-400 {
        font-size: 0.95rem !important; /* 15.2px */
        padding: 0.35rem 0.5rem !important;
        display: inline-block !important;
      }

      #loginSection .text-slate-400 {
        font-size: 0.9rem !important; /* 14.4px */
      }
    }
  </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-start md:items-center justify-center p-4 pt-6 md:pt-4 overflow-x-hidden relative">

  <!-- Premium Neon Mesh Background blobs -->
  <div class="absolute top-1/4 left-1/4 h-80 w-80 bg-blue-600/10 rounded-full blur-3xl animate-pulse duration-[8s] pointer-events-none"></div>
  <div class="absolute bottom-1/4 right-1/4 h-96 w-96 bg-indigo-500/10 rounded-full blur-3xl animate-pulse duration-[10s] pointer-events-none"></div>
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-900/10 via-transparent to-transparent pointer-events-none opacity-50"></div>

  <!-- Main Container -->
  <div class="w-full max-w-md bg-slate-900/40 backdrop-blur-2xl shadow-[0_0_50px_rgba(59,130,246,0.12)] rounded-3xl overflow-hidden border border-white/40 slide-up p-5 md:p-6 text-slate-100 relative z-10">
    
    <!-- Branding Header -->
    <div class="text-center mb-4">
      <img src="{{ asset('logo.jpg') }}" class="w-14 h-14 rounded-2xl mx-auto shadow-lg object-cover mb-2 select-none border border-slate-800/60">
      <h1 class="text-2xl font-black text-white tracking-tight inline-flex items-center justify-center gap-0.5">
        <span class="bg-gradient-to-r from-white via-slate-100 to-blue-200 bg-clip-text text-transparent">Carmel Linx</span>
        <span class="text-[10px] font-extrabold text-blue-400 align-super select-none -mt-2">™</span>
      </h1>
      <p class="text-slate-400 font-medium text-[10px] mt-0.5 tracking-wide">Learn • Innovate • Network • eXchange</p>
    </div>

    <!-- Screen Toggle: Login vs Register -->
    <div id="authGate">
      
      <!-- Login Section -->
      <div id="loginSection">
        <!-- Login Role Tabs -->
        <div class="flex bg-slate-950/60 p-1 rounded-2xl mb-4 border border-slate-800/60">
          <button id="tabStudent" onclick="toggleRoleTab('student')" class="flex-1 py-2 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-base">school</span> Student
          </button>
          <button id="tabStaff" onclick="toggleRoleTab('staff')" class="flex-1 py-2 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-base">badge</span> Staff Portal
          </button>
        </div>

        <form onsubmit="handleLogin(event)" class="space-y-3">
          <!-- Student Login Fields -->
          <div id="studentLoginFields" class="space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Register / Admission / SBTE Number</label>
              <input type="text" id="loginUserId" class="w-full px-3 py-2.5 rounded-xl border border-slate-800 bg-slate-950/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="e.g. REG24EC01">
            </div>
          </div>

          <!-- Staff Login Fields -->
          <div id="staffLoginFields" class="space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Mobile Number (Login ID)</label>
              <input type="text" id="loginMobileId" inputmode="numeric" maxlength="10"
                class="w-full px-3 py-2.5 rounded-xl border border-slate-800 bg-slate-955/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="e.g. 9845000001">
              <p class="text-[10px] text-slate-500 font-medium mt-1">Enter your 10-digit registered mobile number</p>
            </div>
          </div>

          <!-- Staff Biometric Quick-Pass Dedicated Card -->
          <div id="biometricFirstCard" class="hidden p-4 rounded-2xl bg-indigo-950/40 border border-indigo-500/30 text-center space-y-3 slide-up">
            <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center mx-auto text-indigo-400 shadow-inner">
              <span class="material-symbols-rounded text-3xl">fingerprint</span>
            </div>
            <div>
              <h3 class="font-extrabold text-white text-base">Quick Fingerprint Login</h3>
              <p class="text-xs text-indigo-300/80 mt-0.5">Touch sensor on your device to enter</p>
            </div>
            <button type="button" onclick="handleBiometricLogin()" class="w-full py-3 bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/25 transition-premium flex items-center justify-center gap-2 cursor-pointer text-sm">
              <span class="material-symbols-rounded text-xl">fingerprint</span>
              <span id="bioBtnText">Scan Fingerprint to Enter</span>
              <div id="bioSpinner" class="loader-spinner border-t-white hidden"></div>
            </button>
            <button type="button" onclick="showPasswordFallback()" class="text-xs font-bold text-slate-400 hover:text-slate-200 transition-premium underline block mx-auto pt-1 cursor-pointer">
              Use Mobile ID & Password Instead
            </button>
          </div>

          <!-- Alert Container -->
          <div id="loginAlert" class="hidden p-3 rounded-xl text-sm font-semibold"></div>

          <!-- Password & Submit Fields Group -->
          <div id="passwordFieldsGroup" class="space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
              <input type="password" id="loginPassword" class="w-full px-3 py-2.5 rounded-xl border border-slate-800 bg-slate-955/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="********">
            </div>

            <!-- Submit -->
            <button type="submit" id="loginSubmitBtn" class="w-full py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-premium flex items-center justify-center gap-2 cursor-pointer text-sm">
              <span id="loginBtnText">Access Portal</span>
              <div id="loginSpinner" class="loader-spinner border-t-white hidden"></div>
            </button>
          </div>
        </form>

        <div class="text-center mt-4 space-y-2">
          <div class="flex justify-center text-xs font-bold mb-1">
            <a href="#" onclick="openRecoveryModal()" class="text-amber-400 hover:text-amber-300 transition-premium">Forgot ID / Password?</a>
          </div>
          <p class="text-slate-400 text-xs">Don't have an account?</p>
          <div class="flex justify-center gap-4 text-xs font-bold">
            <a href="#" onclick="showRegister('student')" class="text-blue-400 hover:text-blue-300 transition-premium">Register as Student</a>
            <span class="text-slate-700">|</span>
            <a href="#" onclick="showRegister('staff')" class="text-blue-400 hover:text-blue-300 transition-premium">Register as Staff</a>
          </div>
        </div>
      </div>

      <!-- Registration Section (Student & Staff) -->
      <div id="registerSection" class="hidden">
        <h2 id="registerTitle" class="text-xl font-extrabold text-white mb-6 text-center border-b border-slate-800 pb-3">Register Student</h2>
        
        <form id="registerForm" onsubmit="handleRegistration(event)" class="space-y-4 max-h-[420px] overflow-y-auto pr-2 custom-scrollbar">
          <!-- Shared Fields -->
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Full Name</label>
            <input type="text" id="regName" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="Enter Full Name">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Email Address</label>
            <input type="email" id="regEmail" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="name@carmelpoly.edu.in">
          </div>

          <!-- Student-Only Fields -->
          <div id="regStudentFields" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">SBTE Register No (Optional)</label>
                <input type="text" id="regStudentId" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="e.g. 24010123">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Admission No</label>
                <input type="text" id="regStudentAdm" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="ADM24EC01">
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Branch</label>
                <select id="regStudentBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium text-sm">
                  <option value="EL">Electronics Engineering (EL)</option>
                  <option value="ME">Mechanical Engineering (ME)</option>
                  <option value="CE">Civil Engineering (CE)</option>
                  <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="AU">Automobile Engineering (AU)</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Admission Year</label>
                <input type="number" id="regStudentYear" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="2026" value="2026">
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Current Semester</label>
                <select id="regStudentSem" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium text-sm">
                  <option value="S1" selected>S1</option>
                  <option value="S2">S2</option>
                  <option value="S3">S3</option>
                  <option value="S4">S4</option>
                  <option value="S5">S5</option>
                  <option value="S6">S6</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Admission Type</label>
                <select id="regStudentAdmissionType" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium text-sm">
                  <option value="Regular" selected>Regular</option>
                  <option value="LET">LET (Lateral Entry)</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Staff-Only Fields -->
          <div id="regStaffFields" class="space-y-4 hidden">
            <div>
              <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Mobile No (Login ID)</label>
              <input type="text" id="regStaffMobile" inputmode="numeric" maxlength="10" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="10-digit Mobile Number">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Branch</label>
                <select id="regStaffBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium text-sm">
                  <option value="EL">Electronics Engineering (EL)</option>
                  <option value="ME">Mechanical Engineering (ME)</option>
                  <option value="CE">Civil Engineering (CE)</option>
                  <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="AU">Automobile Engineering (AU)</option>
                  <option value="GEN_AIDED">General Department Aided (GEN_AIDED)</option>
                  <option value="GEN_SF">General Department Self Finance (GEN_SF)</option>
                  <option value="Admin">Administration</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Designation</label>
                <select id="regStaffDesig" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium text-sm">
                  <option value="HOD">Head of the Department (HOD)</option>
                  <option value="Gen_Dept_Coordinator_Aided">Gen Dept Coordinator Aided</option>
                  <option value="Gen_Dept_Coordinator_Self_Finance">Gen Dept Coordinator Self Finance</option>
                  <option value="Lecturer">Lecturer</option>
                  <option value="Demonstrator">Demonstrator</option>
                  <option value="Physical_Instructor">Physical Instructor</option>
                  <option value="Trade_Instructor">Trade Instructor</option>
                  <option value="Tradesman">Tradesman</option>
                  <option value="Laboratory_Assistant">Laboratory Assistant</option>
                  <option value="Workshop_Instructor">Workshop Instructor</option>
                  <option value="Workshop_Superintendent">Workshop Superintendent</option>
                  <option value="Principal">Principal</option>
                  <option value="Chairman">Chairman</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Password & Photo -->
          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Password</label>
            <input type="password" id="regPassword" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="********">
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Passport Photo</label>
            <input type="file" id="regPhoto" accept="image/*" onchange="previewRegistrationPhoto(event)" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20 transition-premium cursor-pointer">
            <div id="regPhotoPreviewContainer" class="hidden mt-2 p-2 bg-slate-950/60 border border-slate-800 rounded-2xl flex items-center gap-3">
              <img id="regPhotoPreviewImg" src="" class="w-12 h-12 rounded-xl object-cover border border-slate-700 shadow-inner">
              <div class="overflow-hidden">
                <span class="text-xs font-bold text-slate-300 block">Photo Selected</span>
                <span id="regPhotoFileName" class="text-[10px] text-slate-500 block truncate max-w-[200px]">filename.jpg</span>
              </div>
              <button type="button" onclick="clearRegistrationPhoto()" class="ml-auto text-slate-500 hover:text-red-400 cursor-pointer flex items-center justify-center p-1">
                <span class="material-symbols-rounded text-sm">delete</span>
              </button>
            </div>
          </div>

          <!-- Alert Container -->
          <div id="regAlert" class="hidden p-4 rounded-xl text-sm font-semibold"></div>

          <!-- Submit & Back -->
          <div class="flex gap-3 pt-2">
            <button type="button" onclick="showLogin()" class="flex-1 py-3 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
              Back to Login
            </button>
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
              <span id="regBtnText">Register</span>
              <div id="regSpinner" class="loader-spinner border-t-white hidden"></div>
            </button>
          </div>
        </form>
      </div>

    </div>

    <!-- System Support & Platform Metadata Footer -->
    <div class="mt-5 pt-3 border-t border-slate-800/80 text-center space-y-0.5 select-none">
      <p class="text-[11px] text-slate-400 font-semibold flex items-center justify-center gap-1.5">
        <span class="material-symbols-rounded text-xs text-blue-400">engineering</span>
        <span>Support: <strong class="text-slate-200 font-extrabold">Dhanush.A</strong></span>
      </p>
      <p class="text-[10px] text-slate-500 font-medium">Dept. of Electronics | Carmel Polytechnic College</p>
      <span class="text-[9px] text-slate-600 font-mono block tracking-wider">Carmel Linx™ AMS v2026.1 Beta</span>
    </div>

  </div>

  <!-- RECOVERY MODAL -->
  <div id="recoveryModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4">
      <div class="flex justify-between items-center border-b border-slate-800 pb-3">
        <h3 class="font-bold text-slate-200 text-sm flex items-center gap-2">
          <span class="material-symbols-rounded text-amber-400 text-base">vpn_key</span> Recover Account
        </h3>
        <button onclick="closeRecoveryModal()" class="text-slate-400 hover:text-white cursor-pointer"><span class="material-symbols-rounded text-xs">close</span></button>
      </div>

      <form id="recoveryForm" onsubmit="handleRecovery(event)" class="space-y-4">
        <p class="text-xs text-slate-400 leading-relaxed">
          Forgot your username or password? Enter your registered email address below, and we will email your account details to your inbox.
        </p>
        <div>
          <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Registered Email Address</label>
          <input type="email" id="recoveryEmail" required class="w-full px-3 py-2.5 rounded-xl border border-slate-800 bg-slate-950/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium text-sm" placeholder="name@carmelpoly.in">
        </div>

        <!-- Alert Container -->
        <div id="recoveryAlert" class="hidden p-3 rounded-xl text-sm font-semibold"></div>

        <!-- Submit & Close -->
        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closeRecoveryModal()" class="flex-1 py-2.5 border border-slate-850 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
            Cancel
          </button>
          <button type="submit" class="flex-1 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-slate-950 rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
            <span id="recoveryBtnText">Send Mail</span>
            <div id="recoverySpinner" class="loader-spinner border-t-slate-950 hidden"></div>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- REGISTRATION SUCCESS MODAL -->
  <div id="regSuccessModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-premium">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl space-y-4 text-center">
      <div class="flex justify-center">
        <span class="material-symbols-rounded text-green-400 text-5xl bg-green-500/10 p-4 rounded-full">check_circle</span>
      </div>
      <h3 class="font-bold text-slate-100 text-lg">Registration Success!</h3>
      <p class="text-sm text-slate-300">
        Your student profile registration is successful and pending Class Tutor approval.
      </p>
      
      <div class="bg-slate-950 p-4 rounded-2xl border border-slate-800 space-y-2">
        <span class="text-xs text-slate-500 uppercase tracking-wider block">Your Generated Login ID</span>
        <span id="successLoginId" class="text-xl font-mono font-extrabold text-blue-400 block tracking-widest select-all"></span>
      </div>
      
      <p class="text-xs text-amber-400 font-semibold flex items-center justify-center gap-1.5 bg-amber-500/10 p-2.5 rounded-xl border border-amber-500/20">
        <span class="material-symbols-rounded text-sm">photo_camera</span> Please take a screenshot / note down this login ID!
      </p>
      
      <div>
        <button onclick="closeRegSuccessModal()" class="w-full py-2.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold shadow-lg transition-premium text-sm cursor-pointer">
          Done, Go to Login
        </button>
      </div>
    </div>
  </div>

  <script>
    let activeRole = "staff";

    // Default to Staff tab on load
    document.addEventListener('DOMContentLoaded', () => {
      toggleRoleTab('staff');
      
      const lastMobile = localStorage.getItem('carmel_last_staff_mobile');
      if (lastMobile && document.getElementById('loginMobileId')) {
        document.getElementById('loginMobileId').value = lastMobile;
      }
      
      document.getElementById('loginMobileId').focus();

      // Enforce 10 digit numeric-only constraints on mobile fields
      const enforceMobileLimit = (el) => {
        if (!el) return;
        el.addEventListener('input', (e) => {
          e.target.value = e.target.value.replace(/[^0-9]/g, '');
          if (e.target.value.length > 10) {
            e.target.value = e.target.value.slice(0, 10);
          }
        });
      };
      enforceMobileLimit(document.getElementById('loginMobileId'));
      enforceMobileLimit(document.getElementById('regStaffMobile'));

      checkBiometricSupport();
    });

    function checkBiometricSupport() {
      const bioCard = document.getElementById('biometricFirstCard');
      const staffFields = document.getElementById('staffLoginFields');
      const passGroup = document.getElementById('passwordFieldsGroup');
      const hasBioCred = !!(localStorage.getItem('carmel_biometric_cred_id') || localStorage.getItem('carmel_registered_biometric_mobile'));

      if (window.PublicKeyCredential && activeRole === 'staff' && hasBioCred) {
        if (bioCard) bioCard.classList.remove('hidden');
        if (staffFields) staffFields.classList.add('hidden');
        if (passGroup) passGroup.classList.add('hidden');
      } else {
        if (bioCard) bioCard.classList.add('hidden');
        if (activeRole === 'staff') {
          if (staffFields) staffFields.classList.remove('hidden');
          if (passGroup) passGroup.classList.remove('hidden');
        }
      }
    }

    function showPasswordFallback() {
      const bioCard = document.getElementById('biometricFirstCard');
      const staffFields = document.getElementById('staffLoginFields');
      const passGroup = document.getElementById('passwordFieldsGroup');
      const mobileInput = document.getElementById('loginMobileId');

      if (bioCard) bioCard.classList.add('hidden');
      if (staffFields) staffFields.classList.remove('hidden');
      if (passGroup) passGroup.classList.remove('hidden');

      const lastMobile = localStorage.getItem('carmel_registered_biometric_mobile') || localStorage.getItem('carmel_last_staff_mobile');
      if (lastMobile && mobileInput) {
        mobileInput.value = lastMobile;
      }

      setTimeout(() => {
        const pwdInput = document.getElementById('loginPassword');
        if (pwdInput) pwdInput.focus();
      }, 50);
    }

    function toggleRoleTab(role) {
      activeRole = role;
      const tabStudent = document.getElementById('tabStudent');
      const tabStaff = document.getElementById('tabStaff');
      const sFields = document.getElementById('studentLoginFields');
      const fFields = document.getElementById('staffLoginFields');
      const passGroup = document.getElementById('passwordFieldsGroup');

      if (role === 'student') {
        tabStudent.className = "flex-1 py-2 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        tabStaff.className = "flex-1 py-2 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        sFields.classList.remove('hidden');
        fFields.classList.add('hidden');
        if (passGroup) passGroup.classList.remove('hidden');
        setTimeout(() => document.getElementById('loginUserId').focus(), 50);
      } else {
        tabStaff.className = "flex-1 py-2 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        tabStudent.className = "flex-1 py-2 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        sFields.classList.add('hidden');
        setTimeout(() => document.getElementById('loginMobileId').focus(), 50);
      }

      checkBiometricSupport();
    }

    function base64ToBuffer(base64) {
      const binary = atob(base64.replace(/-/g, '+').replace(/_/g, '/'));
      const len = binary.length;
      const bytes = new Uint8Array(len);
      for (let i = 0; i < len; i++) {
        bytes[i] = binary.charCodeAt(i);
      }
      return bytes.buffer;
    }

    function bufferToBase64(buffer) {
      const binary = String.fromCharCode(...new Uint8Array(buffer));
      return btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    }

    async function handleBiometricLogin() {
      const loginAlert = document.getElementById('loginAlert');
      const bioSpinner = document.getElementById('bioSpinner');
      const bioBtnText = document.getElementById('bioBtnText');
      let mobileNo = document.getElementById('loginMobileId').value.trim();
      const savedCredId = localStorage.getItem('carmel_biometric_cred_id') || '';

      if (!mobileNo) {
        mobileNo = localStorage.getItem('carmel_registered_biometric_mobile') || localStorage.getItem('carmel_last_staff_mobile') || '';
        if (mobileNo) {
          document.getElementById('loginMobileId').value = mobileNo;
        }
      }

      loginAlert.classList.add('hidden');
      bioSpinner.classList.remove('hidden');
      bioBtnText.innerText = "Scanning Fingerprint...";

      try {
        const optRes = await fetch('/api/webauthn/auth-options', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({ mobileNo, credentialId: savedCredId })
        });
        const optData = await optRes.json();

        if (optData.status !== 'SUCCESS') {
          localStorage.removeItem('carmel_biometric_cred_id');
          localStorage.removeItem('carmel_registered_biometric_mobile');
          showError(loginAlert, bioSpinner, bioBtnText, optData.message);
          setTimeout(() => showPasswordFallback(), 1200);
          return;
        }

        const options = optData.options;
        options.challenge = base64ToBuffer(options.challenge);
        if (options.allowCredentials && options.allowCredentials.length > 0) {
          options.allowCredentials = options.allowCredentials.map(c => ({
            ...c,
            id: base64ToBuffer(c.id)
          }));
        }

        const credential = await navigator.credentials.get({ publicKey: options });

        bioBtnText.innerText = "Authenticating...";

        const credentialId = bufferToBase64(credential.rawId);

        const authRes = await fetch('/api/webauthn/authenticate', {
          method: 'POST',
          headers: getHeaders(),
          body: JSON.stringify({
            mobileNo,
            credentialId
          })
        });
        const authData = await authRes.json();

        if (authData.status === 'SUCCESS') {
          localStorage.setItem('carmel_biometric_cred_id', credentialId);
          if (authData.id) {
            localStorage.setItem('carmel_registered_biometric_mobile', authData.id);
            localStorage.setItem('carmel_last_staff_mobile', authData.id);
          }
          loginAlert.className = "p-4 rounded-xl text-sm font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
          loginAlert.innerText = "Fingerprint verified! Access granted...";
          window.location.href = authData.route;
        } else {
          localStorage.removeItem('carmel_biometric_cred_id');
          localStorage.removeItem('carmel_registered_biometric_mobile');
          showError(loginAlert, bioSpinner, bioBtnText, authData.message);
          setTimeout(() => showPasswordFallback(), 1200);
        }
      } catch (err) {
        if (err.name === 'NotAllowedError') {
          showError(loginAlert, bioSpinner, bioBtnText, "Fingerprint scan cancelled.");
        } else {
          showError(loginAlert, bioSpinner, bioBtnText, "Biometric error: " + (err.message || "Failed to scan fingerprint."));
        }
        setTimeout(() => showPasswordFallback(), 1200);
      }
    }

    function showRegister(type) {
      document.getElementById('loginSection').classList.add('hidden');
      document.getElementById('registerSection').classList.remove('hidden');
      const rTitle = document.getElementById('registerTitle');
      const regS = document.getElementById('regStudentFields');
      const regF = document.getElementById('regStaffFields');
      
      document.getElementById('registerForm').reset();
      clearRegistrationPhoto();
      document.getElementById('regAlert').classList.add('hidden');

      if (type === 'student') {
        activeRole = "student";
        rTitle.innerText = "Register Student Profile";
        regS.classList.remove('hidden');
        regF.classList.add('hidden');
      } else {
        activeRole = "staff";
        rTitle.innerText = "Register Academic Staff";
        regF.classList.remove('hidden');
        regS.classList.add('hidden');
      }
    }

    let compressedPhotoBlob = null;

    function previewRegistrationPhoto(event) {
      const file = event.target.files[0];
      const container = document.getElementById('regPhotoPreviewContainer');
      const img = document.getElementById('regPhotoPreviewImg');
      const name = document.getElementById('regPhotoFileName');
      const regAlert = document.getElementById('regAlert');
      const spinner = document.getElementById('regSpinner');
      const btnText = document.getElementById('regBtnText');
      
      compressedPhotoBlob = null;

      if (!file) {
        clearRegistrationPhoto();
        return;
      }

      // Validate Image Type
      const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
      if (!validTypes.includes(file.type.toLowerCase()) && !file.type.startsWith('image/')) {
        showRegError(regAlert, spinner, btnText, "Image type mismatch: Selected file is not a valid photo. Please select a JPG, PNG, or WebP image.");
        clearRegistrationPhoto();
        return;
      }

      const reader = new FileReader();
      reader.onerror = function() {
        showRegError(regAlert, spinner, btnText, "Image read error: Unable to read selected photo file.");
        clearRegistrationPhoto();
      };

      reader.onload = function(e) {
        const image = new Image();
        image.onerror = function() {
          showRegError(regAlert, spinner, btnText, "Image format mismatch: File appears to be damaged or not a valid image format.");
          clearRegistrationPhoto();
        };

        image.onload = function() {
          // Perform Client-Side Canvas Downscaling & Compression (Max 800px)
          const canvas = document.createElement('canvas');
          const maxDim = 800;
          let width = image.width;
          let height = image.height;

          if (width > maxDim || height > maxDim) {
            if (width > height) {
              height = Math.round((height * maxDim) / width);
              width = maxDim;
            } else {
              width = Math.round((width * maxDim) / height);
              height = maxDim;
            }
          }

          canvas.width = width;
          canvas.height = height;

          const ctx = canvas.getContext('2d');
          ctx.fillStyle = '#FFFFFF';
          ctx.fillRect(0, 0, width, height);
          ctx.drawImage(image, 0, 0, width, height);

          canvas.toBlob((blob) => {
            if (!blob) {
              showRegError(regAlert, spinner, btnText, "Image compression error: Failed to process photo for upload.");
              clearRegistrationPhoto();
              return;
            }

            if (blob.size > 5 * 1024 * 1024) {
              showRegError(regAlert, spinner, btnText, "Image size mismatch: Selected photo exceeds the maximum allowed size of 5MB.");
              clearRegistrationPhoto();
              return;
            }

            compressedPhotoBlob = blob;
            img.src = canvas.toDataURL('image/jpeg', 0.85);
            name.innerText = file.name + ` (${Math.round(blob.size / 1024)} KB)`;
            container.classList.remove('hidden');
          }, 'image/jpeg', 0.85);
        };

        image.src = e.target.result;
      };

      reader.readAsDataURL(file);
    }

    function clearRegistrationPhoto() {
      compressedPhotoBlob = null;
      const fileInput = document.getElementById('regPhoto');
      if (fileInput) fileInput.value = '';
      const container = document.getElementById('regPhotoPreviewContainer');
      if (container) container.classList.add('hidden');
      const img = document.getElementById('regPhotoPreviewImg');
      if (img) img.src = '';
    }

    function openRecoveryModal() {
      document.getElementById('recoveryForm').reset();
      const alertEl = document.getElementById('recoveryAlert');
      alertEl.classList.add('hidden');
      const modal = document.getElementById('recoveryModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeRecoveryModal() {
      const modal = document.getElementById('recoveryModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    function handleRecovery(e) {
      e.preventDefault();
      const alertEl = document.getElementById('recoveryAlert');
      const spinner = document.getElementById('recoverySpinner');
      const btnText = document.getElementById('recoveryBtnText');
      const email = document.getElementById('recoveryEmail').value.trim();

      alertEl.classList.add('hidden');
      spinner.classList.remove('hidden');
      btnText.innerText = "Processing...";

      fetch('/api/auth/recover-account', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ email })
      })
      .then(res => res.json())
      .then(data => {
        spinner.classList.add('hidden');
        btnText.innerText = "Send Mail";
        if (data.status === 'SUCCESS') {
          alertEl.className = "p-3 rounded-xl text-xs font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
          alertEl.innerText = "Account details sent! Please check your inbox.";
        } else {
          alertEl.className = "p-3 rounded-xl text-xs font-semibold bg-red-950/40 text-red-400 border border-red-900 block";
          alertEl.innerText = data.message;
        }
        alertEl.classList.remove('hidden');
      })
      .catch(err => {
        spinner.classList.add('hidden');
        btnText.innerText = "Send Mail";
        alertEl.className = "p-3 rounded-xl text-xs font-semibold bg-red-950/40 text-red-400 border border-red-900 block";
        alertEl.innerText = "Error: " + err.message;
        alertEl.classList.remove('hidden');
      });
    }

    function showLogin() {
      document.getElementById('registerSection').classList.add('hidden');
      document.getElementById('loginSection').classList.remove('hidden');
      toggleRoleTab(activeRole);
    }

    function closeRegSuccessModal() {
      const modal = document.getElementById('regSuccessModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
      showLogin();
    }

    // Helper: get standard Laravel CSRF token header
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function handleLogin(e) {
      e.preventDefault();
      
      const loginAlert = document.getElementById('loginAlert');
      const spinner = document.getElementById('loginSpinner');
      const btnText = document.getElementById('loginBtnText');
      
      loginAlert.classList.add('hidden');
      spinner.classList.remove('hidden');
      btnText.innerText = "Verifying...";
      
      let userId = activeRole === 'student' 
        ? document.getElementById('loginUserId').value.trim()
        : document.getElementById('loginMobileId').value.trim();
      let password = document.getElementById('loginPassword').value.trim();
      
      if (!userId || !password) {
        showError(loginAlert, spinner, btnText, "Please fill in all credentials.");
        return;
      }

      fetch('/login', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, password, roleType: activeRole })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "SUCCESS") {
          loginAlert.className = "p-4 rounded-xl text-sm font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
          loginAlert.innerText = "Access granted! Redirecting...";
          window.location.href = data.route;
        } else {
          showError(loginAlert, spinner, btnText, data.message);
        }
      })
      .catch(err => {
        showError(loginAlert, spinner, btnText, "Server communication failed.");
      });
    }

    function handleRegistration(e) {
      e.preventDefault();
      
      const regAlert = document.getElementById('regAlert');
      const spinner = document.getElementById('regSpinner');
      const btnText = document.getElementById('regBtnText');
      
      regAlert.classList.add('hidden');
      spinner.classList.remove('hidden');
      btnText.innerText = "Submitting...";

      const formData = new FormData();
      formData.append('name', document.getElementById('regName').value);
      formData.append('email', document.getElementById('regEmail').value);
      formData.append('password', document.getElementById('regPassword').value);
      
      const photoFileInput = document.getElementById('regPhoto');
      const photoFile = photoFileInput ? photoFileInput.files[0] : null;
      if (compressedPhotoBlob) {
        formData.append('photo', compressedPhotoBlob, photoFile ? photoFile.name : 'photo.jpg');
      } else if (photoFile) {
        formData.append('photo', photoFile);
      }

      let url = '/register/student';
      if (activeRole === 'student') {
        formData.append('sbteRegNo', document.getElementById('regStudentId').value);
        formData.append('admNo', document.getElementById('regStudentAdm').value);
        formData.append('branch', document.getElementById('regStudentBranch').value);
        formData.append('admissionYear', document.getElementById('regStudentYear').value);
        formData.append('admissionType', document.getElementById('regStudentAdmissionType').value);
        formData.append('semester', document.getElementById('regStudentSem').value);
      } else {
        url = '/register/staff';
        formData.append('mobileNo', document.getElementById('regStaffMobile').value);
        formData.append('branch', document.getElementById('regStaffBranch').value);
        formData.append('designation', document.getElementById('regStaffDesig').value);
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(async res => {
        let data = null;
        try {
          data = await res.json();
        } catch (err) {
          data = null;
        }

        if (!res.ok) {
          if (res.status === 413) {
            throw new Error("Image size mismatch: Photo payload is too large for the server limit. Please upload a photo under 5MB.");
          } else if (res.status === 422) {
            throw new Error((data && data.message) ? data.message : "Image size or format mismatch. Please upload a valid JPG/PNG photo under 5MB.");
          }
          throw new Error((data && data.message) ? data.message : `Registration request failed (HTTP ${res.status}).`);
        }

        return data;
      })
      .then(data => {
        if (data && data.status === "SUCCESS") {
          spinner.classList.add('hidden');
          btnText.innerText = "Register";
          if (activeRole === 'student') {
            document.getElementById('successLoginId').innerText = data.regNo;
            const successModal = document.getElementById('regSuccessModal');
            successModal.classList.remove('hidden');
            successModal.classList.add('flex');
          } else {
            regAlert.className = "p-4 rounded-xl text-sm font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
            regAlert.innerText = data.message;
            setTimeout(() => showLogin(), 2000);
          }
        } else {
          showRegError(regAlert, spinner, btnText, (data && data.message) ? data.message : "Registration error occurred.");
        }
      })
      .catch(err => {
        let msg = err.message || "Registration request failed.";
        if (msg.includes("Failed to fetch") || msg.includes("NetworkError")) {
          msg = "Photo upload or network error: Image size or type mismatch. Please select a valid photo file (JPG/PNG under 5MB).";
        }
        showRegError(regAlert, spinner, btnText, msg);
      });
    }

    function showError(alertEl, spinner, btnText, msg) {
      alertEl.className = "p-4 rounded-xl text-sm font-semibold bg-red-950/40 text-red-400 border border-red-900/60 block";
      alertEl.innerText = msg;
      spinner.classList.add('hidden');
      btnText.innerText = "Access Portal";
    }

    // Custom scrollbar classes
    const styleEl = document.createElement('style');
    styleEl.innerHTML = `
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.5);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.7);
        }
    `;
    document.head.appendChild(styleEl);

    function showRegError(alertEl, spinner, btnText, msg) {
      alertEl.className = "p-4 rounded-xl text-sm font-semibold bg-red-950/40 text-red-400 border border-red-900/60 block";
      alertEl.innerText = msg;
      spinner.classList.add('hidden');
      btnText.innerText = "Register";
    }
  </script>
</body>
</html>
