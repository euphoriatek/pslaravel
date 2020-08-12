@extends('layouts.app')

@section('content')
<div class="container">
	<h1 class="text-center">Show Product</h1>
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="product_name">Product Name</label>
			<div class="form-control" id="product_name">{{ $product['name']['language'][0] }}</div>
		</div>
		<div class="form-group col-md-6">
			<label for="price">Price</label>
			<div class="form-control" id="price">{{ $product['price'] }}</div>
		</div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="reference">Reference</label>
			<div class="form-control" id="reference">{{ $product['reference'] }}</div>
		</div>
		<div class="form-group col-md-6">
			<label for="id_tax_rules_group">Tax Rule</label>
			<div class="form-control" id="id_tax_rules_group">{{ $tax_rules_group[$product['id_tax_rules_group']] }}</div>
		</div>
	</div>
	<div class="form-group">
		<label for="description">Product Description</label>
		<div class="form-control">{!! $product['description']['language'][0] !!}</div>
	</div>
	<div class="form-group">
		<label for="product_short_description">Product Short Description</label>
		<div class="form-control">{!! $product['description_short']['language'][0] !!}</div>
	</div>
</div>
@endsection
