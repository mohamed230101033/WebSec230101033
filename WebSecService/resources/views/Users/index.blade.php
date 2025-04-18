@extends('layouts.master')
@section('title', 'Users Management')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people me-2"></i> Users</h1>
        @can('admin_users')
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i> Add User
            </a>
        @endcan
    </div>

    <!-- Search & Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or email" 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary me-2 w-100">
                            <i class="bi bi-search me-1"></i> Search
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Credit</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td class="ps-4">{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm bg-light me-2 d-flex align-items-center justify-content-center rounded-circle">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->hasRole('Admin'))
                                    <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                                @elseif($user->hasRole('Employee'))
                                    <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                                @elseif($user->hasRole('Customer'))
                                    <span class="badge role-badge-customer"><i class="bi bi-person me-1"></i> Customer</span>
                                @endif
                            </td>
                            <td>
                                @if($user->hasRole('Customer'))
                                    <span class="text-success">${{ number_format($user->credit, 2) }}</span>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    @can('edit_users')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit User">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @endcan
                                    
                                    <a href="{{ route('profile', $user->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="View Profile">
                                        <i class="bi bi-person"></i>
                                    </a>
                                    
                                    @can('manage_customer_credit')
                                    @if($user->hasRole('Customer'))
                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#addCreditModal" 
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            data-bs-toggle="tooltip" 
                                            title="Add Credit">
                                        <i class="bi bi-cash-coin"></i>
                                    </button>
                                    @endif
                                    @endcan
                                    
                                    @can('delete_users')
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteUserModal{{ $user->id }}"
                                            title="Delete User">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    
                                    <!-- Delete User Modal -->
                                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-4">
                                                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                                        <h4 class="mt-3">Are you sure?</h4>
                                                        <p>Do you really want to delete <strong>{{ $user->name }}</strong>? This action cannot be undone.</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash me-1"></i> Delete User
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

<!-- Add Credit Modal -->
<div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCreditModalLabel">Add Credit to User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.addCredit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="creditUserId">
                    
                    <div class="mb-3">
                        <label class="form-label">User</label>
                        <input type="text" class="form-control" id="creditUserName" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Credit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('users.index') }}" method="GET" id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="role[]" value="admin" id="roleAdmin">
                            <label class="form-check-label" for="roleAdmin">Admin</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="role[]" value="employee" id="roleEmployee">
                            <label class="form-check-label" for="roleEmployee">Employee</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="role[]" value="customer" id="roleCustomer">
                            <label class="form-check-label" for="roleCustomer">Customer</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sortBy" class="form-label">Sort By</label>
                        <select class="form-select" id="sortBy" name="sort_by">
                            <option value="name">Name</option>
                            <option value="email">Email</option>
                            <option value="created_at">Date Created</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sortOrder" class="form-label">Sort Order</label>
                        <select class="form-select" id="sortOrder" name="sort_order">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('filterForm').submit()">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.9rem;
    color: #6c757d;
}

.role-badge-admin {
    background-color: #6610f2;
}

.role-badge-employee {
    background-color: #0d6efd;
}

.role-badge-customer {
    background-color: #198754;
}

.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.search-box {
    position: relative;
}

.search-box i {
    position: absolute;
    left: 10px;
    top: 10px;
    color: #6c757d;
}

.search-box input {
    padding-left: 35px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Handle Add Credit Modal
    var addCreditModal = document.getElementById('addCreditModal')
    if (addCreditModal) {
        addCreditModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var userId = button.getAttribute('data-user-id')
            var userName = button.getAttribute('data-user-name')
            var userCredit = button.closest('tr').querySelector('td:nth-child(5) .text-success')?.textContent || '$0.00'
            
            var creditUserName = document.getElementById('creditUserName')
            var creditUserId = document.getElementById('creditUserId')
            var userCurrentBalance = document.getElementById('userCurrentBalance')
            
            creditUserName.textContent = userName
            creditUserId.value = userId
            userCurrentBalance.textContent = userCredit
            
            // Focus on amount input
            setTimeout(function() {
                document.getElementById('amount').focus()
            }, 500)
            
            // Update form action with correct user ID using the named route
            document.getElementById('addCreditForm').action = '{{ url("users") }}/' + userId + '/add-credit';
        })
    }
});
</script>
@endsection
