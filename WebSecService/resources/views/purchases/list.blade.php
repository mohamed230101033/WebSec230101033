@extends('layouts.master')
@section('title', auth()->user()->hasRole('Admin') ? 'All Customer Purchases' : 'My Purchases')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <h1><i class="bi bi-bag-check me-2"></i> {{ auth()->user()->hasRole('Admin') ? 'All Customer Purchases' : 'My Purchases' }}</h1>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if(!auth()->user()->hasRole('Admin'))
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Account Balance</h3>
                </div>
                <div class="card-body">
                    <p class="fs-4">Available Credit: <strong>${{ number_format(auth()->user()->credit, 2) }}</strong></p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header">
                    <h3>Purchase History</h3>
                </div>
                <div class="card-body">
                    @if(count($purchases) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    @if(auth()->user()->hasRole('Admin'))
                                    <th>User ID</th>
                                    <th>Customer</th>
                                    @endif
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('M d, Y h:i A') : $purchase->created_at->format('M d, Y h:i A') }}</td>
                                    @if(auth()->user()->hasRole('Admin'))
                                    <td>{{ $purchase->user_id }}</td>
                                    <td>{{ $purchase->user->name ?? 'Unknown User' }}</td>
                                    @endif
                                    <td>{{ $purchase->product->name ?? 'Unknown Product' }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>${{ number_format($purchase->price > 0 ? $purchase->price : ($purchase->total_price / $purchase->quantity), 2) }}</td>
                                    <td>${{ number_format($purchase->total_price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info">
                        @if(auth()->user()->hasRole('Admin'))
                            No purchases have been made yet.
                        @else
                            You haven't made any purchases yet. 
                            <a href="{{ route('products_list') }}" class="alert-link">Browse products</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <a href="{{ route('products_list') }}" class="btn btn-primary">Back to Products</a>
        </div>
    </div>
</div>
@endsection 