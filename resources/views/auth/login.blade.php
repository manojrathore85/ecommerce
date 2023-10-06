@extends('layout.nonauthapp')
@section('content')
<main class="form-signin">  
  <form method="POST" action="{{route('login')}}">
    @csrf
    <img class="mb-4" src="{{asset('bootstrap-logo.svg')}}" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
      <label for="email">Email address</label>
      <span class="text-danger">@error('email'){{$message}}@enderror</span>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
      <label for="password">Password</label>
      <span class="text-danger">@error('password'){{$message}}@enderror</span>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
    <p>To register with us click   <a href="/register">here</a> </p>
  
  </form>
</main>
<style> 
.form-signin {
    width: 100%;
    max-width: 330px;
    padding: 15px;
    margin: auto;
}
form{
text-align: center;
}
</style>
@endsection