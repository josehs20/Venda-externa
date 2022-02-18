@extends('layouts.app')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@section('content')
@include('componentes.navbar')
<form name="formteste">
    @csrf
      <input id="search" type="text" placeholder="nome" id="nome">  
   </form>
   <label id="resultado">

   </label>
@endsection


