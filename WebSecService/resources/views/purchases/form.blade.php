@extends('layouts.master')
@section('title', 'Purchase ' . $product->name)
@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('products_list') }}" class="text-decoration-none">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase {{ $product->name }}</li>
                </ol>
            </nav>
            <h1 class="fs-2 mb-0"><i class="bi bi-cart-check me-2 text-primary"></i>Purchase {{ $product->name }}</h1>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger shadow-sm border-0">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-0">
                    <div class="product-img-container bg-light rounded-top p-4 text-center">
                        @if($product->photo && is_file(public_path('images/' . $product->photo)))
                            <img src="{{ asset('images/' . $product->photo) }}" class="img-fluid rounded product-image" alt="{{ $product->name }}">
                        @else
                            <div class="placeholder-img d-flex align-items-center justify-content-center py-5">
                                <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h4 class="mb-2">{{ $product->name }}</h4>
                        <p class="text-muted small mb-3">{{ $product->model }}</p>
                        <p class="product-description mb-4">{{ $product->description }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Product Code:</span>
                            <span class="fw-bold">{{ $product->code }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Available Stock:</span>
                            <span class="fw-bold">{{ $product->stock }} units</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Unit Price:</span>
                            <span class="fw-bold text-primary fs-5">${{ number_format($product->price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0"><i class="bi bi-credit-card me-2 text-primary"></i>Complete Your Purchase</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div>
                                <p class="mb-1">Your Current Balance: <strong>${{ number_format(auth()->user()->credit, 2) }}</strong></p>
                                <p class="mb-0 small">Please confirm your purchase details below</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('do_purchase', $product->id) }}" method="post" id="purchaseForm">
                        @csrf
                        <div class="mb-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="decreaseQty"><i class="bi bi-dash"></i></button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required>
                                <button type="button" class="btn btn-outline-secondary" id="increaseQty"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        
                        <div class="card mb-4 bg-light border-0">
                            <div class="card-body">
                                <h5 class="card-title">Order Summary</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $product->name }} × <span id="displayQty">1</span></span>
                                    <span id="lineTotalDisplay">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total</span>
                                    <span class="text-primary fs-5">$<span id="totalPrice">{{ number_format($product->price, 2) }}</span></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="confirmButton">
                                <i class="bi bi-cart-check me-2"></i>Confirm Purchase
                            </button>
                            <a href="{{ route('products_list') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    const displayQty = document.getElementById('displayQty');
    const lineTotalDisplay = document.getElementById('lineTotalDisplay');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const confirmButton = document.getElementById('confirmButton');
    const purchaseForm = document.getElementById('purchaseForm');
    
    const unitPrice = {{ $product->price }};
    const maxStock = {{ $product->stock }};
    const userCredit = {{ auth()->user()->credit }};
    
    // Setup increment/decrement buttons
    decreaseBtn.addEventListener('click', function() {
        let currentVal = parseInt(quantityInput.value);
        if (currentVal > 1) {
            quantityInput.value = currentVal - 1;
            updateDisplays();
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        let currentVal = parseInt(quantityInput.value);
        if (currentVal < maxStock) {
            quantityInput.value = currentVal + 1;
            updateDisplays();
        }
    });
    
    // Update on direct input
    quantityInput.addEventListener('change', updateDisplays);
    quantityInput.addEventListener('keyup', updateDisplays);
    
    // Form submission with confirmation
    purchaseForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const quantity = parseInt(quantityInput.value) || 0;
        const total = quantity * unitPrice;
        
        // Check sufficient funds
        if (total > userCredit) {
            alert('You do not have enough credit for this purchase.');
            return;
        }
        
        if (confirm('Are you sure you want to complete this purchase?')) {
            this.submit();
        }
    });
    
    function updateDisplays() {
        const quantity = parseInt(quantityInput.value) || 0;
        const total = (quantity * unitPrice).toFixed(2);
        
        displayQty.textContent = quantity;
        totalPriceSpan.textContent = formatNumber(total);
        lineTotalDisplay.textContent = '$' + formatNumber(total);
        
        // Disable confirm button if insufficient funds
        if (quantity * unitPrice > userCredit) {
            confirmButton.classList.add('btn-danger');
            confirmButton.classList.remove('btn-primary');
            confirmButton.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Insufficient Funds';
        } else {
            confirmButton.classList.remove('btn-danger');
            confirmButton.classList.add('btn-primary');
            confirmButton.innerHTML = '<i class="bi bi-cart-check me-2"></i>Confirm Purchase';
        }
    }
    
    function formatNumber(num) {
        return parseFloat(num).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    // Initialize
    updateDisplays();
});
</script>

<style>
.product-img-container {
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.product-img-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}
.product-description {
    font-size: 0.9rem;
    color: #6c757d;
}
</style>
@endsection 