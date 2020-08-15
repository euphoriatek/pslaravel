@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="text-center">Add Product</h1>
		<form method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
			@csrf
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="product_json_file">JSON File</label>
					<input type="file" name="json_file" class="form-control" id="product_json_file">
					@error('json_file')
					    <small class="text-danger">{{ $message }}</small>
					@enderror
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