@extends('layouts.dashboard')

@section('title', 'Edit Staff - ' . config('app.name'))
@section('page_title', 'Edit Staff Member')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('staff.update', $staff) }}">
        @method('PUT')
        @include('staff._form')
    </form>
</div>
@endsection
