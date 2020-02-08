@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">@lang('Home')</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('Sections')</li>
                </ol>
            </nav>
            <a role="button" href="{{ route('sections.create') }}" class="btn btn-primary float-right">@lang('Create')</a>
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
                    <th style="width:5%" scope="col">#</th>
                    <th style="width:15%" scope="col">@lang('Logo')</th>
                    <th style="width:35%" scope="col">@lang('Name, description')</th>
                    <th style="width:30%" scope="col">@lang('Users')</th>
                    <th style="width:15%"></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($sections as $section)
                    <tr>
                        <th scope="row">{{ $section->id }}</th>
                        <td>@if($section->logo)<img src="{{ asset("/public/logo/{$section->logo}") }}" class="mw-100" />@endif</td>
                        <td>
                            <div>{{ $section->name }}</div>
                            <div>{{ $section->description }}</div>
                        </td>
                        <td>
                            <b>@lang('Users'):</b>
                            <div>
                                <ol>
                                    @foreach ($section->users as $user)
                                        <li>{{ $user->name }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </td>
                        <td>
                            <div class="ml-1">
                                <a role="button" href="{{ route('sections.edit', $section->id) }}" class="btn btn-secondary">@lang('Edit')</a>
                            </div>
                            <div class="ml-1 mt-1">
                                <form action="{{ route('sections.destroy', $section->id)}}" method="post">
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
            {{ $sections->links() }}
        </div>
    </div>
</div>
@endsection
