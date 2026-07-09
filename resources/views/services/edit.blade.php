@extends('layouts.dashboard')

@section('title', 'Edit Service - ' . config('app.name'))
@section('page_title', 'Edit Service')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('services.update', $service) }}">
        @method('PUT')
        @include('services._form')
    </form>
</div>
@endsection
