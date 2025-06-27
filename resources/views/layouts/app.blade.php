<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'نظام التقارير الأمنية') }} | @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" integrity="sha384-nU14brUcp6StFntEOOEBvcJm4huWjB0OcIeQ3fltAfSmuZFrkAif0T+UtNGlKKQv" crossorigin="anonymous">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Cairo', 'Public Sans', sans-serif;
        }
        
        .layout-navbar {
            background-color: #fff;
            box-shadow: 0 0 15px 0 rgba(0,0,0,.05);
            height: 64px;
            padding: 0 1.5rem;
        }
        
        .layout-menu {
            background-color: #fff;
            box-shadow: 0 0 15px 0 rgba(0,0,0,.05);
            width: 260px;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 1000;
            transition: all .2s ease-in-out;
        }
        
        .layout-page {
            margin-right: 260px;
            transition: all .2s ease-in-out;
            width: calc(100% - 260px);
            padding-right: 1.5rem;
            padding-left: 1.5rem;
            box-sizing: border-box;
        }
        
        .menu-vertical {
            padding: 1.25rem 0;
        }
        
        .menu-item {
            padding: 0.625rem 1.5rem;
        }
        
        .menu-item a {
            color: #697a8d;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .menu-item a:hover {
            color: #696cff;
        }
        
        .menu-item a i {
            margin-left: 0.5rem;
        }
        
        .menu-item.active > a {
            color: #696cff;
            font-weight: 600;
        }
        
        .menu-item .collapse {
            margin-top: 0.5rem;
        }
        
        .menu-item .collapse .menu-item {
            padding-right: 2rem;
            font-size: 0.9rem;
        }
        
        .menu-item .collapse .menu-item a {
            padding: 0.4rem 0;
        }
        
        .menu-item .btn-link {
            color: #697a8d;
            text-decoration: none;
        }
        
        .menu-item .btn-link:hover {
            color: #696cff;
        }
        
        .menu-item .btn-link .bx-chevron-down {
            transition: transform 0.2s;
        }
        
        .menu-item .btn-link[aria-expanded="true"] .bx-chevron-down {
            transform: rotate(180deg);
        }
        
        .menu-item .menu-link.flex-grow-1 {
            padding-left: 0;
        }
        
        .container-xxl {
            max-width: 1440px;
            width: 100%;
        }
        .card {
            box-shadow: 0 0 15px 0 rgba(0,0,0,.05);
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #d9dee3;
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn-primary {
            background-color: #696cff;
            border-color: #696cff;
        }
        
        .btn-primary:hover {
            background-color: #5f62e8;
            border-color: #5f62e8;
        }
        
        .stats-card {
            padding: 1.5rem;
            border-radius: 0.5rem;
            color: #fff;
            margin-bottom: 1.5rem;
        }
        
        .stats-card-primary {
            background-color: #696cff;
        }
        
        .stats-card-success {
            background-color: #71dd37;
        }
        
        .stats-card-info {
            background-color: #03c3ec;
        }
        
        .stats-card-warning {
            background-color: #ffab00;
        }
        
        .stats-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .stats-card p {
            margin-bottom: 0;
            opacity: 0.8;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .form-label {
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            padding: 0.5rem 0.875rem;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }
        
        .form-control:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1);
        }
        
        .form-select {
            padding: 0.5rem 0.875rem;
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
        }
        
        .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.1);
        }
        
        /* تنسيقات أخرى */
        
        /* تنسيقات أسهم التنقل */
        .pagination svg {
            width: 14px !important;
            height: 14px !important;
            stroke-width: 2px;
        }
        
        .pagination nav div:first-child {
            display: none; /* إخفاء النص الوصفي للصفحات */
        }
        
        .pagination .page-item .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #696cff;
            border-color: #696cff;
        }
        
        /* تنسيقات خاصة للأسهم الكبيرة في أسفل الصفحة */
        svg[width="24"], svg[height="24"] {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
        }
        
        /* تنسيقات للسهم الأول - بناءً على XPath المقدم */
        nav div span span:first-child svg {
            width: 18px !important;
            height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            transform: scale(0.8) !important;
        }
        
        /* تنسيقات للسهم الثاني - بناءً على XPath الجديد */
        nav div span a:nth-child(2) svg,
        nav div span a svg path {
            width: 18px !important;
            height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            transform: scale(0.8) !important;
        }
        
        /* استهداف مباشر للسهم الثاني باستخدام XPath الكامل */
        nav div span a[rel="next"] svg {
            width: 18px !important;
            height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            transform: scale(0.8) !important;
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper">
        <!-- Menu -->
        <aside class="layout-menu">
            <div class="app-brand p-4 text-center">
                <a href="{{ route('operation-areas.index') }}" class="app-brand-link">
                    <span class="app-brand-text fw-bolder fs-4">{{ config('app.name', 'نظام التقارير الأمنية') }}</span>
                </a>
            </div>

            <ul class="menu-vertical navbar-nav">
                <li class="menu-item {{ request()->routeIs('operation-areas.*') ? 'active' : '' }}">
                    <a href="{{ route('operation-areas.index') }}" class="menu-link">
                        <i class='bx bx-map'></i>
                        <span>مناطق العمليات</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('observers.*') ? 'active' : '' }}">
    <a href="{{ route('observers.index') }}" class="menu-link">
        <i class='bx bx-user-check'></i>
        <span>الراصدين</span>
    </a>
</li>
<li class="menu-item {{ request()->routeIs('response.*') || (request()->routeIs('response-points.*') && !request()->routeIs('response-points.items.*')) ? 'active' : '' }}">
    <div class="d-flex align-items-center">
        <a href="{{ route('response.dashboard') }}" class="menu-link flex-grow-1">
            <i class='bx bx-shield-quarter'></i>
            <span>نقاط الاستجابة</span>
        </a>
        <button class="btn btn-link p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#responseSubmenu" aria-expanded="{{ request()->routeIs('response.*') || (request()->routeIs('response-points.*') && !request()->routeIs('response-points.items.*')) ? 'true' : 'false' }}">
            <i class='bx bx-chevron-down'></i>
        </button>
    </div>
    <div class="collapse {{ request()->routeIs('response.*') || (request()->routeIs('response-points.*') && !request()->routeIs('response-points.items.*')) ? 'show' : '' }}" id="responseSubmenu">
        <ul class="navbar-nav ps-3 mt-2">
            <li class="menu-item {{ request()->routeIs('response-points.create') ? 'active' : '' }}">
                <a href="{{ route('response-points.create') }}" class="menu-link">
                    <i class='bx bx-plus'></i>
                    <span>إضافة نقطة جديدة</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- قسم إدارة العناصر -->
<li class="menu-item {{ request()->routeIs('response-points.items.*') ? 'active' : '' }}">
    <div class="d-flex align-items-center">
        <a href="{{ route('response-points.items.index', ['responsePoint' => 'all']) }}" class="menu-link flex-grow-1">
            <i class='bx bx-group'></i>
            <span>إدارة العناصر</span>
        </a>
        <button class="btn btn-link p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#itemsSubmenu" aria-expanded="{{ request()->routeIs('response-points.items.*') ? 'true' : 'false' }}">
            <i class='bx bx-chevron-down'></i>
        </button>
    </div>
    <div class="collapse {{ request()->routeIs('response-points.items.*') ? 'show' : '' }}" id="itemsSubmenu">
        <ul class="navbar-nav ps-3 mt-2">
            <li class="menu-item {{ request()->routeIs('response-points.items.create') ? 'active' : '' }}">
                <a href="{{ route('response-points.items.create') }}" class="menu-link">
                    <i class='bx bx-plus'></i>
                    <span>إضافة عناصر جديدة</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- قسم البلاغات والتقارير -->
<li class="menu-item {{ request()->routeIs('reports.*') || request()->routeIs('report-types.*') ? 'active' : '' }}">
    <div class="d-flex align-items-center">
        <a href="{{ route('reports.dashboard') }}" class="menu-link flex-grow-1">
            <i class='bx bx-file'></i>
            <span>البلاغات والتقارير</span>
        </a>
        <button class="btn btn-link p-0 ms-2" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu" aria-expanded="{{ request()->routeIs('reports.*') || request()->routeIs('report-types.*') ? 'true' : 'false' }}">
            <i class='bx bx-chevron-down'></i>
        </button>
    </div>
    <div class="collapse {{ request()->routeIs('reports.*') || request()->routeIs('report-types.*') ? 'show' : '' }}" id="reportsSubmenu">
        <ul class="navbar-nav ps-3 mt-2">
            <li class="menu-item {{ request()->routeIs('reports.create') ? 'active' : '' }}">
                <a href="{{ route('reports.create') }}" class="menu-link">
                    <i class='bx bx-plus'></i>
                    <span>إضافة بلاغ جديد</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('report-types.*') ? 'active' : '' }}">
                <a href="{{ route('report-types.index') }}" class="menu-link">
                    <i class='bx bx-category'></i>
                    <span>أنواع البلاغات</span>
                </a>
            </li>
        </ul>
    </div>
</li>

<!-- يمكن إضافة المزيد من عناصر القائمة هنا في المستقبل -->
            </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- رسائل النجاح والخطأ -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @yield('content')
            </div>
            <!-- / Content wrapper -->
        </div>
        <!-- / Layout container -->
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Main JS -->
    <script>
        $(function() {
            // يمكن إضافة سكريبتات عامة هنا
        });
    </script>

    @yield('scripts')
@stack('scripts')
</body>
</html>
