@extends('layouts.master')
@section('title', 'Market Bill')
@section('content')
<div class="container">
        <h1 class="my-4">Supermarket Bill</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price ($)</th>
                    <th>Total ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bill['items'] as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 2) }}</td>
                        <td>{{ number_format($item['total'], 2) }}</td>
                    </tr>
                @endforeach
                <tr class="table-secondary">
                    <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                    <td><strong>{{ number_format($bill['subtotal'], 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end">Tax (10%)</td>
                    <td>{{ number_format($bill['tax'], 2) }}</td>
                </tr>
                <tr class="table-primary">
                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                    <td><strong>{{ number_format($bill['total'], 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection