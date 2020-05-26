@extends('layouts.app')

@section('content')

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark  fixed-top justify-content-between align-items-center">
        <button class="navbar-toggler sideMenuToggler mr-3 bg-secondary float-left" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="{{url('/admin-panel')}}">WeSports<span class="text-danger">Admin</span></a>
        <a href="{{url('/')}}" class="btn btn-danger float-right"><i class="fas fa-sign-out-alt"></i></a>
    </nav>

    <div class="wrapper d-flex ">
        {{--Static no data--}}
        <x-admin-side-menu/>
        {{--Dynamic--}}
        @include('components.admin-content')
    </div>



@endsection

@section('scripts')
    <script src="{{asset('js/admin-panel.js')}}">

    </script>
@endsection
