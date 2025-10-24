@extends('layouts.main')

@section('title', 'Permission Denied')

@section('content')
<div class="container mt-5 text-center">
    <h2 class="text-danger">Permission Denied</h2>
    <p>You don’t have permission to access this page or perform this action.</p>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Go Back</a>
</div>
@endsection