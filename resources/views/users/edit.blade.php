@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">@lang('Home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">@lang('Users')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('Edit a user #:id', ['id' => $user->id])</li>
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
                </div>
                <br />
            @endif
            <form method="post" action="{{ route('users.update', $user->id) }}">
                @method('PATCH')
                @csrf
                <div class="form-group">
                    <label for="name">@lang('Name'):</label>
                    <input type="text" class="form-control" name="name" autocomplete="off" placeholder="{{ $user->name }}" value="{{ $user->name }}" />
                </div>

                <div class="form-group">
                    <label for="email">@lang('Email'):</label>
                    <input type="text" class="form-control" name="email" autocomplete="off" placeholder="{{ $user->email }}" value="{{ $user->email }}" />
                </div>
                <div class="form-group">
                    <label for="password">@lang('Password'):</label>
                    <input type="password" class="form-control" name="password" autocomplete="off" value="" />
                </div>
                <button type="submit" class="btn btn-primary float-right">@lang('Update')</button>
            </form>
        </div>
    </div>
</div>
@endsection
