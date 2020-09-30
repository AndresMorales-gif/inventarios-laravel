@extends('layouts.app')

@section('content')
	<div class="content-products">
		<div class="add-products">
			<a href="{{ route('newProduct') }}"><button>Agregar productos</button></a>
		</div>
		<div class="content-title">
			<h3>Mis productos</h3>
		</div>
		<list-product-component></list-product-component>
	</div>
@endsection