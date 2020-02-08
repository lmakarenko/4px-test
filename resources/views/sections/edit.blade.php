@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">@lang('Home')</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('sections.index') }}">@lang('Sections')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Edit a section #:id', ['id' => $section->id])</li>
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
                <form method="post" enctype="multipart/form-data" action="{{ route('sections.update', $section->id) }}">
                    @method('PATCH')
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang('Name'):</label>
                        <input type="text" class="form-control" name="name" value="{{ $section->name }}" />
                    </div>
                    <div class="form-group">
                        <label for="description">@lang('Description'):</label>
                        <textarea class="form-control" name="description">{{ $section->description }}</textarea>
                    </div>
                    <div class="mb-2">@lang('Logo')</div>
                    @if($section->logo)
                        <div class="pt-2 pb-2">
                            <img src="{{ asset("public/logo/$section->logo") }}" alt="{{ $section->name }} logo" title="{{ $section->name }} logo" class="img-thumbnail" />
                        </div>
                    @endif
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" name="logo" class="custom-file-input" id="logo" />
                            <label class="custom-file-label" for="logo">@lang('Browse')</label>
                        </div>
                    </div>
                    <h4 class="mb-2 pt-2">@lang('Users')</h4>
                    <ul class="list-group list-group-flush">
                        @foreach($users as $user)
                            <li class="list-group-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" @if($section->users->pluck('id')->contains($user->id)) checked @endif name="users[]" value="{{ $user->id }}" class="custom-control-input" id="check{{ $user->id }}" />
                                    <label class="custom-control-label" for="check{{ $user->id }}">{{ $user->name }}&nbsp;<a href="mailto:{{ $user->email }}">({{ $user->email }})</a></label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <br/>
                    <button type="submit" class="btn btn-primary float-right">@lang('Update')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
