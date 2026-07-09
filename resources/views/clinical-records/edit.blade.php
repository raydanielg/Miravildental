@extends('layouts.dashboard')

@section('title', 'Edit Treatment Record - ' . config('app.name'))
@section('page_title', 'Edit Treatment Record')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('clinical-records.update', $clinicalRecord) }}">
        @method('PUT')
        @include('clinical-records._form')
    </form>
</div>
@endsection
