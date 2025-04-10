@extends('layouts.master')
@section('title', 'User Dashboard')
@section('content')
<div class="container">
    <!-- User Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-center">
                        <div class="user-avatar bg-primary mb-3 mb-md-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ms-md-4 text-center text-md-start">
                            <h2 class="mb-1">{{ $user->name }}</h2>
                            <p class="text-muted mb-2">
                                <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                            </p>
                            @if($user->hasRole('Admin'))
                                <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                            @elseif($user->hasRole('Employee'))
                                <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                            @elseif($user->hasRole('Customer'))
                                <span class="badge role-badge-customer"><i class="bi bi-person me-1"></i> Customer</span>
                            @endif
                        </div>
                        
                        <div class="ms-auto mt-3 mt-md-0">
                            <a href="#" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="bi bi-key me-1"></i> Change Password
                            </a>
                            @can('edit_users')
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary ms-2">
                                    <i class="bi bi-pencil me-1"></i> Edit Profile
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($user->hasRole('Customer'))
    <!-- Customer Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-header">
                <h3>Customer Dashboard</h3>
                <p class="mb-0">Welcome back, {{ $user->name }}</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Available Balance -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-light-success">
                    <i class="bi bi-wallet2 text-success"></i>
                </div>
                <div class="stat-value">${{ number_format($user->credit, 2) }}</div>
                <div class="stat-label">Available Balance</div>
            </div>
        </div>

        <!-- Total Purchases -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-light-primary">
                    <i class="bi bi-bag-check text-primary"></i>
                </div>
                <div class="stat-value">{{ $user->purchases()->count() }}</div>
                <div class="stat-label">Total Purchases</div>
            </div>
        </div>

        <!-- Amount Spent -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon bg-light-danger">
                    <i class="bi bi-cash text-danger"></i>
                </div>
                <div class="stat-value">${{ number_format($user->purchases()->sum('total_price'), 2) }}</div>
                <div class="stat-label">Total Amount Spent</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <!-- Purchase History Card -->
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Purchase History</h5>
                    <span class="badge bg-primary">{{ $user->purchases()->count() }} Orders</span>
                </div>
                <div class="card-body">
                    @if($user->purchases()->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->purchases()->with('product')->latest()->take(5)->get() as $purchase)
                                    <tr>
                                        <td>{{ $purchase->created_at->format('M d, Y') }}</td>
                                        <td>{{ $purchase->product->name }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td>${{ number_format($purchase->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('purchases_list') }}" class="btn btn-sm btn-outline-primary">View All Purchases</a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-bag-x fs-1 text-muted"></i>
                            <p class="mt-2">You haven't made any purchases yet.</p>
                            <a href="{{ route('products_list') }}" class="btn btn-primary">Browse Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Account Details Card -->
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i> Account Access</h5>
                </div>
        <div class="card-body">
                    <p class="text-muted fs-6">Your permissions and settings</p>
                    
                    <div class="mb-3">
                        <h6>Account Type</h6>
                        <p class="mb-0">
                            @if($user->hasRole('Admin'))
                                <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                            @elseif($user->hasRole('Employee'))
                                <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                            @elseif($user->hasRole('Customer'))
                                <span class="badge role-badge-customer"><i class="bi bi-person me-1"></i> Customer</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Permissions</h6>
                        <div>
                            @foreach($user->getAllPermissions() as $permission)
                                <span class="badge bg-light text-dark mb-1 me-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <h6>Account Details</h6>
                        <div class="text-muted">
                            <p class="mb-1"><small>Created: {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</small></p>
                            <p class="mb-0"><small>Last Updated: {{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Admin/Employee Dashboard -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i> Account Access</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Account Type</h6>
                        <p class="mb-0">
                            @if($user->hasRole('Admin'))
                                <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                            @elseif($user->hasRole('Employee'))
                                <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Permissions</h6>
                        <div>
                            @foreach($user->getAllPermissions() as $permission)
                                <span class="badge bg-light text-dark mb-1 me-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <h6>Account Details</h6>
                        <div class="text-muted">
                            <p class="mb-1"><small>Created: {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</small></p>
                            <p class="mb-0"><small>Last Updated: {{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i> Management Tools</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @can('show_users')
                        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-people me-3 fs-4"></i>
                            <div>
                                <strong>User Management</strong>
                                <p class="mb-0 text-muted">View and manage system users</p>
                            </div>
                        </a>
                        @endcan
                        
                        @can('add_products')
                        <a href="{{ route('products_edit') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-plus-circle me-3 fs-4"></i>
                            <div>
                                <strong>Add New Product</strong>
                                <p class="mb-0 text-muted">Create and publish new products</p>
                            </div>
                        </a>
                        @endcan
                        
                        @can('hold_products')
                        <a href="{{ route('products_list') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="bi bi-eye-slash me-3 fs-4"></i>
                            <div>
                                <strong>Product Visibility</strong>
                                <p class="mb-0 text-muted">Hold/unhold products from customer view</p>
                            </div>
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Password Change Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('updatePassword', $user->id) }}" method="post">
                @csrf
                <div class="modal-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                    @error('current_password')
                            <div class="text-danger mt-1">{{ 'Something went wrong' }}</div>
                    @enderror
                </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                    @error('new_password')
                            <div class="text-danger mt-1">{{ 'Something went wrong' }}</div>
                    @enderror
                </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-light-primary {
    background-color: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
}
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}
.bg-light-danger {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}
</style>
@endsection
