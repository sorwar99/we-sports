@extends('layouts.app')
@section('content')

<!--
    TEST PARA VER SI TENEMOS EL TOKEN ASIGNADO
                NO BORRAR PORFA
-->
<a href="{{url('test')}}">TEST PARA VER TOKEN</a>
<br>
<a href="{{url('events/create')}}">CREAR EVENTO</a>
<div class="">
    <h4>Deportes:</h4>
    <ul>
        @foreach($sports as $sport)
            <li>{{$sport['name']}}</li>
        @endforeach
    </ul>
</div>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div id="eventsField" class="row justify-content-center"></div>

        </div>
        <div class="col-12 text-center">
            <a class="btn btn-outline-secondary p-2 p-md-3 text-center" href="{{route('events.all')}}">Ver mas
                eventos</a>
        </div>
    </div>
</div>

    

@endsection

@section('scripts')
    <script src="{{asset('js/get-events.js')}}">
    </script>
@endsection
