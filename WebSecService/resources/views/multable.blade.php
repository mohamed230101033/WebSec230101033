@extends('layouts.master')
@section('title', 'multiplication Table')
@section('content')
   
@php( $j = $number ) 
<div class="card m-4 col-sm-2">
<div class="card-header">{{$j}} Multiplication Table</div>
<div class="card-body">
<table>
@foreach (range(1, 10) as $i)
<tr><td>{{$i}} * {{$j}}</td><td> = {{ $i * $j }}</td></li>
@endforeach
</table>
</div>
</div>
@endsection
