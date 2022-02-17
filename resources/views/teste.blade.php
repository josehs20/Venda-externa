@extends('layouts.app')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@section('content')

<form name="formteste">
    @csrf
      <input type="text" placeholder="nome" id="nome">
           
      <input class="form-control" type="number" id="quant" >
      <select class="form-select" id="desc_tipo">
          <option value="Porcentagem"><b>
                  <h4> % </h4>
          </option>
          <option value="Dinheiro"><b>
                  <h4> $ </h4>
          </option>
      </select>
           <button type="submit" >enviar</button>
   </form>
@endsection
<script src="{{ asset('js/viewhome.js') }}" defer></script>

