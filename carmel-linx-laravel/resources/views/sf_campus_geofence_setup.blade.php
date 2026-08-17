<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus GPS Location Core Setup | Carmel Linx</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet.js Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --warning: #f59e0b;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            padding-bottom: 40px;
        }

        .header {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-btn {
            color: var(--text-muted);
            font-size: 0.95rem;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }

        .container {
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 20px;
        }

        .setup-grid {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 24px;
        }

        @media (max-width: 868px) {
            .setup-grid {
                grid-template-columns: 1fr;
            }
        }

        .setup-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .card-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .card-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-light);
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .btn-capture {
            width: 100%;
            padding: 13px;
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.4);
            border-radius: 12px;
            color: #60a5fa;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 18px;
            transition: all 0.2s ease;
        }

        .btn-capture:hover {
            background: rgba(37, 99, 235, 0.25);
        }

        .btn-gmaps {
            width: 100%;
            padding: 12px;
            background: rgba(234, 67, 53, 0.15);
            border: 1px solid rgba(234, 67, 53, 0.4);
            border-radius: 12px;
            color: #f87171;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 12px;
            transition: all 0.2s ease;
        }

        .btn-gmaps:hover {
            background: rgba(234, 67, 53, 0.25);
            color: #fca5a5;
        }

        .btn-save {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
            margin-top: 20px;
        }

        .btn-save:active {
            transform: scale(0.98);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Map Container Styling */
        #mapContainer {
            width: 100%;
            height: 420px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            background: #000;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.6);
        }

        .map-instructions {
            background: rgba(15, 23, 42, 0.8);
            border: 1px dashed var(--border-color);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <header class="header">
        <button type="button" onclick="goBackToDashboard()" class="back-btn" style="border:none; cursor:pointer;"><i class="fa-solid fa-chevron-left"></i> Back</button>
        <h1><i class="fa-solid fa-location-dot"></i> Campus GPS &amp; Google Map Setup</h1>
        <span style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 4px 10px; border-radius: 20px; font-weight: 600;">Desktop Admin</span>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="setup-grid">
            <!-- Left Column: Form Controls -->
            <div class="setup-card">
                <div class="card-header">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <div>
                        <h2>Campus Geofence Config</h2>
                        <p>Define centroid coordinates and radius for staff punching.</p>
                    </div>
                </div>

                <button type="button" class="btn-capture" onclick="captureCurrentGPS()">
                    <i class="fa-solid fa-crosshairs"></i> Capture My Current Location as Centroid
                </button>

                <form action="/sf-attendance/geofence-setup" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Campus Name</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-building-columns"></i>
                            <input type="text" name="campus_name" class="form-control" value="{{ $geofence->campus_name }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Centroid Latitude (°N)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-arrows-up-down"></i>
                            <input type="number" step="any" id="inputLat" name="centroid_lat" class="form-control" value="{{ $geofence->centroid_lat }}" onchange="updateMapFromInputs()" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Centroid Longitude (°E)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-arrows-left-right"></i>
                            <input type="number" step="any" id="inputLng" name="centroid_lng" class="form-control" value="{{ $geofence->centroid_lng }}" onchange="updateMapFromInputs()" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Allowed Campus Radius (Meters)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-ruler-combined"></i>
                            <input type="number" id="inputRadius" name="radius_meters" class="form-control" value="{{ $geofence->radius_meters }}" min="10" max="5000" oninput="updateCircleRadius()" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Max Device GPS Accuracy Limit (Meters)</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-bullseye"></i>
                            <input type="number" name="max_accuracy_meters" class="form-control" value="{{ $geofence->max_accuracy_meters }}" min="5" max="200" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Save GPS Location Setup
                    </button>
                </form>
            </div>

            <!-- Right Column: Interactive Map & Google Maps Integration -->
            <div class="setup-card" style="display: flex; flex-direction: column;">
                <div class="card-header" style="margin-bottom: 12px;">
                    <i class="fa-solid fa-earth-americas" style="color: #60a5fa;"></i>
                    <div>
                        <h2>Interactive Map Preview &amp; Pinpoint</h2>
                        <p>Drag the red marker or click on map to position campus center.</p>
                    </div>
                </div>

                <div class="map-instructions">
                    <span><i class="fa-solid fa-hand-pointer text-primary me-1"></i> Drag pin or click map to move pin</span>
                    <span id="coordDisplay" style="color: #38bdf8; font-weight: 700;">{{ $geofence->centroid_lat }}, {{ $geofence->centroid_lng }}</span>
                </div>

                <!-- Leaflet Interactive Map View -->
                <div id="mapContainer"></div>

                <!-- Open in Google Maps Link -->
                <a id="btnGmapsLink" href="https://www.google.com/maps?q={{ $geofence->centroid_lat }},{{ $geofence->centroid_lng }}" target="_blank" class="btn-gmaps">
                    <i class="fa-brands fa-google"></i> Open Coordinates in Google Maps <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        let map, marker, circle;
        const initialLat = {{ $geofence->centroid_lat }};
        const initialLng = {{ $geofence->centroid_lng }};
        const initialRadius = {{ $geofence->radius_meters }};

        document.addEventListener("DOMContentLoaded", function() {
            // Initialize Leaflet Map centered at initial Lat/Lng
            map = L.map('mapContainer').setView([initialLat, initialLng], 16);

            // Add OpenStreetMap tile layer (Hybrid Satellite / Standard street tiles)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add Draggable Marker for Campus Centroid
            marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

            // Add Geofence Radius Circle Overlay
            circle = L.circle([initialLat, initialLng], {
                color: '#2563eb',
                fillColor: '#3b82f6',
                fillOpacity: 0.25,
                radius: initialRadius
            }).addTo(map);

            // Marker Drag End Listener
            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateCoordinates(pos.lat, pos.lng);
            });

            // Map Click Listener
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
            });
        });

        // Update Form Inputs & Circle Overlay Position
        function updateCoordinates(lat, lng) {
            const formattedLat = parseFloat(lat).toFixed(8);
            const formattedLng = parseFloat(lng).toFixed(8);

            document.getElementById('inputLat').value = formattedLat;
            document.getElementById('inputLng').value = formattedLng;
            document.getElementById('coordDisplay').innerText = `${formattedLat}, ${formattedLng}`;

            if (circle) circle.setLatLng([lat, lng]);

            // Update Google Maps external link
            const gmapsBtn = document.getElementById('btnGmapsLink');
            if (gmapsBtn) {
                gmapsBtn.href = `https://www.google.com/maps?q=${formattedLat},${formattedLng}`;
            }
        }

        // Update Map from Manual Input Fields
        function updateMapFromInputs() {
            const lat = parseFloat(document.getElementById('inputLat').value);
            const lng = parseFloat(document.getElementById('inputLng').value);

            if (!isNaN(lat) && !isNaN(lng)) {
                const newLatLng = new L.LatLng(lat, lng);
                marker.setLatLng(newLatLng);
                circle.setLatLng(newLatLng);
                map.panTo(newLatLng);
                document.getElementById('coordDisplay').innerText = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                document.getElementById('btnGmapsLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
            }
        }

        // Update Dynamic Circle Radius on Map
        function updateCircleRadius() {
            const rad = parseInt(document.getElementById('inputRadius').value);
            if (circle && !isNaN(rad) && rad > 0) {
                circle.setRadius(rad);
            }
        }

        // Browser Geolocation Auto Capture
        function captureCurrentGPS() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    updateCoordinates(lat, lng);
                    if (map) map.setView([lat, lng], 17);
                    alert("Current GPS coordinates captured!\nLatitude: " + lat + "\nLongitude: " + lng);
                }, (err) => {
                    alert("Unable to fetch current GPS coordinates. Please grant location access.");
                }, { enableHighAccuracy: true });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }

        function goBackToDashboard() {
            const ref = document.referrer;
            if (ref && ref.includes(window.location.host) && !ref.includes('/sf-attendance/')) {
                window.location.href = ref;
                return;
            }

            const userRole = "{{ session('userRole') }}";
            if (userRole === 'Super_Admin' || userRole === 'Principal') {
                window.location.href = '/dashboard/principal';
            } else if (userRole === 'Academic_Coordinator_SF' || userRole === 'ACADEMIC_COORDINATOR_SF') {
                window.location.href = '/dashboard/academic-coordinator-sf';
            } else if (userRole === 'Gen_Dept_Coordinator_Self_Finance' || userRole === 'GEN_DEPT_COORDINATOR_SELF_FINANCE') {
                window.location.href = '/dashboard/general-coordinator-sf';
            } else if (userRole === 'Admin') {
                window.location.href = '/dashboard/admin';
            } else if (window.history.length > 1 && ref && !ref.includes('/sf-attendance/')) {
                window.history.back();
            } else {
                window.location.href = '/dashboard/principal';
            }
        }
    </script>
</body>
</html>
