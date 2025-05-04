<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(str_contains(request()->getHost(), 'websecservice.localhost.com'))
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <title>WebSecService - @yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="{{ secure_asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            background-color: #f5f8fa;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.2rem;
        }
        
        .card {
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 30px;
        }
        
        .table {
            vertical-align: middle;
        }
        
        .btn {
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 500;
        }
        
        .btn-sm {
            padding: 0.25rem 0.7rem;
            font-size: 0.875rem;
        }
        
        .stat-card {
            padding: 1.5rem;
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .role-badge-admin {
            background-color: var(--danger-color);
            color: white;
        }
        
        .role-badge-employee {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .role-badge-customer {
            background-color: var(--info-color);
            color: #212529;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #0d6efd, #0099ff);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .search-box input {
            padding-left: 2.5rem;
            border-radius: 50px;
            border: 1px solid #ddd;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }
        
        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    @include('layouts.menu')
    <div class="container py-4">
        @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm fade-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm fade-alert" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        </div>
        @endif

        @yield('content')
    </div>
    
    <!-- Global Confirmation Modal -->
    <div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 overflow-hidden">
          <div class="modal-header bg-primary bg-opacity-10 border-0">
            <h5 class="modal-title" id="globalConfirmTitle">Confirm Action</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4">
            <div class="text-center mb-3">
              <div class="mb-3 text-primary">
                <i class="bi bi-question-circle" style="font-size: 3rem;"></i>
              </div>
              <h4 class="mb-3" id="globalConfirmMessage">Are you sure you want to proceed?</h4>
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary rounded-pill" id="globalConfirmBtn">
              <i class="bi bi-check2 me-2"></i>Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="{{ secure_asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Enable Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Enhanced Confirmation Dialog
            const globalConfirmModal = new bootstrap.Modal(document.getElementById('globalConfirmModal'));
            const globalConfirmBtn = document.getElementById('globalConfirmBtn');
            const globalConfirmTitle = document.getElementById('globalConfirmTitle');
            const globalConfirmMessage = document.getElementById('globalConfirmMessage');
            
            // Override the default confirm dialog
            const originalConfirm = window.confirm;
            window.confirm = function(message, title = 'Confirm Action') {
                return new Promise((resolve) => {
                    globalConfirmTitle.textContent = title;
                    globalConfirmMessage.textContent = message;
                    
                    globalConfirmBtn.onclick = function() {
                        globalConfirmModal.hide();
                        resolve(true);
                    };
                    
                    const dismissBtns = document.querySelectorAll('[data-bs-dismiss="modal"]');
                    dismissBtns.forEach(btn => {
                        btn.onclick = function() {
                            resolve(false);
                        };
                    });
                    
                    globalConfirmModal.show();
                });
            };
            
            // Fix for duplicate error messages
            const errorAlerts = document.querySelectorAll('.alert-danger');
            if (errorAlerts.length > 1) {
                // Keep only the first error alert
                for (let i = 1; i < errorAlerts.length; i++) {
                    errorAlerts[i].remove();
                }
            }
            
            // Comprehensive fix for modal flickering/glitching
            const modalFixStyle = document.createElement('style');
            modalFixStyle.textContent = `
                /* Fix for modals glitching on mouse movement */
                .modal {
                    animation: none !important;
                    transition: none !important;
                    transform: none !important;
                }
                .modal-backdrop {
                    animation: none !important;
                    transition: none !important;
                }
                .modal-dialog {
                    margin: 1.75rem auto !important;
                    transform: none !important;
                    transition: none !important;
                }
                .fade {
                    transition: none !important;
                }
                
                /* For nicer alerts */
                .fade-alert {
                    animation: fadeOut 5s forwards;
                    opacity: 1;
                    border-left: 4px solid;
                }
                .alert-success {
                    border-left-color: #198754;
                    background-color: #f8fff9;
                }
                .alert-danger {
                    border-left-color: #dc3545;
                    background-color: #fff8f8;
                }
                @keyframes fadeOut {
                    0% { opacity: 0; transform: translateY(-10px); }
                    5% { opacity: 1; transform: translateY(0); }
                    75% { opacity: 1; }
                    100% { opacity: 0; }
                }
            `;
            document.head.appendChild(modalFixStyle);
            
            // Auto dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    setTimeout(function() {
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.remove();
                        }, 500);
                    }, 5000);
                });
            }, 100);
            
            // Handle modals with a custom opener and closer
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(button) {
                // Get target modal
                const targetModalId = button.getAttribute('data-bs-target');
                if (!targetModalId) return;
                
                const targetModal = document.querySelector(targetModalId);
                if (!targetModal) return;
                
                // Create a completely manual implementation
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Show backdrop manually
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop show';
                    document.body.appendChild(backdrop);
                    
                    // Show modal
                    targetModal.style.display = 'block';
                    targetModal.classList.add('show');
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = '17px';
                    
                    // Handle closing
                    const closeModal = function() {
                        targetModal.style.display = 'none';
                        targetModal.classList.remove('show');
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        document.body.removeChild(backdrop);
                    };
                    
                    // Close when clicking dismiss buttons
                    targetModal.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(closeBtn) {
                        closeBtn.addEventListener('click', closeModal);
                    });
                    
                    // Close when clicking backdrop if not static
                    if (targetModal.getAttribute('data-bs-backdrop') !== 'static') {
                        backdrop.addEventListener('click', closeModal);
                    }
                    
                    // Close when pressing escape if keyboard=true
                    if (targetModal.getAttribute('data-bs-keyboard') !== 'false') {
                        document.addEventListener('keydown', function escapeHandler(e) {
                            if (e.key === 'Escape') {
                                closeModal();
                                document.removeEventListener('keydown', escapeHandler);
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>