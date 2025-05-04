@extends('layouts.master')
@section('title', auth()->user()->hasRole('Admin') ? 'All Customer Purchases' : 'My Purchases')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="bi bi-bag-heart me-2 text-primary"></i> {{ auth()->user()->hasRole('Admin') ? 'All Customer Purchases' : 'My Purchases' }}</h1>
        <a href="{{ route('products_list') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Back to Products
        </a>
    </div>

    @if(!auth()->user()->hasRole('Admin'))
    <div class="row mb-4">
        <div class="col-md-7 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-0">
                    <div class="bg-gradient-primary p-4 text-white">
                        <h3 class="fw-light mb-1">Available Balance</h3>
                        <div class="d-flex align-items-center">
                            <div class="display-4 fw-bold me-3">${{ number_format(auth()->user()->credit, 2) }}</div>
                            <i class="bi bi-wallet2 ms-auto" style="font-size: 3rem; opacity: 0.6;"></i>
                        </div>
                    </div>
                    <div class="p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            <p class="mb-0 small">Use your credit to purchase products from our store</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                <div class="card-body p-4">
                    <h3 class="fw-light mb-3">Purchase Summary</h3>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Total Purchases</span>
                                <span class="fs-4 fw-bold">{{ count($purchases) }}</span>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Total Spent</span>
                                <span class="fs-4 fw-bold text-primary">${{ number_format($purchases->sum('total_price'), 2) }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Items Purchased</span>
                                <span class="fs-4 fw-bold">{{ $purchases->sum('quantity') }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column">
                                <span class="text-muted small">Latest Purchase</span>
                                <span class="fs-6 fw-medium">{{ $purchases->count() > 0 ? ($purchases->sortByDesc('purchase_date')->first()->purchase_date ? $purchases->sortByDesc('purchase_date')->first()->purchase_date->format('M d, Y') : $purchases->sortByDesc('created_at')->first()->created_at->format('M d, Y')) : 'No purchases yet' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white p-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0 fw-light"><i class="bi bi-clock-history me-2 text-primary"></i>Purchase History</h3>
                @if(count($purchases) > 0)
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control rounded-pill" id="searchPurchases" placeholder="Search purchases...">
                        <span class="input-group-text bg-transparent border-0 position-absolute end-0"><i class="bi bi-search"></i></span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($purchases) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="purchasesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            @if(auth()->user()->hasRole('Admin'))
                            <th class="px-4 py-3">User ID</th>
                            <th class="px-4 py-3">Customer</th>
                            @endif
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Quantity</th>
                            <th class="px-4 py-3">Unit Price</th>
                            <th class="px-4 py-3">Total Price</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex flex-column">
                                    <span>{{ $purchase->purchase_date ? $purchase->purchase_date->format('M d, Y') : $purchase->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $purchase->purchase_date ? $purchase->purchase_date->format('h:i A') : $purchase->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            @if(auth()->user()->hasRole('Admin'))
                            <td class="px-4 py-3">{{ $purchase->user_id }}</td>
                            <td class="px-4 py-3">{{ $purchase->user->name ?? 'Unknown User' }}</td>
                            @endif
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    @if($purchase->product && $purchase->product->photo && is_file(public_path('images/' . $purchase->product->photo)))
                                    <div class="me-3">
                                        <img src="{{ secure_asset('images/' . $purchase->product->photo) }}" alt="{{ $purchase->product->name }}" class="rounded-3" style="width: 40px; height: 40px; object-fit: contain;">
                                    </div>
                                    @else
                                    <div class="me-3 bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-box text-muted"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="fw-medium">{{ $purchase->product->name ?? 'Unknown Product' }}</span>
                                        @if($purchase->product)
                                        <small class="text-muted d-block">{{ $purchase->product->code }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-secondary rounded-pill">{{ $purchase->quantity }}</span>
                            </td>
                            <td class="px-4 py-3">${{ number_format($purchase->price > 0 ? $purchase->price : ($purchase->total_price / $purchase->quantity), 2) }}</td>
                            <td class="px-4 py-3 fw-bold text-primary">${{ number_format($purchase->total_price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Completed
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-bag text-muted" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-light mb-2">No Purchases Yet</h3>
                <p class="text-muted mb-4">
                    @if(auth()->user()->hasRole('Admin'))
                        No purchases have been made in the system yet.
                    @else
                        You haven't made any purchases yet.
                    @endif
                </p>
                @if(!auth()->user()->hasRole('Admin'))
                <a href="{{ route('products_list') }}" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-shop me-2"></i>Browse Products
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 500;
        color: #555;
    }
    
    .rounded-4 {
        border-radius: 0.75rem !important;
    }
    
    .fw-medium {
        font-weight: 500 !important;
    }
    
    .fw-light {
        font-weight: 300 !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }
    
    #searchPurchases {
        padding-right: 35px;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    
    #searchPurchases:focus {
        background-color: #fff;
        box-shadow: none;
        border-color: #dee2e6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchPurchases');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchVal = this.value.toLowerCase();
                const rows = document.querySelectorAll('#purchasesTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchVal)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection 