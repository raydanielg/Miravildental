@extends('layouts.dashboard')

@section('title', 'Add Treatment Record - ' . config('app.name'))
@section('page_title', 'Add Treatment Record')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('clinical-records.store') }}">
        @include('clinical-records._form')
    </form>
</div>
@endsection
