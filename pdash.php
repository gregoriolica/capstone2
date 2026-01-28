<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'config/config.php';
include_once 'config/roleGate.php';
include_once 'config/url.php';
requireRole(['patient']);

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$user_id = $_SESSION['user_id'] ?? 1; // fallback for testing
$stmt = $pdo->prepare("SELECT * FROM patient_records WHERE user_id = ?");
$stmt->execute([$user_id]);
$patient = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAM Lying-In Clinic Patient Dashboard</title>
    <link rel="stylesheet" href="pdash.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
          <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
            <div class="logo">JAM Lying-In Clinic</div>
            <div class="nav-item active" id="dashboardNav">Dashboard</div>
            <div class="nav-item" id="medrecNav">Medical Record</div>
            <div class="nav-item" id="billingNav">Billings</div>
            <div class="nav-item" id="settingsNav">Settings</div>
            <a href="logout.php" class="sign-out">Sign Out</a>
        </div>

        <!-- main dashboard -->
        <div class="main-content" id="mainContent" style="display: block;"> 
            <div class="header">
                <h1 class="header-title">Patient Dashboard</h1>
            </div>

            <div class="content">
                <div class="patient-info">
                    <div class="patient-header">
                        <div class="photo-container">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAQAAAAAYLlVAAAAnUlEQVR4Ae3WsQ2CMBBF0SeKsgpEoIpEoIpEoIpEoIpEoIpEoIpE4AvbFMoH3WfYt5N87Mzs7n9xBDAwMDAwMDAwMDAwMDAwMDDwE9APwJp5WiVQMnAU4B7gJuYLJdJrJx6G+wuV1V0o7tPu0p4gJQIfAGuAVW7rkmS0VHY+v8x8BygN8A8wDqgJ+VPI8p4C8gHzgUqutIOTYVZx2fXnNv5zJXJsuegAAAABJRU5ErkJggg==" class="patient-photo" alt="Default Photo">
                        </div>
                        <div class="patient-details">
                      <h2>
                        <?= htmlspecialchars($patient['last_name'] ?? '') ?>,
                        <?= htmlspecialchars($patient['first_name'] ?? '') ?>
                        <?= htmlspecialchars($patient['middle_name'] ?? '') ?>
                      </h2>

                      <div class="patient-id"></div>

                      <div class="patient-email">
                        <?= htmlspecialchars($patient['email'] ?? '') ?>
                      </div>
                        </div>

                    </div>

                    <div class="info-section">
                      <button class="btn" id="openUpdateModal">UPDATE</button>
                        <h3>General Information</h3>
                            <div class="info-item">
                              <span class="info-label">Date of birth:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['date_of_birth'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Age:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['age'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Gender:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['gender'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Status:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['status'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Contact Number:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['contact_number'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Occupation:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['occupation'] ?? '') ?></span>
                            </div>
                            <div class="info-item">
                              <span class="info-label">Address:</span>
                              <span class="info-value"><?= htmlspecialchars($patient['address'] ?? '') ?></span>
                            </div>

                        <h3>In Case of Emergency</h3>
                        <div class="info-item">
                          <span class="info-label">Name:</span>
                          <span class="info-value"><?= htmlspecialchars($patient['emergency_name'] ?? '') ?></span>
                        </div>

                        <div class="info-item">
                          <span class="info-label">Contact Number:</span>
                          <span class="info-value"><?= htmlspecialchars($patient['emergency_contact'] ?? '') ?></span>
                        </div>

                        <div class="info-item">
                          <span class="info-label">Address:</span>
                          <span class="info-value"><?= htmlspecialchars($patient['emergency_address'] ?? '') ?></span>
                        </div>

                        <div class="info-item">
                          <span class="info-label">Relationship:</span>
                          <span class="info-value"><?= htmlspecialchars($patient['relationship'] ?? '') ?></span>
                        </div>

                    </div>
                </div>

                <div class="right-side">
                    <div class="appointments-section">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3>📅 Appointments </h3>
                            <button class="btn-primary" id="openScheduleModal">Schedule Appointment</button>
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', loadAppointments);

                        function loadAppointments() {
                          const container = document.querySelector('.appointments-section');
                          const list = document.createElement('div');
                          list.id = 'appointmentList';
                          list.style.display = 'flex';
                          list.style.flexDirection = 'column';
                          list.style.gap = '16px';
                          container.appendChild(list);

                          fetch('http://localhost/JAM_LYINGIN/auth/action/patient/patient_get_appointment_requests.php')
                            .then(res => res.json())
                            .then(data => {
                              list.innerHTML = '';

                              if (data.status === 'success') {
                                data.data.forEach(app => {
                                  const card = document.createElement('div');
                                  card.style.cssText = `
                                    border: 1.5px solid #e2e8f0;
                                    border-radius: 10px;
                                    padding: 16px;
                                    background: #f9fafb;
                                    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
                                  `;

                                  card.innerHTML = `
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                      <div>
                                        <div style="font-weight: 600; color: #4b5563;">
                                          ${formatDate(app.appointment_date)} @ ${app.appointment_time}
                                        </div>
                                        <div style="font-size: 14px; color: #6b7280;">
                                          ${app.chief_complaint ? escapeHtml(app.chief_complaint) : 'No complaint specified'}
                                        </div>
                                      </div>
                                      <div style="font-size: 13px; font-weight: 600; color: ${statusColor(app.status)};">
                                        ${app.status.toUpperCase()}
                                      </div>
                                    </div>
                                  `;
                                  list.appendChild(card);
                                });
                              } else if (data.status === 'empty') {
                                list.innerHTML = `<div style="color: #9ca3af;">No appointment requests found.</div>`;
                              } else {
                                list.innerHTML = `<div style="color: #ef4444;">⚠️ ${escapeHtml(data.message)}</div>`;
                              }
                            })
                            .catch(err => {
                              console.error('❌ Fetch error:', err);
                              list.innerHTML = `<div style="color: #ef4444;">❌ Failed to load appointments.</div>`;
                            });
                        }

                        function formatDate(dateStr) {
                          const d = new Date(dateStr);
                          return d.toLocaleDateString('en-PH', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                          });
                        }

                        function statusColor(status) {
                          switch (status) {
                            case 'approved': return '#10b981';
                            case 'pending': return '#f59e0b';
                            case 'rejected': return '#ef4444';
                            case 'cancelled': return '#6b7280';
                            default: return '#9ca3af';
                          }
                        }

                        function escapeHtml(text) {
                          const div = document.createElement('div');
                          div.textContent = text;
                          return div.innerHTML;
                        }
                        </script>
                    </div>
                      
                    <div class="files-section">
                        <div class="section-header">
                            <h3 class="section-title">Doctors</h3>
                        </div>
                        <div class="doctor-list">
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', loadDoctors);

                        function loadDoctors() {
                          const container = document.querySelector('.doctor-list');
                          container.innerHTML = `<div style="color: #6b7280;">Loading doctors...</div>`;

                          fetch('http://localhost/JAM_LYINGIN/auth/action/patient/patient_get_doctor.php')
                            .then(res => res.json())
                            .then(data => {
                              container.innerHTML = '';

                              if (data.status === 'success') {
                                data.data.forEach(doc => {
                                  const card = document.createElement('div');
                                  card.style.cssText = `
                                    border: 1px solid #e5e7eb;
                                    border-radius: 8px;
                                    padding: 12px 16px;
                                    background: #ffffff;
                                    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
                                  `;

                                  card.innerHTML = `
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                      <div>
                                        <div style="font-weight: 600; font-size: 16px; color: #374151;">
                                          ${escapeHtml(doc.full_name)}
                                        </div>
                                        <div style="font-size: 14px; color: #6b7280;">
                                          ${escapeHtml(doc.email)}<br>
                                          📞 ${escapeHtml(doc.contact)}
                                        </div>
                                      </div>
                                      
                                    </div>
                                  `;
                                  container.appendChild(card);
                                });
                              } else if (data.status === 'empty') {
                                container.innerHTML = `<div style="color: #9ca3af;">No doctors available at the moment.</div>`;
                              } else {
                                container.innerHTML = `<div style="color: #ef4444;">⚠️ ${escapeHtml(data.message)}</div>`;
                              }
                            })
                            .catch(err => {
                              console.error('❌ Fetch error:', err);
                              container.innerHTML = `<div style="color: #ef4444;">❌ Failed to load doctor list.</div>`;
                            });
                        }

                        function escapeHtml(text) {
                          const div = document.createElement('div');
                          div.textContent = text;
                          return div.innerHTML;
                        }
                        </script>
                    
                </div>
              </div>
              <div style="grid-column: 1 / -1;">
                <div class="baby-gallery-card">
                  <h3 class="baby-gallery-title">Baby Profiles</h3>
                  <div class="baby-gallery-actions">
                    <button type="button" class="add-new-button" onclick="openCreateBabyModal()">+ Add New</button>
                  </div>
                  <div class="baby-mosaic-grid">                  
                  </div>
                </div>
                
                <!-- Create Baby Modal -->
                <div id="createBabyModal" class="floating-modal" style="display:none; max-width: 600px; width: 95%; background: #ffffff; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05); border-radius: 24px; padding: 0; overflow: hidden; max-height: 90vh; min-height: 400px; flex-direction: column; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1001;">
                  <div style="background: linear-gradient(135deg, #fb89b8 0%, #764ba2 100%); padding: 32px 40px 24px 40px; position: relative; overflow: hidden; flex-shrink: 0; border-radius: 24px 24px 0 0; display: flex; align-items: center; gap: 16px;">
                    <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #fff;">
                      <span>👶</span>
                    </div>
                    <div>
                      <h3 style="color: #fff; font-size: 24px; font-weight: 700; margin: 0;">Create Baby Profile</h3>
                      <div style="color: #f3e8ff; font-size: 15px; font-weight: 400;">Add a new baby to the clinic records</div>
                    </div>
                    <button type="button" onclick="closeCreateBabyModal()" style="background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.3); color: #fff; cursor: pointer; padding: 8px; border-radius: 50%; width: 36px; height: 36px; font-size: 18px; font-weight: 600; margin-left: auto;">&times;</button>
                  </div>
                  <form id="createBabyForm" style="padding: 32px; background: #fff; flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 24px;">
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                      <label style="color: #7c3a8a; font-weight: 600; font-size: 15px;">Baby Name</label>
                      <input type="text" id="babyNameInput" placeholder="Enter baby name" style="width:100%;padding:16px 20px;border-radius:12px;border:2px solid #e2e8f0;font-size:15px;background:#fff;transition:all 0.3s ease;color:#1f2937;font-family:inherit;box-sizing:border-box;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                      <label style="color: #7c3a8a; font-weight: 600; font-size: 15px;">Birth Date</label>
                      <input type="date" id="babyBirthDate" style="width:100%;padding:16px 20px;border-radius:12px;border:2px solid #e2e8f0;font-size:15px;background:#fff;transition:all 0.3s ease;color:#1f2937;font-family:inherit;box-sizing:border-box;box-shadow:0 1px 3px rgba(0,0,0,0.08);">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                      <label style="color: #7c3a8a; font-weight: 600; font-size: 15px;">Photo</label>
                      <input type="file" id="babyPhotoInput" accept="image/*" style="width:100%;padding:8px 0;">
                    </div>
                    <div class="modal-actions" style="display: flex; justify-content: flex-end; gap: 16px; padding-top: 24px; border-top: 1px solid #e2e8f0; flex-shrink: 0;">
                      <button type="button" onclick="closeCreateBabyModal()" class="btn-secondary" style="padding: 14px 28px; border-radius: 12px; font-size: 15px; font-weight: 600; background: #f8fafc; color: #475569; border: 2px solid #e2e8f0; cursor: pointer;">Cancel</button>
                      <button type="submit" class="btn-primary" style="padding: 14px 32px; border-radius: 12px; font-size: 16px; font-weight: 700; background: linear-gradient(135deg, #fb89b8 0%, #764ba2 100%); color: white; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(102,126,234,0.4);">Save</button>
                    </div>
                  </form>
                </div>

                <script>
                  function openCreateBabyModal() {
                    var modal = document.getElementById('createBabyModal');
                    if (modal) modal.style.display = 'block';
                    var form = document.getElementById('createBabyForm');
                    if (form) {
                      var name = document.getElementById('babyNameInput');
                      var date = document.getElementById('babyBirthDate');
                      var photo = document.getElementById('babyPhotoInput');
                      if (name) name.value = '';
                      if (date) date.value = '';
                      if (photo) photo.value = '';
                    }
                  }
                  function closeCreateBabyModal() {
                    var modal = document.getElementById('createBabyModal');
                    if (modal) modal.style.display = 'none';
                  }
                  document.addEventListener('DOMContentLoaded', function() {
                    var placeholder = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="220" height="220"><rect width="100%" height="100%" fill="%23f8f9fe"/><circle cx="110" cy="90" r="36" fill="%23fde6ef"/><rect x="70" y="140" width="80" height="28" rx="14" fill="%23e5e7eb"/></svg>';
                    var dateInput = document.getElementById('babyBirthDate');
                    if (dateInput) { dateInput.removeAttribute('min'); }
                    var downscaleDataUrl = function(dataUrl, maxDim, quality) {
                      return new Promise(function(resolve) {
                        var img = new Image();
                        img.onload = function() {
                          var w = img.width, h = img.height;
                          var scale = Math.min(1, maxDim / Math.max(w, h));
                          var cw = Math.max(1, Math.round(w * scale));
                          var ch = Math.max(1, Math.round(h * scale));
                          var canvas = document.createElement('canvas');
                          canvas.width = cw; canvas.height = ch;
                          var ctx = canvas.getContext('2d');
                          ctx.drawImage(img, 0, 0, cw, ch);
                          var out;
                          try { out = canvas.toDataURL('image/jpeg', quality); } catch(e) { out = dataUrl; }
                          resolve(out);
                        };
                        img.onerror = function() { resolve(dataUrl); };
                        img.src = dataUrl;
                      });
                    };
                    // Initialize gallery from localStorage
                    try {
                      var stored = JSON.parse(localStorage.getItem('babyGallery') || '[]');
                      var grid = document.querySelector('.baby-mosaic-grid');
                      if (grid && Array.isArray(stored)) {
                        stored.forEach(function(item) {
                          var link = document.createElement('a');
                          link.href = 'babyprofile.php';
                          link.className = 'baby-card-link';
                          var tile = document.createElement('div');
                          tile.className = 'mosaic-item square';
                          tile.dataset.name = (item && item.name) ? item.name : '';
                          var img = document.createElement('img');
                          img.className = 'mosaic-image';
                          img.src = item.src || placeholder;
                          tile.appendChild(img);
                          link.appendChild(tile);
                          grid.prepend(link);
                        });
                      }
                    } catch(e) {}
                    // Handle create form submit
                    var form = document.getElementById('createBabyForm');
                    if (form) {
                      form.addEventListener('submit', function(ev){
                        ev.preventDefault();
                        var fileInput = document.getElementById('babyPhotoInput');
                        var nameInput = document.getElementById('babyNameInput');
                        var file = fileInput && fileInput.files && fileInput.files[0];
                        if (file) {
                          var fr = new FileReader();
                          fr.onload = function(e) {
                            var src = e.target.result;
                            var trimmed = (nameInput ? nameInput.value.trim() : '');
                            downscaleDataUrl(src, 800, 0.7).then(function(outSrc) {
                              var gallery = [];
                              try { gallery = JSON.parse(localStorage.getItem('babyGallery') || '[]'); } catch (e) { gallery = []; }
                              gallery.unshift({ src: outSrc, name: trimmed });
                              try { localStorage.setItem('babyGallery', JSON.stringify(gallery)); }
                              catch (err) {
                                gallery[0].src = placeholder;
                                try { localStorage.setItem('babyGallery', JSON.stringify(gallery)); } catch(e2) {}
                              }
                              var profile = {};
                              try { profile = JSON.parse(localStorage.getItem('profile') || '{}'); } catch (e) { profile = {}; }
                              profile.child_name = trimmed;
                              localStorage.setItem('profile', JSON.stringify(profile));
                              var grid = document.querySelector('.baby-mosaic-grid');
                              if (grid) {
                                var link = document.createElement('a');
                                link.href = 'babyprofile.php';
                                link.className = 'baby-card-link';
                                var tile = document.createElement('div');
                                tile.className = 'mosaic-item square';
                                tile.dataset.name = trimmed;
                                var img = document.createElement('img');
                                img.className = 'mosaic-image';
                                img.src = outSrc || placeholder;
                                tile.appendChild(img);
                                link.appendChild(tile);
                                grid.prepend(link);
                              }
                              closeCreateBabyModal();
                              window.location.href = 'babyprofile.php';
                            });
                          };
                          fr.readAsDataURL(file);
                          fr.onerror = function() {
                            var trimmed = (nameInput ? nameInput.value.trim() : '');
                            var gallery = [];
                            try { gallery = JSON.parse(localStorage.getItem('babyGallery') || '[]'); } catch (e) { gallery = []; }
                            gallery.unshift({ src: placeholder, name: trimmed });
                            localStorage.setItem('babyGallery', JSON.stringify(gallery));
                            var profile = {};
                            try { profile = JSON.parse(localStorage.getItem('profile') || '{}'); } catch (e) { profile = {}; }
                            profile.child_name = trimmed;
                            localStorage.setItem('profile', JSON.stringify(profile));
                            var grid = document.querySelector('.baby-mosaic-grid');
                            if (grid) {
                              var link = document.createElement('a');
                              link.href = 'babyprofile.php';
                              link.className = 'baby-card-link';
                              var tile = document.createElement('div');
                              tile.className = 'mosaic-item square';
                              tile.dataset.name = trimmed;
                              var img = document.createElement('img');
                              img.className = 'mosaic-image';
                              img.src = placeholder;
                              tile.appendChild(img);
                              link.appendChild(tile);
                              grid.prepend(link);
                            }
                            closeCreateBabyModal();
                            window.location.href = 'babyprofile.php';
                          };
                        } else {
                          var nameInput = document.getElementById('babyNameInput');
                          var trimmed = (nameInput ? nameInput.value.trim() : '');
                          var gallery = [];
                          try { gallery = JSON.parse(localStorage.getItem('babyGallery') || '[]'); } catch (e) { gallery = []; }
                          gallery.unshift({ src: placeholder, name: trimmed });
                          localStorage.setItem('babyGallery', JSON.stringify(gallery));
                          var profile = {};
                          try { profile = JSON.parse(localStorage.getItem('profile') || '{}'); } catch (e) { profile = {}; }
                          profile.child_name = trimmed;
                          localStorage.setItem('profile', JSON.stringify(profile));
                          var grid = document.querySelector('.baby-mosaic-grid');
                          if (grid) {
                            var link = document.createElement('a');
                            link.href = 'babyprofile.php';
                            link.className = 'baby-card-link';
                            var tile = document.createElement('div');
                            tile.className = 'mosaic-item square';
                            tile.dataset.name = trimmed;
                            var img = document.createElement('img');
                            img.className = 'mosaic-image';
                            img.src = placeholder;
                            tile.appendChild(img);
                            link.appendChild(tile);
                            grid.prepend(link);
                          }
                          closeCreateBabyModal();
                          window.location.href = 'babyprofile.php';
                        }
                      });
                    }
                  });
                </script>
              </div>
            </div>
        </div>

        <!-- Medical Record Section (hidden by default) -->
        <div id="medrecDashboard" style="display:none; flex: 1; background: #f9fafc; overflow-y: auto;">
          <div style="padding: 32px; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
              <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 0;">
                <h1 style="font-size: 36px; color: #232b3b; font-weight: 700; margin: 0 0 8px 0;">Medical Record</h1>
              </div>
              <div style="color: #7c8ba1; font-size: 15px; display: flex; align-items: center; gap: 8px; margin-top: 8px;">
                <span id="medrecCurrentDate" style="font-size: 18px;"></span>
              </div>
            </div>
            <div style="display: block; width: 100%;">
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; align-items: stretch;">
                <!-- Age of Gestation Card -->
                <div class="aog-card" style="background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(44,62,80,0.08); padding: 28px 32px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                  <svg width="90" height="90" style="margin-bottom: 10px;">
                    <circle cx="45" cy="45" r="40" stroke="#e2e8f0" stroke-width="8" fill="none"/>
                    <circle cx="45" cy="45" r="40" stroke="#6ee7b7" stroke-width="8" fill="none" stroke-dasharray="251" stroke-dashoffset="251" stroke-linecap="round"/>
                  </svg>
                  <div style="font-size: 26px; font-weight: 700; color: #232b3b; margin-bottom: 2px;"></div>
                  <div style="font-size: 15px; color: #7c8ba1; font-weight: 500;">Age of Gestation</div>
                </div>
                
                <!-- Analytics Table -->
                <div style="background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(44,62,80,0.08); padding: 24px 32px; display: flex; flex-direction: column; justify-content: flex-start; min-width:0;">
                  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div style="font-size: 18px; color: #232b3b; font-weight: 700;">Visit Analytics</div>
                    <form id="analyticsSearchForm" style="display: flex; align-items: center; gap: 8px;">
                      <input type="date" id="analyticsDate" name="analyticsDate" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 15px;">
                      <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 14px; border-radius: 6px;">Search</button>
                    </form>
                  </div>
                  <div style="overflow-x:auto;">
                  <table style="width:100%; border-collapse:collapse; font-size:15px;">
                    <thead>
                      <tr style="background:#f8f9fe; color:#7c3aed;">
                        <th style="padding:8px 10px; text-align:left;">Visit Date</th>
                        <th style="padding:8px 10px; text-align:left;">BP</th>
                        <th style="padding:8px 10px; text-align:left;">Temp</th>
                        <th style="padding:8px 10px; text-align:left;">Weight</th>
                        <th style="padding:8px 10px; text-align:left;">Fundal Height</th>
                        <th style="padding:8px 10px; text-align:left;">Fetal Heart Tone</th>
                        <th style="padding:8px 10px; text-align:left;">Fetal Position</th>
                        <th style="padding:8px 10px; text-align:left;">Chief Complaint</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                  </div>
                </div>
                </div>
              
                <div style="display: block; width: 100%;">
                <div id="medrecTabs" style="display: flex; gap: 32px; align-items: center; border-bottom: 2px solid #e0e7ff; margin-bottom: 18px; width: 100%;">
                    <button type="button" id="physicalExamTab" class="medrec-tab active" style="background: none; border: none; font-size: 16px; font-weight: 600; color: #7c3aed; padding-bottom: 8px; border-bottom: 2px solid #7c3aed; cursor: pointer; outline: none;">
                      Physical Examination
                    </button>
                    <button type="button" id="medicalHistoryTab" class="medrec-tab" style="background: none; border: none; font-size: 16px; font-weight: 500; color: #a0aec0; padding-bottom: 8px; border-bottom: 2px solid transparent; cursor: pointer; outline: none;">
                      Medical History
                    </button>
                    <button type="button" id="obstetricalHistoryTab" class="medrec-tab" style="background: none; border: none; font-size: 16px; font-weight: 500; color: #a0aec0; padding-bottom: 8px; border-bottom: 2px solid transparent; cursor: pointer; outline: none;">
                      Obstetrical History
                    </button>
                    <button type="button" id="vawRiskTab" class="medrec-tab" style="background: none; border: none; font-size: 16px; font-weight: 500; color: #a0aec0; padding-bottom: 8px; border-bottom: 2px solid transparent; cursor: pointer; outline: none;">
                      VAW Risk
                    </button>
                </div>
                
                <div id="physicalExamContent" class="medrec-content">
                  <form id="physicalExamSearchForm" style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <input type="date" id="physicalExamDate" name="physicalExamDate" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 15px;">
                    <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 14px; border-radius: 6px;">Search</button>
                  </form>
                  <div id="physicalExamResults" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                    No physical examination records yet.
                  </div>
                </div>

                <div id="medicalHistoryContent" style="display:none;" class="medrec-content">
                  <form id="medicalHistorySearchForm" style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <input type="date" id="medicalHistoryDate" name="medicalHistoryDate" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 15px;">
                    <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 14px; border-radius: 6px;">Search</button>
                  </form>
                  <div id="medicalHistoryResults" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">No medical history records yet.</div>
                </div>

                <div id="obstetricalHistoryContent" style="display:none;" class="medrec-content">
                  <form id="obstetricalHistorySearchForm" style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <input type="date" id="obstetricalHistoryDate" name="obstetricalHistoryDate" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 15px;">
                    <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 14px; border-radius: 6px;">Search</button>
                  </form>
                  <div id="obstetricalHistoryResults" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                    No obstetrical history records yet.
                  </div>
                </div>

                <div id="vawRiskContent" class="medrec-content" style="display: none;">
                  <form id="vawRiskSearchForm" style="display: flex; align-items: center; gap: 8px; margin-bottom: 18px;">
                    <input type="date" id="vawRiskDate" name="vawRiskDate" style="padding: 6px 10px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 15px;">
                    <button type="submit" class="btn-primary" style="padding: 7px 16px; font-size: 14px; border-radius: 6px;">Search</button>
                  </form>
                  <div id="vawRiskResults" style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                    No VAW risk assessments yet.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Billing Dashboard Section (hidden by default) -->
        <div id="billingDashboard" style="display:none; flex: 1; background: #f9fafc; overflow-y: auto;">
          <div style="padding: 32px; max-width: 1200px; margin: 0 auto;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
              <h1 style="font-size: 36px; color: #232b3b; font-weight: 700; margin: 0;">Billing Dashboard</h1>
            </div>
            <div style="display: flex; gap: 32px; margin-bottom: 32px;">
              <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(44,62,80,0.08); padding: 28px 36px; flex:1; display: flex; flex-direction: column; align-items: flex-start;">
                <div style="font-size: 15px; color: #7c8ba1; font-weight: 500;">Outstanding Balance</div>
                <div id="billingBalance" style="font-size: 28px; font-weight: 700; color: #e53e3e; margin-top: 6px;"></div>
              </div>
              <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(44,62,80,0.08); padding: 28px 36px; flex:1; display: flex; flex-direction: column; align-items: flex-start;">
                <div style="font-size: 15px; color: #7c8ba1; font-weight: 500;">Last Payment</div>
                <div id="lastPaymentAmount" style="font-size: 22px; font-weight: 700; color: #232b3b; margin-top: 6px;"></div>
                <div id="lastPaymentDate" style="font-size: 13px; color: #a0aec0; margin-top: 2px;"></div>
              </div>
              <div style="background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(44,62,80,0.08); padding: 28px 36px; flex:1; display: flex; flex-direction: column; align-items: flex-start;">
                <div style="font-size: 15px; color: #7c8ba1; font-weight: 500;">Next Due Date</div>
                <div style="font-size: 22px; font-weight: 700; color: #232b3b; margin-top: 6px;"></div>
              </div>
            </div>
            <div style="background: #fff; border-radius: 18px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 28px 32px; margin-bottom: 32px;">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                <div style="font-size: 20px; color: #232b3b; font-weight: 700;">Recent Transactions</div>
                <button class="btn-primary" style="padding: 10px 28px; font-size: 15px; border-radius: 8px;">Pay Now</button>
              </div>
              <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:15px;">
                  <thead>
                    <tr style="background:#f8f9fe; color:#7c3aed;">
                      <th style="padding:8px 10px; text-align:left;">Date</th>
                      <th style="padding:8px 10px; text-align:left;">Description</th>
                      <th style="padding:8px 10px; text-align:left;">Amount</th>
                      <th style="padding:8px 10px; text-align:left;">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Settings Section (hidden by default) -->
        <div id="settingsSection" style="display:none; flex: 1; background: #f8f9fe; overflow-y: auto;">
          <div style="padding: 32px; max-width: 1200px; margin: 0 auto;">
            <div class="settings-card">
              <div class="settings-card-header">
                <div class="settings-card-icon">
                  <span>⚙️</span>
                </div>
                <div>
                  <h2 class="settings-card-title">Settings</h2>
                  <p class="settings-card-subtitle">Manage your account preferences and security settings</p>
                </div>
              </div>

              <div class="settings-form">
                <div class="settings-form-group">
                  <h3 style="color: #2d3748; font-size: 18px; font-weight: 600; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">Security Settings</h3>
                  
                  <div style="margin-bottom: 20px;">
                    <label class="settings-label">Current Password *</label>
                    <input type="password" id="currentPassword" class="settings-input" placeholder="Enter your current password">
                  </div>
                  
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                      <label class="settings-label">New Password *</label>
                      <input type="password" id="newPassword" class="settings-input" placeholder="Enter new password">
                    </div>
                    <div>
                      <label class="settings-label">Confirm New Password *</label>
                      <input type="password" id="confirmPassword" class="settings-input" placeholder="Confirm new password">
                    </div>
                  </div>
                  
                  <button type="button" class="settings-button" onclick="changePassword()">Change Password</button>
                </div>
              </div>
              </div>
          </div>
        </div>
    </div>

    <!-- Modal for scheduling appointment -->
    <div id="scheduleModal" class="modal-overlay" style="display:none;">
        <div class="modal-content" style="max-width: 500px; background: linear-gradient(135deg, #f8f9fe 60%, #e9d8fd 100%); box-shadow: 0 8px 32px rgba(108, 51, 110, 0.18); border-radius: 24px; padding: 0; overflow-y: auto; max-height: 90vh;">
          <div style="background: linear-gradient(90deg, #7c3aed 0%, #ff6ba2 100%); padding: 28px 36px 18px 36px; border-radius: 24px 24px 0 0;">
            <h2 style="color: #fff; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: 1px;">Schedule Appointment</h2>
          </div>
          <form id="scheduleForm" style="padding: 32px 36px 24px 36px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px 32px; margin-bottom: 28px;">
              <div style="display: flex; flex-direction: column; gap: 8px; grid-column: 1 / -1;">
              <label for="doctorSelect" style="color: #7c3aed; font-weight: 500;">Doctor</label>
              <select id="doctorSelect" name="doctor" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
                <option value="">Select Doctor</option>
                <?php
                include_once '../config/config.php';

                try {
                    $stmt = $pdo->prepare("
                        SELECT u.id, u.first_name, u.last_name
                        FROM users u
                        WHERE u.role = 'staff'
                          AND EXISTS (
                              SELECT 1 FROM doctor d WHERE d.user_id = u.id
                          )
                        ORDER BY u.first_name ASC, u.last_name ASC
                    ");
                    $stmt->execute();
                    $doctorList = $stmt->fetchAll();

                    foreach ($doctorList as $staff) {
                        $fullName = 'Dr. ' . htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']);
                        echo "<option value=\"{$staff['id']}\">$fullName</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option disabled>Error loading doctors</option>";
                }
                ?>
              </select>
                <div id="doctorScheduleInfo" style="margin-top: 6px; font-size: 15px; color: #7c3aed; font-weight: 500; min-height: 20px;"></div>
              </div>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                <label for="appointmentDate" style="color: #7c3aed; font-weight: 500;">Date</label>
                <input type="date" id="appointmentDate" name="date" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
              </div>
              <div style="display: flex; flex-direction: column; gap: 8px;">
                <label for="timeSlot" style="color: #7c3aed; font-weight: 500;">Time Slot</label>
                <input type="time" id="timeSlot" name="time" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
              </div>
              <div style="display: flex; flex-direction: column; gap: 8px; grid-column: 1 / -1;">
                <label for="chiefComplaint" style="color: #7c3aed; font-weight: 500;">Chief Complaint</label>
                <input type="text" id="chiefComplaint" name="complaint" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
              </div>
            </div>
            <div style="display:flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
              <button type="button" class="btn-secondary" id="closeScheduleModal" style="padding: 10px 22px; border-radius: 8px; font-size: 15px;">Cancel</button>
              <button type="submit" class="btn-primary" style="padding: 10px 28px; border-radius: 8px; font-size: 16px; font-weight: 600; background: linear-gradient(90deg, #ff6ba2 0%, #667eea 100%);">Submit</button>
            </div>
          </form>
        </div>
    </div>

    <!-- Update Patient Modal -->
    <div id="updateModal" class="modal-overlay" style="display:none;">
      <div class="modal-content" style="max-width: 650px; background: linear-gradient(135deg, #f8f9fe 60%, #e9d8fd 100%); box-shadow: 0 8px 32px rgba(108, 51, 110, 0.18); border-radius: 24px; padding: 0; overflow-y: auto; max-height: 90vh;">
        <div style="background: linear-gradient(90deg, #7c3aed 0%, #ff6ba2 100%); padding: 28px 36px 18px 36px; border-radius: 24px 24px 0 0;">
          <h2 style="color: #fff; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: 1px;">Update Patient Information</h2>
        </div>
        <form id="updateForm" method="POST" action="auth/handle_update_patient.php" enctype="multipart/form-data" style="padding: 32px 36px 24px 36px;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px 32px; margin-bottom: 28px;">
            <div style="grid-column: 1 / -1; font-size: 18px; color: #7c3aed; font-weight: 600; margin-bottom: 8px;">Patient</div>
            <div style="display: flex; justify-content: center; align-items: center; margin-top: -40px; margin-bottom: 24px;">
              <div style="position: relative; width: 90px; height: 90px;">
                <img id="updatePatientPhoto" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48Y2lyY2xlIGN4PSI0MCIgY3k9IjQwIiByPSI0MCIgZmlsbD0iI0Y3RkFGQyIvPjxjaXJjbGUgY3g9IjQwIiBjeT0iMzAiIHI9IjEyIiBmaWxsPSIjNEE1NTY4Ii8+PHBhdGggZD0iTTIwIDYwYzAtMTEgOS0yMCAyMC0yMHMyMCA5IDIwIDIwdjEwSDIwVjYweiIgZmlsbD0iIzRBNTU2OCIvPjwvc3ZnPg==" alt="Patient Photo" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; background: #f7fafc;">
                <label for="updatePhotoInput" style="position: absolute; bottom: 0; right: 0; background: linear-gradient(90deg, #ff6ba2 0%, #667eea 100%); color: #fff; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 8px rgba(108, 51, 110, 0.12); font-size: 18px; border: 2px solid #fff;">
                  <span style="pointer-events: none;">&#128247;</span>
                  <input id="updatePhotoInput" name="patientImage" type="file" accept="image/*" style="display: none;">
                </label>
              </div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">First Name</label>
              <input type="text" name="firstName" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Middle Name</label>
              <input type="text" name="middleName" style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Last Name</label>
              <input type="text" name="lastName" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Date of Birth</label>
              <input type="date" name="dob" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Age</label>
              <input type="number" name="age" min="0" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Gender</label>
              <select name="gender" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
                <option value="">Select Gender</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
              </select>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Status</label>
              <select name="status" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
                <option value="">Select Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
              </select>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Contact Number</label>
              <input type="text" name="contact" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Occupation</label>
              <input type="text" name="occupation" style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px; grid-column: 1 / -1;">
              <label style="color: #7c3aed; font-weight: 500;">Address</label>
              <input type="text" name="address" style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="grid-column: 1 / -1; font-size: 18px; color: #7c3aed; font-weight: 600; margin: 18px 0 8px 0;">Emergency Contact</div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Name</label>
              <input type="text" name="emergencyName" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Contact Number</label>
              <input type="text" name="emergencyContact" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Address</label>
              <input type="text" name="emergencyAddress" style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff; transition: border 0.2s;">
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
              <label style="color: #7c3aed; font-weight: 500;">Relationship</label>
              <select name="relationship" required style="padding: 10px 14px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 16px; background: #fff;">
                <option value="">Select Relationship</option>
                <option value="Parent">Parent</option>
                <option value="Sibling">Sibling</option>
                <option value="Spouse">Spouse</option>
                <option value="Friend">Friend</option>
                <option value="Other">Other</option>
              </select>
            </div>
          </div>
          <div style="display:flex; justify-content: flex-end; gap: 10px; margin-top: 18px;">
            <button type="button" class="btn-secondary" id="closeUpdateModal" style="padding: 10px 22px; border-radius: 8px; font-size: 15px;">Cancel</button>
            <button type="submit" class="btn-primary" style="padding: 10px 28px; border-radius: 8px; font-size: 16px; font-weight: 600; background: linear-gradient(90deg, #ff6ba2 0%, #667eea 100%);">Save</button>
          </div>
        </form>
      </div>
    </div>

    <script>
        // Modal controls
        document.getElementById('openScheduleModal').onclick = function() {
            document.getElementById('scheduleModal').style.display = 'flex';
        };
        document.getElementById('closeScheduleModal').onclick = function() {
            document.getElementById('scheduleModal').style.display = 'none';
        };
        
        document.getElementById('openUpdateModal').onclick = function() {
            document.getElementById('updateModal').style.display = 'flex';
        };
        document.getElementById('closeUpdateModal').onclick = function() {
            document.getElementById('updateModal').style.display = 'none';
        };

        // Schedule form submission
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          
          fetch('auth/action/submit_appointment_request.php', {
            method: 'POST',
            body: formData
          })
          .then(res => {
            const contentType = res.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
              throw new Error("Invalid response format");
            }
            return res.json();
          })
          .then(data => {
            alert(data.message);
            if (data.success) {
              document.getElementById('scheduleForm').reset();
              document.getElementById('scheduleModal').style.display = 'none';
              loadAppointments();
            }
          })
          .catch(err => {
            alert('Submission failed.');
            console.error(err);
          });
        });

        // Update form submission
        document.getElementById('updateForm').onsubmit = function(e) {
            alert('Patient information updated!');
            document.getElementById('updateModal').style.display = 'none';
        };

        // Update Patient Photo upload
        document.getElementById('updatePhotoInput').addEventListener('change', function(e) {
          if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
              document.getElementById('updatePatientPhoto').src = ev.target.result;
            };
            reader.readAsDataURL(this.files[0]);
          }
        });

        // Navigation logic
        const dashboardNav = document.getElementById('dashboardNav');
        const medrecNav = document.getElementById('medrecNav');
        const billingNav = document.getElementById('billingNav');
        const settingsNav = document.getElementById('settingsNav');
        
        const mainContent = document.getElementById('mainContent');
        const medrecDashboard = document.getElementById('medrecDashboard');
        const billingDashboard = document.getElementById('billingDashboard');
        const settingsSection = document.getElementById('settingsSection');

        dashboardNav.onclick = function() {
            mainContent.style.display = 'block';
            medrecDashboard.style.display = 'none';
            billingDashboard.style.display = 'none';
            settingsSection.style.display = 'none';
            
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        };

        medrecNav.onclick = function() {
            mainContent.style.display = 'none';
            medrecDashboard.style.display = 'block';
            billingDashboard.style.display = 'none';
            settingsSection.style.display = 'none';
            
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        };

        billingNav.onclick = function() {
            loadBillingDashboard();
            mainContent.style.display = 'none';
            medrecDashboard.style.display = 'none';
            billingDashboard.style.display = 'block';
            settingsSection.style.display = 'none';
            
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        };

        settingsNav.onclick = function() {
            mainContent.style.display = 'none';
            medrecDashboard.style.display = 'none';
            billingDashboard.style.display = 'none';
            settingsSection.style.display = 'block';
            
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        };

        // Change Password function
        function changePassword() {
          const currentPassword = document.getElementById('currentPassword').value.trim();
          const newPassword = document.getElementById('newPassword').value.trim();
          const confirmPassword = document.getElementById('confirmPassword').value.trim();

          if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Please fill out all fields.');
            return;
          }

          if (newPassword !== confirmPassword) {
            alert('New password and confirmation do not match.');
            return;
          }

          if (newPassword.length < 6) {
            alert('New password must be at least 6 characters long.');
            return;
          }

          fetch('auth/action/patient/patient_change_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
              current_password: currentPassword,
              new_password: newPassword
            })
          })
          .then(res => res.json())
          .then(data => {
            if (data.status === 'success') {
              alert('Password changed successfully.');
              document.getElementById('currentPassword').value = '';
              document.getElementById('newPassword').value = '';
              document.getElementById('confirmPassword').value = '';
            } else {
              alert(data.message || 'Password change failed.');
            }
          })
          .catch(err => {
            console.error('Password change error:', err);
            alert('An error occurred while changing password.');
          });
        }

        // Load billing dashboard
        function loadBillingDashboard() {
          const patientId = <?= json_encode($_SESSION['patient_id'] ?? null) ?>;
          if (!patientId) {
            console.error('Patient ID not found');
            return;
          }
          
          fetch(`http://localhost/JAM_Lyingin/auth/action/patient/patient_get_billing_summary.php?patient_id=${patientId}`)
            .then(res => res.json())
            .then(data => {
              if (data.status !== 'success') {
                alert('Failed to load billing data');
                return;
              }

              document.getElementById('billingBalance').textContent =
                `₱${parseFloat(data.balance || 0).toFixed(2)}`;

              document.getElementById('lastPaymentAmount').textContent =
                `₱${parseFloat(data.last_payment?.amount || 0).toFixed(2)}`;

              document.getElementById('lastPaymentDate').textContent =
                data.last_payment?.created_at
                  ? new Date(data.last_payment.created_at).toLocaleDateString()
                  : '—';

              const tbody = billingDashboard.querySelector('table tbody');
              tbody.innerHTML = '';

              if (data.transactions && data.transactions.length > 0) {
                data.transactions.forEach(tx => {
                  const row = document.createElement('tr');
                  row.innerHTML = `
                    <td style="padding:8px 10px;">${new Date(tx.date).toLocaleDateString()}</td>
                    <td style="padding:8px 10px;">${tx.description}</td>
                    <td style="padding:8px 10px;">₱${parseFloat(tx.amount).toFixed(2)}</td>
                    <td style="padding:8px 10px;">${tx.transaction_type}</td>
                  `;
                  tbody.appendChild(row);
                });
              } else {
                tbody.innerHTML = '<tr><td colspan="4" style="padding:20px; text-align:center; color:#9ca3af;">No transactions found</td></tr>';
              }
            })
            .catch(err => {
              console.error('Billing fetch error:', err);
              alert('Unable to load billing dashboard.');
            });
        }

        // Load AOG
        document.addEventListener('DOMContentLoaded', loadAOG);

        function loadAOG() {
          const card = document.querySelector('.aog-card');
          if (!card) return;
          
          const svgCircle = card.querySelector('svg circle:nth-child(2)');
          const valueDisplay = card.querySelector('div:nth-of-type(2)');

          fetch('http://localhost/JAM_LYINGIN/auth/action/patient/patient_get_pregnancy_tracker.php')
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                const aog = data.data.aog_weeks;
                const weeks = parseInt(aog, 10) || 0;
                const maxWeeks = 40;
                const percent = Math.min(weeks / maxWeeks, 1);
                const dashOffset = 251 - Math.round(251 * percent);

                svgCircle.setAttribute('stroke-dashoffset', dashOffset);
                valueDisplay.textContent = `${weeks} weeks`;
              } else {
                svgCircle.setAttribute('stroke-dashoffset', 251);
                valueDisplay.textContent = '--';
              }
            })
            .catch(err => {
              console.error('❌ AOG fetch error:', err);
              svgCircle.setAttribute('stroke-dashoffset', 251);
              valueDisplay.textContent = '--';
            });
        }

        // Load visit analytics
        document.addEventListener('DOMContentLoaded', () => {
          loadVisitAnalytics();

          const form = document.getElementById('analyticsSearchForm');
          if (form) {
            form.addEventListener('submit', e => {
              e.preventDefault();
              const date = document.getElementById('analyticsDate').value;
              loadVisitAnalytics(date);
            });
          }
        });

        function loadVisitAnalytics(filterDate = null) {
          const tbody = document.querySelector('table tbody');
          if (!tbody) return;
          
          tbody.innerHTML = `<tr><td colspan="8" style="padding:10px; color:#6b7280;">Loading...</td></tr>`;

          fetch('http://localhost/JAM_LYINGIN/auth/action/patient/patient_get_visit_analytics.php')
            .then(res => res.json())
            .then(data => {
              tbody.innerHTML = '';

              if (data.status === 'success') {
                const visits = filterDate
                  ? data.data.filter(v => v.visit_date === filterDate)
                  : data.data;

                if (visits.length === 0) {
                  tbody.innerHTML = `<tr><td colspan="8" style="padding:10px; color:#9ca3af;">No records found for selected date.</td></tr>`;
                  return;
                }

                visits.forEach(v => {
                  const row = document.createElement('tr');
                  row.innerHTML = `
                    <td style="padding:8px 10px;">${formatDate(v.visit_date)}</td>
                    <td style="padding:8px 10px;">${v.blood_pressure || '--'}</td>
                    <td style="padding:8px 10px;">${v.temperature ?? '--'}</td>
                    <td style="padding:8px 10px;">${v.weight ?? '--'}</td>
                    <td style="padding:8px 10px;">${v.fundal_height ?? '--'}</td>
                    <td style="padding:8px 10px;">${v.fetal_heart_tone ?? '--'}</td>
                    <td style="padding:8px 10px;">${v.fetal_position || '--'}</td>
                    <td style="padding:8px 10px;">${escapeHtml(v.chief_complaint || '--')}</td>
                  `;
                  tbody.appendChild(row);
                });
              } else if (data.status === 'empty') {
                tbody.innerHTML = `<tr><td colspan="8" style="padding:10px; color:#9ca3af;">No visit analytics found.</td></tr>`;
              } else {
                tbody.innerHTML = `<tr><td colspan="8" style="padding:10px; color:#ef4444;">⚠️ ${escapeHtml(data.message)}</td></tr>`;
              }
            })
            .catch(err => {
              console.error('❌ Fetch error:', err);
              tbody.innerHTML = `<tr><td colspan="8" style="padding:10px; color:#ef4444;">❌ Failed to load analytics.</td></tr>`;
            });
        }

        function formatDate(dateStr) {
          const d = new Date(dateStr);
          return d.toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        function escapeHtml(text) {
          const div = document.createElement('div');
          div.textContent = text;
          return div.innerHTML;
        }

        // Medical record tab navigation
        document.querySelectorAll('.medrec-tab').forEach(tab => {
          tab.addEventListener('click', () => {
            document.querySelectorAll('.medrec-tab').forEach(t => {
              t.classList.remove('active');
              t.style.color = '#a0aec0';
              t.style.fontWeight = '500';
              t.style.borderBottom = '2px solid transparent';
            });

            tab.classList.add('active');
            tab.style.color = '#7c3aed';
            tab.style.fontWeight = '600';
            tab.style.borderBottom = '2px solid #7c3aed';

            document.querySelectorAll('.medrec-content').forEach(panel => {
              panel.style.display = 'none';
            });

            const tabId = tab.id.replace('Tab', 'Content');
            const activePanel = document.getElementById(tabId);
            if (activePanel) activePanel.style.display = '';
          });
        });

// Set current date in Medical Record section
function formatDateMedrec(date) {
          const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
          const d = date.getDate().toString().padStart(2, '0');
          const m = (date.getMonth() + 1).toString().padStart(2, '0');
          const y = date.getFullYear();
          return `${days[date.getDay()]}, ${d}.${m}.${y}`;
        }
        
        const medrecDateSpan = document.getElementById('medrecCurrentDate');
        if (medrecDateSpan) {
          medrecDateSpan.textContent = formatDateMedrec(new Date());
        }

        // Fetch medical record data functions
        function fetchPhysicalExamRecords() {
          const resultsBox = document.getElementById('physicalExamResults');
          resultsBox.innerHTML = `<div style="padding: 24px; text-align: center;">Loading...</div>`;

          const url = "<?= $getPhysicalExamURL ?>";
          
          fetch(url, {
            method: "GET",
            credentials: "same-origin"
          })
          .then(response => {
            if (!response.ok) throw new Error("HTTP " + response.status);
            return response.json();
          })
          .then(data => {
            if (data.status === "success" && Array.isArray(data.data) && data.data.length > 0) {
              const html = data.data.map(record => `
                <div style="margin-bottom: 12px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: left;">
                  <strong>Date:</strong> ${record.exam_date || '—'}<br>
                  <strong>Findings:</strong> ${record.findings || 'N/A'}<br>
                  <strong>Doctor:</strong> ${record.examined_by || `Staff ID ${record.staff_id}`}<br>
                  <strong>Doctor's Note:</strong> ${record.doctor_note || '—'}
                </div>
              `).join("");
              resultsBox.innerHTML = html;
            } else {
              resultsBox.innerHTML = `
                <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                  ${data.message || 'No physical examination records found.'}
                </div>
              `;
            }
          })
          .catch(error => {
            resultsBox.innerHTML = `<div style="color: red; font-weight: 600; padding: 24px; text-align: center;">⚠️ ${error.message}</div>`;
            console.error('[Error] Fetch failed:', error);
          });
        }

        function fetchMedicalHistoryRecords() {
          const resultsBox = document.getElementById('medicalHistoryResults');
          resultsBox.innerHTML = `<div style="padding: 24px; text-align: center;">Loading...</div>`;

          const url = "<?= $getMedicalHistoryURL ?>";
          
          fetch(url, {
            method: "GET",
            credentials: "same-origin"
          })
          .then(response => {
            if (!response.ok) throw new Error("HTTP " + response.status);
            return response.json();
          })
          .then(data => {
            if (data.status === "success" && Array.isArray(data.data) && data.data.length > 0) {
              const html = data.data.map(record => `
                <div style="margin-bottom: 12px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: left;">
                  <strong>Date:</strong> ${record.visit_date || '—'}<br>
                  <strong>Doctor:</strong> ${record.examined_by || `Staff ID ${record.staff_id}`}<br>
                  <strong>Doctor's Note:</strong> ${record.doctor_note || '—'}
                </div>
              `).join("");
              resultsBox.innerHTML = html;
            } else {
              resultsBox.innerHTML = `
                <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                  ${data.message || 'No medical history records found.'}
                </div>
              `;
            }
          })
          .catch(error => {
            resultsBox.innerHTML = `<div style="color: red; font-weight: 600; padding: 24px; text-align: center;">⚠️ ${error.message}</div>`;
            console.error('[Error] Fetch failed:', error);
          });
        }

        function fetchVAWRiskRecords() {
          const resultsBox = document.getElementById('vawRiskResults');
          resultsBox.innerHTML = `<div style="padding: 24px; text-align: center;">Loading...</div>`;

          const url = "<?= $getVAWRiskAssessmentURL ?>";
          
          fetch(url, {
            method: "GET",
            credentials: "same-origin"
          })
          .then(response => {
            if (!response.ok) throw new Error("HTTP " + response.status);
            return response.json();
          })
          .then(data => {
            if (data.status === "success" && Array.isArray(data.data) && data.data.length > 0) {
              const html = data.data.map(record => `
                <div style="margin-bottom: 12px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: left;">
                  <strong>Date:</strong> ${record.assessment_date || '—'}<br>
                  <strong>Doctor:</strong> ${record.examined_by || `Staff ID ${record.staff_id}`}<br>
                  <strong>Doctor's Note:</strong> ${record.doctor_note || '—'}
                </div>
              `).join("");
              resultsBox.innerHTML = html;
            } else {
              resultsBox.innerHTML = `
                <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                  ${data.message || 'No VAW risk records found.'}
                </div>
              `;
            }
          })
          .catch(error => {
            resultsBox.innerHTML = `<div style="color: red; font-weight: 600; padding: 24px; text-align: center;">⚠️ ${error.message}</div>`;
            console.error('[Error] Fetch failed:', error);
          });
        }

        function fetchObstetricalHistoryRecords() {
          const resultsBox = document.getElementById('obstetricalHistoryResults');
          resultsBox.innerHTML = `<div style="padding: 24px; text-align: center;">Loading...</div>`;

          const url = "<?= $getObstetricalHistoryURL ?>";
          
          fetch(url, {
            method: "GET",
            credentials: "same-origin"
          })
          .then(response => {
            if (!response.ok) throw new Error("HTTP " + response.status);
            return response.json();
          })
          .then(data => {
            if (data.status === "success" && Array.isArray(data.data) && data.data.length > 0) {
              const html = data.data.map(record => `
                <div style="margin-bottom: 12px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px; text-align: left;">
                  <strong>Date:</strong> ${record.visit_date || '—'}<br>
                  <strong>Doctor:</strong> ${record.examined_by || `Staff ID ${record.staff_id}`}<br>
                  <strong>Doctor's Note:</strong> ${record.doctor_note || '—'}<br>
                  <strong>Pregnancies:</strong> Full Term: ${record.full_term ?? 0}, Premature: ${record.premature ?? 0}, Abortions: ${record.abortions ?? 0}, Living: ${record.living_children ?? 0}<br>
                  <strong>Last Delivery:</strong> ${record.last_delivery_date || '—'} (${record.last_delivery_type || '—'})<br>
                  <strong>Menstrual Info:</strong> Last Period: ${record.past_menstrual_period || '—'}, Character: ${record.menstrual_character || '—'}<br>
                  <strong>Hydatidiform Mole:</strong> ${record.hydatidiform_mole ? 'Yes' : 'No'}<br>
                  <strong>Ectopic Pregnancy:</strong> ${record.ectopic_pregnancy ? 'Yes' : 'No'}
                </div>
              `).join("");
              resultsBox.innerHTML = html;
            } else {
              resultsBox.innerHTML = `
                <div style="background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(44,62,80,0.06); padding: 32px; color: #7c8ba1; font-size: 18px; text-align: center;">
                  ${data.message || 'No obstetrical history records found.'}
                </div>
              `;
            }
          })
          .catch(error => {
            resultsBox.innerHTML = `<div style="color: red; font-weight: 600; padding: 24px; text-align: center;">⚠️ ${error.message}</div>`;
            console.error('[Error] Fetch failed:', error);
          });
        }

        // Search form handlers for medical records
        const physicalExamTab = document.getElementById('physicalExamTab');
        const medicalHistoryTab = document.getElementById('medicalHistoryTab');
        const obstetricalHistoryTab = document.getElementById('obstetricalHistoryTab');
        const vawRiskTab = document.getElementById('vawRiskTab');

        if (physicalExamTab) {
          physicalExamTab.onclick = function() {
            document.getElementById('physicalExamContent').style.display = '';
            document.getElementById('medicalHistoryContent').style.display = 'none';
            document.getElementById('obstetricalHistoryContent').style.display = 'none';
            document.getElementById('vawRiskContent').style.display = 'none';
            fetchPhysicalExamRecords();
          };
        }

        if (medicalHistoryTab) {
          medicalHistoryTab.onclick = function() {
            document.getElementById('medicalHistoryContent').style.display = '';
            document.getElementById('physicalExamContent').style.display = 'none';
            document.getElementById('obstetricalHistoryContent').style.display = 'none';
            document.getElementById('vawRiskContent').style.display = 'none';
            fetchMedicalHistoryRecords();
          };
        }

        if (obstetricalHistoryTab) {
          obstetricalHistoryTab.onclick = function() {
            document.getElementById('obstetricalHistoryContent').style.display = '';
            document.getElementById('physicalExamContent').style.display = 'none';
            document.getElementById('medicalHistoryContent').style.display = 'none';
            document.getElementById('vawRiskContent').style.display = 'none';
            fetchObstetricalHistoryRecords();
          };
        }

        if (vawRiskTab) {
          vawRiskTab.onclick = function() {
            document.getElementById('vawRiskContent').style.display = '';
            document.getElementById('physicalExamContent').style.display = 'none';
            document.getElementById('medicalHistoryContent').style.display = 'none';
            document.getElementById('obstetricalHistoryContent').style.display = 'none';
            fetchVAWRiskRecords();
          };
        }

        // Search form submissions
        const physicalExamSearchForm = document.getElementById('physicalExamSearchForm');
        if (physicalExamSearchForm) {
          physicalExamSearchForm.onsubmit = function(e) {
            e.preventDefault();
            const date = document.getElementById('physicalExamDate').value;
            alert('Physical Exam search for date: ' + date + '\n(Backend integration needed)');
          };
        }

        const medicalHistorySearchForm = document.getElementById('medicalHistorySearchForm');
        if (medicalHistorySearchForm) {
          medicalHistorySearchForm.onsubmit = function(e) {
            e.preventDefault();
            const date = document.getElementById('medicalHistoryDate').value;
            alert('Medical History search for date: ' + date + '\n(Backend integration needed)');
          };
        }

        const obstetricalHistorySearchForm = document.getElementById('obstetricalHistorySearchForm');
        if (obstetricalHistorySearchForm) {
          obstetricalHistorySearchForm.onsubmit = function(e) {
            e.preventDefault();
            const date = document.getElementById('obstetricalHistoryDate').value;
            alert('Obstetrical History search for date: ' + date + '\n(Backend integration needed)');
          };
        }

        const vawRiskSearchForm = document.getElementById('vawRiskSearchForm');
        if (vawRiskSearchForm) {
          vawRiskSearchForm.onsubmit = function(e) {
            e.preventDefault();
            const date = document.getElementById('vawRiskDate').value;
            alert('VAW Risk search for date: ' + date + '\n(Backend integration needed)');
          };
        }
    </script>
</body>
</html>
