
@if(session('success'))
    <div class="alert alert-success alert-dismissible mt-3">
        <button class="close" data-dismiss="alert" aria-label="close"></button>
        {{session('success')}}
        {{ session()->forget('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible mt-3">
        <button class="close" data-dismiss="alert" aria-label="close"></button>
        {{session('error')}}
        {{ session()->forget('error') }}
    </div>
@endif
