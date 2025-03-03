@extends('layouts.master')
@section('title', 'Market Bill')
@section('content')
<table class="table">
    <tr>
        <th>Item</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
    </tr>
    @php
        $overallTotal = 0;
    @endphp
    @foreach ($bill as $item)
        @php
            $total = $item['Quantity'] * $item['Price'];
            $overallTotal += $total;
        @endphp
        <tr>
            <td>{{ $item['item'] }}</td>
            <td>{{ $item['Quantity'] }}</td>
            <td>{{ $item['Price'] }}</td>
            <td>{{ $total }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3"><strong>Overall Total</strong></td>
        <td><strong>{{ $overallTotal }}</strong></td>
    </tr>
</table>
@endsection