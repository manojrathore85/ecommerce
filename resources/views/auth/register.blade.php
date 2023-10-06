@extends('layout.nonauthapp')
@section('content')
<div class="card col-md-6 offset-md-3">
    
    <form method="POST" action="{{route('register')}}">
    @csrf
        <div class="card-header">
           
            <a href="/login" class="btn btn-primary float-end">Login</a>
            <h3>Login</h3>
        </div>
        <div class="card-body">
            @if (Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if (Session::has('fail'))
            <div class="alert alert-success">{{Session::get('fail')}}</div>
            @endif
            <div class="mb-3 row">
                <label for="name" class="col-sm-4 col-form-label">name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}">
                    <span class="text-danger">@error('name'){{$message}}@enderror</span>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="email" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" id="email" name="email"  value="{{old('email')}}">
                    <span class="text-danger">@error('email'){{$message}}@enderror</span>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="password" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" name="password">
                    <span class="text-danger">@error('password'){{$message}}@enderror</span>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="confirm_password" class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="text" name="confirm_password" id="confirm_password" class="form-control">
                    <span class="text-danger">@error('confirm_password'){{$message}} @enderror</span>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button class="btn btn-primary">Register</button>
        </div>
    </form>
</div>
@endsection