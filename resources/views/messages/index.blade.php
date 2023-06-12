@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <message-create></message-create>
            </div>
            <div class="col-md-8">
                <message-list :user_id='{{ Auth::user()->id }}'></message-list>
            </div>
        </div>
    </div>
@endsection
