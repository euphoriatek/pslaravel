@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="text-center">Add Product</h1>
		<form method="POST" action="{{ route('product.store') }}">
			@csrf
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="product_name">Product Name</label>
					<input type="text" name="name" class="form-control" value="{{ old('name') }}" id="product_name">
					@error('name')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="price">Price</label>
					<input type="text" name="price" class="form-control" value="{{ old('price') }}" id="price">
					@error('price')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="reference">Reference</label>
					<input type="text" name="reference" value="{{ old('reference') }}" class="form-control" id="reference">
					@error('reference')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group col-md-6">
					<label for="id_tax_rules_group">Tax Rule</label>
					<select name="id_tax_rules_group" id="id_tax_rules_group" class="form-control">
						<option value="0">No tax</option>
						<option value="1">IN Reduced Rate (4%)</option>
						<option value="2">IN Standard Rate (12.5%)</option>
						<option value="3">IN Super Reduced Rate (1%)</option>
					</select>
					@error('id_tax_rules_group')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
				</div>
				<div class="form-group">
					<label for="description">Product Description</label>
					<textarea id="description" name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
				</div>
				<div class="form-group">
					<label for="product_short_description">Product Short Description</label>
					<textarea id="product_short_description" name="description_short" class="form-control">{{ old('description_short') }}</textarea>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">Add</button>
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