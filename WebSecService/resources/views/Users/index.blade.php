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
<div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-gradient-primary text-white py-3">
                <h5 class="modal-title" id="addCreditModalLabel">
                    <i class="bi bi-cash-coin me-2"></i>Add Credit to Customer
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('users/0/add-credit') }}" method="POST" id="addCreditForm">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="user_id" id="creditUserId">
                    
                    <!-- Customer Info Card -->
                    <div class="card bg-light border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="customer-avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0" id="creditUserNameDisplay">Customer Name</h6>
                                    <input type="text" class="form-control d-none" id="creditUserName" readonly>
                                    <small class="text-muted" id="creditUserIdDisplay">ID: #0000</small>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block">Current Balance</small>
                                    <span class="fw-bold text-success fs-5" id="userCurrentBalance">$0.00</span>
                                </div>
                                <div>
                                    <small class="text-muted d-block text-end">After Transaction</small>
                                    <span class="fw-bold text-primary fs-5" id="afterTransactionAmount">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amount Input -->
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">Amount to Add</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-currency-dollar text-primary"></i></span>
                            <input type="number" step="0.01" min="0.01" class="form-control border-start-0 shadow-none ps-0" 
                                   id="amount" name="amount" required placeholder="0.00">
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Enter the amount you want to add to the customer's account
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between bg-light py-3">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center">
                        <i class="bi bi-check-circle me-2"></i>Confirm Transaction
                    </button>
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

.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd, #0043a8);
}

.rounded-4 {
    border-radius: 0.5rem;
}

.customer-avatar {
    width: 50px;
    height: 50px;
    font-size: 1.25rem;
}

.modal-content {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
            var creditUserNameDisplay = document.getElementById('creditUserNameDisplay')
            var creditUserIdDisplay = document.getElementById('creditUserIdDisplay')
            var creditUserId = document.getElementById('creditUserId')
            var userCurrentBalance = document.getElementById('userCurrentBalance')
            var amountInput = document.getElementById('amount')
            var afterTransactionAmount = document.getElementById('afterTransactionAmount')
            
            // Debug to console
            console.log('User ID:', userId)
            console.log('User Name:', userName)
            console.log('User Credit:', userCredit)
            
            // Set values
            creditUserName.value = userName
            creditUserNameDisplay.textContent = userName
            creditUserIdDisplay.textContent = 'ID: #' + userId
            creditUserId.value = userId
            userCurrentBalance.textContent = userCredit
            afterTransactionAmount.textContent = userCredit
            
            // Calculate after transaction amount when amount changes
            amountInput.addEventListener('input', function() {
                var currentCredit = parseFloat(userCredit.replace(/[^0-9.-]+/g, '')) || 0
                var addAmount = parseFloat(this.value) || 0
                var newAmount = currentCredit + addAmount
                afterTransactionAmount.textContent = '$' + newAmount.toFixed(2)
            })
            
            // Focus on amount input
            setTimeout(function() {
                amountInput.focus()
            }, 500)
            
            // Update form action with correct user ID
            document.getElementById('addCreditForm').action = '{{ url("users") }}/' + userId + '/add-credit';
        })
    }
});
</script>
@endsection
