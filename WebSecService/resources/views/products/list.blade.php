@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-shop me-2"></i> Products</h1>
        @can('add_products')
            <a href="{{ route('products_edit') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-plus-circle me-1"></i> Add Product
            </a>
        @endcan
    </div>

    <!-- Search & Filter Form -->
    <div class="card border-0 shadow-sm mb-4 rounded-4">
        <div class="card-body">
            <form action="{{ route('products_list') }}" method="get">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="keywords" placeholder="Search Products" value="{{ request()->keywords }}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" name="min_price" placeholder="Min Price" value="{{ request()->min_price }}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" name="max_price" placeholder="Max Price" value="{{ request()->max_price }}">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="order_by" class="form-select">
                            <option value="">Order By</option>
                            <option value="name" {{ request()->order_by == "name" ? "selected" : "" }}>Name</option>
                            <option value="price" {{ request()->order_by == "price" ? "selected" : "" }}>Price</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="order_direction" class="form-select">
                            <option value="ASC" {{ request()->order_direction != "DESC" ? "selected" : "" }}>Ascending</option>
                            <option value="DESC" {{ request()->order_direction == "DESC" ? "selected" : "" }}>Descending</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary rounded-pill me-2">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('products_list') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- To Allow Cross Site Scripting -->
<!-- @if(!empty(request()->input('keywords')))
    <div class="card mt-2">
        <div class="card-body">
            Search Results: <span>{!! request()->keywords !!}</span>
        </div>
    </div>
@endif -->


    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm product-card rounded-4 overflow-hidden">
                @if($product->hold)
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-secondary rounded-pill">
                        <i class="bi bi-eye-slash me-1"></i> On Hold
                    </span>
                </div>
                @endif
                
                @if($product->stock <= 0)
                <div class="position-absolute top-0 end-0 m-3 stock-badge">
                    <span class="badge bg-danger shadow-sm d-flex align-items-center">
                        <i class="bi bi-x-circle-fill me-1"></i> 
                        <span>Out of Stock</span>
                    </span>
                </div>
                @elseif($product->stock <= 3)
                <div class="position-absolute top-0 end-0 m-3 stock-badge">
                    <span class="badge bg-warning text-dark shadow-sm d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> 
                        <span>Only {{ $product->stock }} left!</span>
                    </span>
                </div>
                @elseif($product->stock <= 10)
                <div class="position-absolute top-0 end-0 m-3 stock-badge">
                    <span class="badge bg-info shadow-sm d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-1"></i> 
                        <span>Limited Stock: {{ $product->stock }}</span>
                    </span>
                </div>
                @else
                <div class="position-absolute top-0 end-0 m-3 stock-badge">
                    <span class="badge bg-success shadow-sm d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-1"></i> 
                        <span>In Stock</span>
                    </span>
                </div>
                @endif
                
                <div class="product-img-container">
                    @if($product->photo && is_file(public_path('images/' . $product->photo)))
                        <img src="{{ asset('images/' . $product->photo) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    @else
                        <div class="placeholder-img d-flex align-items-center justify-content-center h-100 w-100 bg-light">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <h6 class="text-muted mb-3">{{ $product->model }}</h6>
                    
                    <div class="product-info mb-3">
                        <div class="mb-2">
                            <span class="text-muted">Code:</span> 
                            <span class="fw-bold">{{ $product->code }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Price:</span> 
                            <span class="fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted">Stock:</span> 
                            <div class="d-flex align-items-center mt-1">
                                @if($product->stock <= 0)
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="bi bi-x-circle-fill me-1"></i> Out of stock
                                    </span>
                                @elseif($product->stock <= 3)
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        Only {{ $product->stock }} left!
                                    </span>
                                @elseif($product->stock <= 10)
                                    <span class="badge bg-info rounded-pill text-white">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Limited: {{ $product->stock }} units
                                    </span>
                                @else
                                    <span class="badge bg-success rounded-pill">
                                        {{ $product->stock }} in stock
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @can('manage_stock')
                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill shadow-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#updateStockModal{{ $product->id }}">
                                <i class="bi bi-box-seam me-1"></i> Update Stock
                            </button>
                        @endcan
                    </div>
                    
                    <p class="card-text product-description">{{ $product->description }}</p>
                </div>
                
                <div class="card-footer bg-white border-0 pt-0">
                    <div class="d-flex flex-wrap justify-content-between">
                        <div class="btn-group mb-2">
                            @can('edit_products')
                            <a href="{{ route('products_edit', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-pill me-1">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            @endcan
                            
                            @can('delete_products')
                            <a href="#" class="btn btn-sm btn-outline-danger rounded-pill" 
                               data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                <i class="bi bi-trash me-1"></i> Delete
                            </a>
                            
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true" data-bs-backdrop="static">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content rounded-4 overflow-hidden">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                                        <h4 class="mt-3">Are you sure?</h4>
                                        <p>Do you really want to delete <strong>{{ $product->name }}</strong>? This action cannot be undone.</p>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                    <a href="{{ route('products_delete', $product->id) }}" class="btn btn-danger rounded-pill">
                                        <i class="bi bi-trash me-1"></i> Delete Product
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endcan
                        </div>
                        
                        <div class="btn-group mb-2">
                            @can('hold_products')
                                @if($product->hold)
                                <a href="{{route('products_unhold', $product->id)}}" class="btn btn-sm btn-outline-success rounded-pill me-1">
                                    <i class="bi bi-eye me-1"></i> Unhold
                                </a>
                                @else
                                <a href="{{route('products_hold', $product->id)}}" class="btn btn-sm btn-outline-warning rounded-pill me-1">
                                    <i class="bi bi-eye-slash me-1"></i> Hold
                                </a>
                                @endif
                            @endcan
                            
                            @can('purchase_products')
                                @if(!$product->hold && $product->stock > 0 && !auth()->user()->hasRole(['Admin', 'Employee']))
                                <a href="{{ route('purchase_form', $product->id) }}" class="btn btn-sm btn-primary rounded-pill shadow-sm px-3">
                                    <i class="bi bi-cart-plus me-1"></i> Purchase
                                </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if(count($products) == 0)
    <div class="text-center py-5">
        <i class="bi bi-search fs-1 text-muted"></i>
        <h3 class="mt-3">No products found</h3>
        <p class="text-muted">Try adjusting your search criteria or check back later.</p>
    </div>
    @endif
</div>

<!-- Update Stock Modals -->
@foreach($products as $product)
@can('manage_stock')
<div class="modal fade" id="updateStockModal{{ $product->id }}" tabindex="-1" aria-labelledby="updateStockModalLabel{{ $product->id }}" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="updateStockModalLabel{{ $product->id }}">
          <i class="bi bi-box-seam me-2 text-primary"></i>Update Stock
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('products_update_stock', $product->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-4">
            <h6 class="mb-3">Manage inventory for this product</h6>
            <p class="text-muted">Product: <span class="fw-semibold text-dark">{{ $product->name }} ({{ $product->code }})</span></p>
          
            @if($product->photo && is_file(public_path('images/' . $product->photo)))
              <div class="text-center mb-3">
                <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail rounded" style="max-height: 100px;">
              </div>
            @endif
            
            <div class="d-flex align-items-center mt-2 mb-3">
              <div class="me-2">Current stock:</div>
              @if($product->stock <= 0)
                <span class="badge bg-danger">Out of stock</span>
              @elseif($product->stock <= 3)
                <span class="badge bg-warning text-dark">Low: {{ $product->stock }} units</span>
              @elseif($product->stock <= 10)
                <span class="badge bg-info text-white">Limited: {{ $product->stock }} units</span>
              @else
                <span class="badge bg-success">{{ $product->stock }} in stock</span>
              @endif
            </div>
          </div>
          
          <div class="mb-3">
            <label for="stock{{ $product->id }}" class="form-label">New Stock Quantity</label>
            <input type="number" class="form-control" id="stock{{ $product->id }}" name="stock" min="0" value="{{ $product->stock }}" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan
@endforeach

<style>
    .product-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.2s ease;
    }
    
    .product-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .product-description {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .product-img-container {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    
    .product-img-container img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .rounded-4 {
        border-radius: 0.75rem !important;
    }
    
    .rounded-3 {
        border-radius: 0.5rem !important;
    }
    
    .fw-medium {
        font-weight: 500 !important;
    }
    
    .fw-light {
        font-weight: 300 !important;
    }
    
    .stock-badge .badge {
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }
</style>
@endsection
