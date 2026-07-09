@extends('layouts.dashboard')

@section('title', 'Add Room - ' . config('app.name'))
@section('page_title', 'Add Room / Chair')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <form method="POST" action="{{ route('rooms.store') }}">
        @include('rooms._form')
    </form>
</div>
@endsection
