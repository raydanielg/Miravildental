@extends('layouts.dashboard')

@section('title', 'Add Service - ' . config('app.name'))
@section('page_title', 'Add Service')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('services.store') }}">
        @include('services._form')
    </form>
</div>
@endsection
