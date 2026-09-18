<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pegawai')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <style>
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        body > .container-fluid,
        body > .container-fluid > .row {
            min-height: 100vh;
        }
        .sidebar-col {
            position: relative;
            transition: all 0.3s ease;
        }
        .sidebar {
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            min-height: 100%;
            height: 100%;
            padding: 20px;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1000;
        }
        .sidebar.hidden {
            margin-left: -100%;
        }
        .sidebar > .flex-grow-1 {
            flex-grow: 1;
        }
        .sidebar .brand {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #34495e;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background .2s;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .sidebar a.active {
            background: #3498db;
        }
        .content {
            padding: 20px;
            transition: all 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @auth
            <div class="col-md-2 p-0 sidebar-col" id="sidebarCol">
                @include('layouts.sidebar')
            </div>
            <div class="col-md-10 content" id="contentCol">
                <nav class="navbar navbar-light bg-light border-bottom mb-3 rounded shadow-sm px-3">
                    <div class="container-fluid px-0">
                        <button class="btn btn-outline-dark btn-sm" id="toggleSidebarBtn">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                        <span class="navbar-brand mb-0 h1 ms-3 fs-6 text-muted">@yield('title', 'Sistem Pegawai')</span>
                    </div>
                </nav>
            @else
            <div class="col-md-12 content" id="contentCol">
            @endauth
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarCol = document.getElementById('sidebarCol');
        const contentCol = document.getElementById('contentCol');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');

        function toggleSidebar() {
            sidebar.classList.toggle('hidden');
            if (sidebarCol) sidebarCol.classList.toggle('d-none');
            
            if (contentCol) {
                contentCol.classList.toggle('col-md-10');
                contentCol.classList.toggle('col-md-12');
            }
        }

        if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
