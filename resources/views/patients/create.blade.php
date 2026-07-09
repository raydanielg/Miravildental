@extends('layouts.dashboard')

@section('title', 'Register Patient - ' . config('app.name'))
@section('page_title', 'Register Patient')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-sm font-semibold text-gray-900 mb-4">Patient Information</h3>
    <form method="POST" action="{{ route('patients.store') }}">
        @include('patients._form')
    </form>
</div>
@endsection
