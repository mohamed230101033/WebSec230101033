@extends('layouts.master')
@section('title', 'Edit User')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 my-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-person-gear me-2 text-primary"></i> Edit User</h4>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Users
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone (optional)</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password (leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">User Role <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3 flex-wrap">
                                    @foreach($roles as $role)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input role-radio" type="radio" name="role" 
                                               id="role_{{ $role->id }}" value="{{ $role->id }}" 
                                               data-role-id="{{ $role->id }}"
                                               {{ old('role', $currentRoleId) == $role->id ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            @if($role->name == 'Admin')
                                            <span class="badge role-badge-admin"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                                            @elseif($role->name == 'Employee')
                                            <span class="badge role-badge-employee"><i class="bi bi-person-badge me-1"></i> Employee</span>
                                            @elseif($role->name == 'Customer')
                                            <span class="badge role-badge-customer"><i class="bi bi-person me-1"></i> Customer</span>
                                            @else
                                            <span class="badge bg-secondary">{{ $role->name }}</span>
                                            @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-3">Permissions</label>
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-info-circle me-1"></i> Permissions in <span class="badge bg-light text-dark border">grey</span> are included with the selected role. 
                                    You can select additional permissions below.
                                </p>
                                
                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                    @foreach($permissions as $permission)
                                    <div class="col">
                                        <div class="form-check permission-check">
                                            <input class="form-check-input permission-checkbox" 
                                                   type="checkbox" 
                                                   name="permissions[]" 
                                                   id="permission_{{ $permission->id }}" 
                                                   value="{{ $permission->id }}"
                                                   data-permission-id="{{ $permission->id }}"
                                                   {{ (is_array(old('permissions', $userPermissions)) && in_array($permission->id, old('permissions', $userPermissions))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Update User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.role-badge-admin {
    background-color: #6610f2;
}

.role-badge-employee {
    background-color: #0d6efd;
}

.role-badge-customer {
    background-color: #198754;
}

.permission-check.disabled {
    opacity: 0.75;
}

.permission-check.from-role .form-check-label {
    position: relative;
}

.permission-check.from-role .form-check-label::after {
    content: " (from role)";
    font-size: 0.75rem;
    color: #6c757d;
    margin-left: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store role permissions, direct permissions, and current role ID
    const rolePermissions = @json($rolePermissions);
    const directPermissions = @json($directPermissions);
    let initialRoleId = @json($currentRoleId);
    
    // Function to update permission checkboxes based on selected role
    function updatePermissionCheckboxes() {
        const selectedRoleId = document.querySelector('input[name="role"]:checked')?.value;
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        
        if (!selectedRoleId) return;
        
        // Get permissions for the selected role
        const permissions = rolePermissions[selectedRoleId] || [];
        
        // Reset all permissions
        permissionCheckboxes.forEach(checkbox => {
            const permissionId = parseInt(checkbox.dataset.permissionId);
            const checkDiv = checkbox.closest('.permission-check');
            
            // First clear all states
            checkDiv.classList.remove('from-role', 'disabled');
            checkbox.disabled = false;
            
            // If changing from initial role, reset checkboxes
            if (selectedRoleId != initialRoleId) {
                // Only check if it's a direct permission
                checkbox.checked = directPermissions.includes(permissionId);
            }
            
            // If this permission comes with the selected role
            if (permissions.includes(permissionId)) {
                checkbox.checked = true;
                checkbox.disabled = true;
                checkDiv.classList.add('from-role', 'disabled');
            }
        });
    }
    
    // Initial update
    updatePermissionCheckboxes();
    
    // Listen for role changes
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', updatePermissionCheckboxes);
    });
});
</script>
@endsection
