@extends('layouts.app')

@section('title', 'Món Ngon — Quản Lý Sản Phẩm')

@section('toast')
    @include('product.toast')
@endsection

@section('sidebar')
    @include('shared.sidebar')
@endsection

@section('content')
    @include('admin.table')
@endsection

@section('report')
    @include('admin.report')
@endsection

@section('footer')
    @include('shared.footer')
@endsection

@section('modal')
    @include('admin.modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/manage.js') }}"></script>
@endpush