@extends('layout.nonauthapp')
<div class="container center">
    <div class="position-relative p-5 text-center text-muted bg-body border border-dashed rounded-5">

        <svg class="bi mt-5 mb-3" width="48" height="48">
            <use xlink:href="#check2-circle"></use>
        </svg>
        <h1 class="text-body-emphasis">Placeholder jumbotron</h1>
        <p class="col-lg-6 mx-auto mb-4">
            This faded back jumbotron is useful for placeholder content. It's also a great way to add a bit of context to a page or section when no content is available and to encourage visitors to take a specific action.
        </p>
        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @else
        <div class="alert alert-danger">
            Your Email is not varified yet to get the email vaification link
        </div>
        <a class="btn btn-primary" href="/email/resend/{{auth()->user()->id}}" role="button">Resend link for email varification»</a>
        @endif



    </div>
</div>
<style>
    .center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 10px;
    }
</style>