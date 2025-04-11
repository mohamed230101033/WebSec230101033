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
    <div class="alert alert-danger shadow-sm border-0 rounded-4 position-relative" style="z-index: 1030;">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-danger"></i>
            <div>
                <strong>Purchase Failed</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-header bg-primary bg-opacity-10 text-center py-4 border-0">
                    <h4 class="fw-light mb-0">Product Details</h4>
                </div>
                <div class="card-body p-0">
                    <div class="product-img-container bg-light p-4 text-center">
                        @if($product->photo && is_file(public_path('images/' . $product->photo)))
                            <img src="{{ asset('images/' . $product->photo) }}" class="img-fluid rounded-3 product-image" alt="{{ $product->name }}">
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
            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 border-0">
                    <h4 class="mb-0 fw-light"><i class="bi bi-credit-card me-2 text-primary"></i>Complete Your Purchase</h4>
                </div>
                <div class="card-body p-4">
                    <div class="card mb-4 border-primary border-opacity-25 shadow-sm rounded-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="bi bi-wallet2 text-primary fs-3" style="opacity: 0.8;"></i>
                                </div>
                                <div>
                                    <h5 class="fw-light mb-1">Your Current Balance</h5>
                                    <p class="display-6 fw-bold text-primary mb-0">${{ number_format(auth()->user()->credit, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('do_purchase', $product->id) }}" method="post" id="purchaseForm">
                        @csrf
                        <div class="mb-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary rounded-start-pill" id="decreaseQty"><i class="bi bi-dash"></i></button>
                                <input type="number" class="form-control text-center border-secondary-subtle" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}" required>
                                <button type="button" class="btn btn-outline-secondary rounded-end-pill" id="increaseQty"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        
                        <div class="card mb-4 bg-light border-0 rounded-4">
                            <div class="card-body p-4">
                                <h5 class="card-title fw-light mb-3">Order Summary</h5>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Product:</span>
                                    <span class="fw-medium">{{ $product->name }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Unit Price:</span>
                                    <span>${{ number_format($product->price, 2) }}</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Quantity:</span>
                                    <span><span id="displayQty">1</span> units</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Remaining Stock After Purchase:</span>
                                    <span class="badge bg-secondary rounded-pill" id="remainingStock">{{ $product->stock - 1 }} units</span>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Total Amount:</span>
                                    <span class="text-primary fs-5">$<span id="totalPrice">{{ number_format($product->price, 2) }}</span></span>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Balance After Purchase:</span>
                                    <span class="fs-5" id="remainingBalance">${{ number_format(auth()->user()->credit - $product->price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg py-3 rounded-pill shadow-sm" id="confirmButton">
                                <i class="bi bi-cart-check me-2"></i>Confirm Purchase
                            </button>
                            <a href="{{ route('products_list') }}" class="btn btn-outline-secondary rounded-pill py-2">
                                <i class="bi bi-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div class="modal fade" id="purchaseConfirmModal" tabindex="-1" aria-labelledby="purchaseConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 overflow-hidden">
      <div class="modal-header bg-primary bg-opacity-10 border-0">
        <h5 class="modal-title" id="purchaseConfirmModalLabel">Confirm Your Purchase</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <div class="mb-3 text-primary">
            <i class="bi bi-cart-check" style="font-size: 3rem;"></i>
          </div>
          <h4 class="mb-3">Are you sure you want to complete this purchase?</h4>
          <p class="mb-0">You are about to purchase <strong><span id="modalQuantity">1</span>x {{ $product->name }}</strong></p>
          <p class="mb-0">Total amount: <strong class="text-primary">$<span id="modalTotal">{{ number_format($product->price, 2) }}</span></strong></p>
        </div>
        
        <div class="border border-warning rounded-3 p-3 mb-0 shadow-sm" style="background-color: #fff3cd;">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="bi bi-exclamation-triangle-fill me-2 text-warning fs-4"></i>
            </div>
            <div class="flex-grow-1 ms-2">
              <p class="mb-0 fw-bold text-dark">Important: This amount will be deducted from your account balance immediately upon confirmation.</p>
              <p class="mb-0 small text-danger mt-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Please ensure you have sufficient funds as this action cannot be undone.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary rounded-pill" id="modalConfirmBtn">
          <i class="bi bi-cart-check me-2"></i>Complete Purchase
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Insufficient Funds Modal -->
<div class="modal fade" id="insufficientFundsModal" tabindex="-1" aria-labelledby="insufficientFundsModalLabel" aria-hidden="true" data-bs-backdrop="static" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 overflow-hidden border-danger">
      <div class="modal-header bg-danger bg-opacity-10 border-0">
        <h5 class="modal-title fw-bold" id="insufficientFundsModalLabel">Insufficient Funds</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center">
          <div class="mb-3 text-danger">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
          </div>
          <h4 class="mb-4">You don't have enough credit</h4>
          
          <div class="card border-0 bg-light mb-4 rounded-4">
            <div class="card-body p-3">
              <div class="row mb-2">
                <div class="col-7 text-start text-muted">Your current balance:</div>
                <div class="col-5 text-end fw-bold">${{ number_format(auth()->user()->credit, 2) }}</div>
              </div>
              <div class="row mb-2">
                <div class="col-7 text-start text-muted">Required amount:</div>
                <div class="col-5 text-end fw-bold text-danger">$<span id="requiredAmount">{{ number_format($product->price, 2) }}</span></div>
              </div>
              <div class="row border-top pt-2 mt-2">
                <div class="col-7 text-start fw-bold">Amount needed:</div>
                <div class="col-5 text-end fw-bold text-danger">$<span id="missingAmount">0.00</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 d-flex justify-content-center gap-2">
        <a href="{{ route('products_list') }}" class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-arrow-left me-2"></i>Browse Products
        </a>
        <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-2"></i>Close
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const totalPriceSpan = document.getElementById('totalPrice');
    const displayQty = document.getElementById('displayQty');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const confirmButton = document.getElementById('confirmButton');
    const purchaseForm = document.getElementById('purchaseForm');
    const remainingStockSpan = document.getElementById('remainingStock');
    const remainingBalanceSpan = document.getElementById('remainingBalance');
    const modalQuantity = document.getElementById('modalQuantity');
    const modalTotal = document.getElementById('modalTotal');
    const modalConfirmBtn = document.getElementById('modalConfirmBtn');
    const requiredAmountSpan = document.getElementById('requiredAmount');
    const missingAmountSpan = document.getElementById('missingAmount');
    
    const unitPrice = {{ $product->price }};
    const maxStock = {{ $product->stock }};
    const userCredit = {{ auth()->user()->credit }};
    
    // Bootstrap modal objects
    const purchaseConfirmModal = new bootstrap.Modal(document.getElementById('purchaseConfirmModal'));
    const insufficientFundsModal = new bootstrap.Modal(document.getElementById('insufficientFundsModal'));
    
    // Helper function to clear all modal-related elements
    function clearModalState() {
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        
        document.querySelectorAll('.modal').forEach(modal => {
            const bsInstance = bootstrap.Modal.getInstance(modal);
            if (bsInstance) {
                bsInstance.hide();
            }
        });
    }
    
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
    
    // Confirm button click handler
    confirmButton.addEventListener('click', function(e) {
        e.preventDefault(); // Always prevent default
        
        const quantity = parseInt(quantityInput.value) || 0;
        const total = quantity * unitPrice;
        
        // Clear any existing modal state
        clearModalState();
        
        // Check sufficient funds
        if (total > userCredit) {
            // Update and show insufficient funds modal
            requiredAmountSpan.textContent = formatNumber(total);
            missingAmountSpan.textContent = formatNumber(total - userCredit);
            
            // Show insufficient funds modal with slight delay for smooth transition
            setTimeout(() => insufficientFundsModal.show(), 100);
        } else {
            // Update confirmation modal values and show it
            modalQuantity.textContent = quantity;
            modalTotal.textContent = formatNumber(total);
            
            // Show purchase confirmation modal with slight delay for smooth transition
            setTimeout(() => purchaseConfirmModal.show(), 100);
        }
    });
    
    // Modal confirm button submits the form
    modalConfirmBtn.addEventListener('click', function() {
        purchaseForm.submit();
    });
    
    function updateDisplays() {
        const quantity = parseInt(quantityInput.value) || 0;
        const total = (quantity * unitPrice).toFixed(2);
        const remainingStock = maxStock - quantity;
        const remainingBalance = userCredit - (quantity * unitPrice);
        
        // Update all display elements
        displayQty.textContent = quantity;
        totalPriceSpan.textContent = formatNumber(total);
        remainingStockSpan.textContent = remainingStock + " units";
        remainingBalanceSpan.textContent = "$" + formatNumber(remainingBalance);
        
        // Update balance color based on sufficiency
        remainingBalanceSpan.classList.toggle('text-danger', remainingBalance < 0);
        remainingBalanceSpan.classList.toggle('text-success', remainingBalance >= 0);
        
        // Update button appearance based on fund availability
        const insufficientFunds = quantity * unitPrice > userCredit;
        confirmButton.classList.toggle('btn-danger', insufficientFunds);
        confirmButton.classList.toggle('btn-primary', !insufficientFunds);
        
        confirmButton.innerHTML = insufficientFunds 
            ? '<i class="bi bi-exclamation-triangle me-2"></i>Insufficient Funds'
            : '<i class="bi bi-cart-check me-2"></i>Confirm Purchase';
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
.rounded-4 {
    border-radius: 0.75rem !important;
}
.fw-light {
    font-weight: 300 !important;
}
.fw-medium {
    font-weight: 500 !important;
}
.border-secondary-subtle {
    border-color: #dee2e6 !important;
}
</style>
@endsection 