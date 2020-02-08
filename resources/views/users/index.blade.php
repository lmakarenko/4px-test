@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">@lang('Home')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('Users')</li>
                </ol>
            </nav>
            <a type="submit" class="btn btn-primary float-right" role="button" href="{{ route('users.create') }}">@lang('Create')</a>
        </div>
        <div class="card-body">
            <div class="col-sm-12">
                @if(session()->get('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('Name')</th>
                    <th scope="col">@lang('Email')</th>
                    <th scope="col">@lang('Registration date')</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <div class="ml-1">
                                <a class="btn btn-secondary" href="{{ route('users.edit', $user->id) }}" role="button">@lang('Edit')</a>
                            </div>
                            <div class="ml-1 mt-1">
                                <form action="{{ route('users.destroy', $user->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">@lang('Delete')</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
