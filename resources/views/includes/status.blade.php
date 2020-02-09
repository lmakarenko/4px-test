@if(session()->get('success'))
    <div class="alert alert-success">
        @lang(session()->get('success'))
    </div>
@elseif(session()->get('error'))
    <div class="alert alert-danger">
        @lang(session()->get('error'))
    </div>
@elseif($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>@lang($error)</div>
        @endforeach
    </div>
@endif
