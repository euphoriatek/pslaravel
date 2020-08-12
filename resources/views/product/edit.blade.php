@extends('layouts.app')
@section('content')
	<div class="container">
		<h1 class="text-center">Edit Product</h1>
		<form method="POST" action="{{ route('product.update', $product['id']) }}">
			@method('PATCH')
			@csrf
			<input type="hidden" name="id" value="{{$product['id']}}">
			<input type="hidden" name="id_manufacturer" value="1">
			<input type="hidden" name="id_supplier" value="1">
			<input type="hidden" name="id_category_default" value="4">
			<input type="hidden" name="new" value="">
			<input type="hidden" name="cache_default_attribute" value="0">
			<input type="hidden" name="id_default_image" value="">
			<input type="hidden" name="id_default_combination" value="">
			<input type="hidden" name="id_tax_rules_group" value="1">
			<input type="hidden" name="position_in_category" value="1">
			<input type="hidden" name="manufacturer_name" value="Studio Design">
			<input type="hidden" name="quantity" value="0">
			<input type="hidden" name="type" value="simple">
			<input type="hidden" name="id_shop_default" value="1">
			<input type="hidden" name="reference" value="demo_1">
			<input type="hidden" name="supplier_reference" value="">
			<input type="hidden" name="location" value="">
			<input type="hidden" name="width" value="0.000000">
			<input type="hidden" name="height" value="0.000000">
			<input type="hidden" name="depth" value="0.000000">
			<input type="hidden" name="weight" value="0.000000">
			<input type="hidden" name="quantity_discount" value="0">
			<input type="hidden" name="ean13" value="">
			<input type="hidden" name="isbn" value="">
			<input type="hidden" name="upc" value="">
			<input type="hidden" name="cache_is_pack" value="0">
			<input type="hidden" name="cache_has_attachments" value="0">
			<input type="hidden" name="is_virtual" value="0">
			<input type="hidden" name="state" value="1">
			<input type="hidden" name="additional_delivery_times" value="0">
			<input type="hidden" name="delivery_in_stock" value="">
			<input type="hidden" name="delivery_out_stock" value="">
			<input type="hidden" name="on_sale" value="0">
			<input type="hidden" name="online_only" value="0">
			<input type="hidden" name="ecotax" value="0.000000">
			<input type="hidden" name="minimal_quantity" value="0">
			<input type="hidden" name="low_stock_threshold" value="0">
			<input type="hidden" name="low_stock_alert" value="0">
			<input type="hidden" name="wholesale_price" value="0.000000">
			<input type="hidden" name="unity" value="">
			<input type="hidden" name="unit_price_ratio" value="0.000000">
			<input type="hidden" name="additional_shipping_cost" value="0.00">
			<input type="hidden" name="customizable" value="0">
			<input type="hidden" name="hidden_fields" value="0">
			<input type="hidden" name="uploadable_files" value="0">
			<input type="hidden" name="active" value="1">
			<input type="hidden" name="redirect_type" value="">
			<input type="hidden" name="id_type_redirected" value="0">
			<input type="hidden" name="available_for_order" value="1">
			<input type="hidden" name="available_date" value="0000-00-00">
			<input type="hidden" name="show_condition" value="0">
			<input type="hidden" name="condition" value="new">
			<input type="hidden" name="show_price" value="1">
			<input type="hidden" name="indexed" value="1">
			<input type="hidden" name="visibility" value="both">
			<input type="hidden" name="advanced_stock_management" value="0">
			<input type="hidden" name="date_add" value="2020-08-12 13:23:45">
			<input type="hidden" name="date_upd" value="2020-08-12 13:28:54">
			<input type="hidden" name="pack_stock_type" value="3">
			<input type="hidden" name="meta_description" value="">
			<input type="hidden" name="meta_keywords" value="">
			<input type="hidden" name="meta_title" value="">
			<input type="hidden" name="link_rewrite" value="">
			<input type="hidden" name="available_now" value="">
			<input type="hidden" name="available_later" value="">
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="product_name">Product Name</label>
					<input type="product_name" name="name" class="form-control" value="{{ $product['name']['language'][0] }}" id="product_name">
					@error('name')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="price">Price</label>
					<input type="text" name="price" value="{{ $product['price'] }}" class="form-control" id="price">
					@error('price')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="reference">Reference</label>
					<input type="text" name="reference" value="{{ $product['reference'] }}" class="form-control" id="reference">
					@error('reference')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="id_tax_rules_group">Tax Rule</label>
					<select name="id_tax_rules_group" id="id_tax_rules_group" class="form-control">
						<option value="0" {{ $product['id_tax_rules_group']==0?'selected':'' }}>No tax</option>
						<option value="1" {{ $product['id_tax_rules_group']==1?'selected':'' }}>IN Reduced Rate (4%)</option>
						<option value="2" {{ $product['id_tax_rules_group']==2?'selected':'' }}>IN Standard Rate (12.5%)</option>
						<option value="3" {{ $product['id_tax_rules_group']==3?'selected':'' }}>IN Super Reduced Rate (1%)</option>
					</select>
					@error('id_tax_rules_group')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
			</div>
			<div class="form-group">
				<label for="description">Product Description</label>
				<textarea id="description" name="description" rows="5" class="form-control">{!! $product['description']['language'][0] !!}</textarea>
			</div>
			<div class="form-group">
				<label for="product_short_description">Product Short Description</label>
				<textarea id="product_short_description" name="description_short" class="form-control">{!! $product['description_short']['language'][0] !!}</textarea>
			</div>
			
			<button type="submit" class="btn btn-primary">Update</button>
		</form>
	</div>
	@push('js')
		<script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
		<script type="text/javascript">
			CKEDITOR.replace( 'description' );
			CKEDITOR.replace( 'description_short' );
		</script>
	@endpush
@endsection