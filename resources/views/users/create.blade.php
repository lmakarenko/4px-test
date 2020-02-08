@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">@lang('Home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">@lang('Users')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('Create a new user')</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br />
            @endif
            <form method="post" action="{{ route('users.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">@lang('Name'):</label>
                    <input type="text" class="form-control" name="name"/>
                </div>
                <div class="form-group">
                    <label for="email">@lang('Email'):</label>
                    <input type="text" class="form-control" name="email"/>
                </div>
                <div class="form-group">
                    <label for="password">@lang('Password'):</label>
                    <input type="password" class="form-control" name="password"/>
                </div>
                <button type="submit" class="btn btn-primary float-right">@lang('Store')</button>
            </form>
        </div>
    </div>
</div>
@endsection
