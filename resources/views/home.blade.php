@extends('layouts.app')

@section('title', 'Món Ngon — Giao Diện Chính')

@section('toast')
    @include('product.toast')
@endsection

@section('cart')
    @include('product.cart')
@endsection

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('header')
    @include('shared.header')
@endsection

@section('content')
    @include('product.hero')
    @include('product.grid')
    @if(!isset($selectedProduct))
        @include('product.promo')
    @endif
@endsection

@section('footer')
    @include('shared.footer')
@endsection