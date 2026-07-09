@extends('layouts.dashboard')

@section('title', 'Edit Appointment - ' . config('app.name'))
@section('page_title', 'Edit Appointment')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('appointments.update', $appointment) }}">
        @method('PUT')
        @include('appointments._form')
    </form>
</div>
@endsection
