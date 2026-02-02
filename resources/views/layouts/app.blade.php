<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Record System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            transition: all 0.3s ease;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            background: var(--primary-gradient);
            color: white;
        }

        .sidebar-header h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .sidebar-menu a i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .top-bar-left h5 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #212529;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .theme-toggle {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 18px;
        }

        .theme-toggle:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .btn-logout {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Modern Cards */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 25px;
        }

        /* Modern Buttons */
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
        }

        .btn-danger {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
        }

        .btn-secondary {
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
        }

        /* Modern Table */
        .table {
            border-radius: 10px;
            overflow: hidden;
        }

        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* Modern Form Controls */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 8px;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            padding: 15px 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #1a1a2e;
            color: #e0e0e0;
        }

        body.dark-mode .sidebar {
            background: #16213e;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }

        body.dark-mode .sidebar-menu a {
            color: #a0a0a0;
        }

        body.dark-mode .sidebar-menu a:hover,
        body.dark-mode .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.2) 0%, transparent 100%);
            color: #667eea;
        }

        body.dark-mode .top-bar {
            background: #16213e;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        body.dark-mode .top-bar-left h5 {
            color: #e0e0e0;
        }

        body.dark-mode .theme-toggle {
            background: #1a1a2e;
        }

        body.dark-mode .theme-toggle:hover {
            background: #0f3460;
        }

        body.dark-mode .card {
            background: #16213e;
            color: #e0e0e0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        body.dark-mode .card-header {
            background: #16213e;
            border-bottom-color: #0f3460;
        }

        body.dark-mode .table thead th {
            background: #0f3460;
            color: #a0a0a0;
        }

        body.dark-mode .table tbody td {
            border-bottom-color: #0f3460;
            color: #e0e0e0;
        }

        body.dark-mode .table tbody tr:hover {
            background: #0f3460;
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: #0f3460;
            border-color: #0f3460;
            color: #e0e0e0;
        }

        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background: #0f3460;
            border-color: #667eea;
        }

        body.dark-mode .form-label {
            color: #a0a0a0;
        }

        /* Fix text visibility in dark mode */
        body.dark-mode p,
        body.dark-mode span:not(.badge),
        body.dark-mode div {
            color: #e0e0e0;
        }

        body.dark-mode .text-muted {
            color: #a0a0a0 !important;
        }

        body.dark-mode small {
            color: #a0a0a0;
        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6 {
            color: #e0e0e0;
        }

        /* Pagination Styling */
        .pagination {
            margin: 0;
        }

        .pagination .page-link {
            border: 1px solid #e0e0e0;
            color: #667eea;
            padding: 8px 12px;
            margin: 0 4px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
            border-color: #e0e0e0;
            background: #f8f9fa;
        }

        body.dark-mode .pagination .page-link {
            background: #16213e;
            border-color: #0f3460;
            color: #667eea;
        }

        body.dark-mode .pagination .page-link:hover {
            background: #0f3460;
            border-color: #667eea;
        }

        body.dark-mode .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }

        body.dark-mode .pagination .page-item.disabled .page-link {
            background: #0f3460;
            border-color: #0f3460;
            color: #666;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark-mode');
                if (document.body) {
                    document.body.classList.add('dark-mode');
                }
            }
        })();
    </script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-mortarboard-fill"></i> Student Records</h4>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Students</span>
            </a>
            <a href="{{ route('activity-logs.index') }}" class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i>
                <span>Activity Logs</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="top-bar-right">
                <button class="theme-toggle" id="theme-toggle" title="Toggle theme">
                    <span id="theme-icon">🌙</span>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="content-area">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="content-area">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Main Content Area -->
        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;

        // Load theme preference on page load
        function loadTheme() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark') {
                body.classList.add('dark-mode');
                themeIcon.textContent = '☀️';
            } else {
                body.classList.remove('dark-mode');
                themeIcon.textContent = '🌙';
            }
        }

        // Toggle theme
        themeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            
            // Save theme preference to localStorage
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeIcon.textContent = '☀️';
            } else {
                localStorage.setItem('theme', 'light');
                themeIcon.textContent = '🌙';
            }
        });

        // Load theme on page load
        loadTheme();

        // Auto-dismiss success messages after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
