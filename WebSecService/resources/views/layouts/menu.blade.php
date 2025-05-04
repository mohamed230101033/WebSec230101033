<nav class="navbar navbar-expand-lg navbar-light bg-white mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-shield-lock text-primary"></i>
            <span>WebSecService</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>
                
                <!-- Services Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear me-1"></i> Services
                    </a>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="servicesDropdown">
                        <li><a class="dropdown-item" href="/even"><i class="bi bi-calculator me-2"></i> Even Numbers</a></li>
                        <li><a class="dropdown-item" href="/prime"><i class="bi bi-calculator me-2"></i> Prime Numbers</a></li>
                        <li><a class="dropdown-item" href="/multable"><i class="bi bi-table me-2"></i> Multiplication Table</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('minitest') }}"><i class="bi bi-receipt me-2"></i> MiniTest Bill</a></li>
                        <li><a class="dropdown-item" href="{{ route('transcript') }}"><i class="bi bi-file-earmark-text me-2"></i> Transcript</a></li>
                        <li><a class="dropdown-item" href="{{ route('calculator') }}"><i class="bi bi-calculator me-2"></i> Calculator</a></li>
                        <li><a class="dropdown-item" href="{{ route('grades.index') }}"><i class="bi bi-mortarboard me-2"></i> GPA Calculator</a></li>
                        <li><a class="dropdown-item" href="{{ url('/questions') }}"><i class="bi bi-question-circle me-2"></i> MCQ Exam</a></li>
                    </ul>
                </li>
                
                <!-- Products Link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}" href="{{ route('products_list') }}">
                        <i class="bi bi-shop me-1"></i> Products
                    </a>
                </li>
                
                <!-- Purchases links -->
                @auth
                    <!-- Admin sees All Customer Purchases -->
                    @if(auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('my-purchases*') ? 'active' : '' }}" href="{{ route('purchases_list') }}">
                            <i class="bi bi-bag-check me-1"></i> All Customer Purchases
                        </a>
                    </li>
                    @endif
                    
                    <!-- Customers see My Purchases -->
                    @if(auth()->user()->hasRole('Customer') && auth()->user()->hasPermissionTo('purchase_products'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('my-purchases*') ? 'active' : '' }}" href="{{ route('purchases_list') }}">
                            <i class="bi bi-bag-check me-1"></i> My Purchases
                        </a>
                    </li>
                    @endif
                @endauth
                
                <!-- Admin & Employee menu items -->
                @auth
                    @if(auth()->user()->hasRole(['Admin', 'Employee']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear-fill me-1"></i> Management
                        </a>
                        <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="adminDropdown">
                            @can('show_users')
                            <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi bi-people me-2"></i> Users</a></li>
                            @endcan
                            @can('add_products')
                            <li><a class="dropdown-item" href="{{ route('products_edit') }}"><i class="bi bi-plus-circle me-2"></i> Add Product</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                @endauth
            </ul>
            
            <ul class="navbar-nav ms-auto">
                @auth
                    <!-- Show user credit for customers -->
                    <li class="nav-item">
                        <span class="nav-link text-success">
                            <i class="bi bi-cash-coin me-1"></i> ${{ number_format(auth()->user()->credit ?? 0, 2) }}
                        </span>
                    </li>
                    
                    <!-- Show role badge -->
                    <li class="nav-item">
                        <span class="nav-link">
                            @php $displayRole = auth()->user()->getDisplayRole(); @endphp
                            @if($displayRole == 'Admin')
                                <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                            @elseif($displayRole == 'Employee')
                                <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                            @else
                                <span class="badge role-badge-customer"><i class="bi bi-person me-1"></i> Customer</span>
                            @endif
                        </span>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            @if(session('cert_login'))
                                <li><a class="dropdown-item text-danger" href="{{ route('cert.logout') }}"><i class="bi bi-shield-lock me-2"></i> Certificate Logout</a></li>
                            @else
                                <li><a class="dropdown-item text-danger" href="{{ route('doLogout') }}"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            @endif
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus me-1"></i> Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>