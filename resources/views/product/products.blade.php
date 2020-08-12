@extends('layouts.app')

@section('content')
<div class="container">
	<h1 class="text-center">Producut List</h1>
	@if (session('success'))
	    <div class="alert alert-success">
	        {{ session('success') }}
	    </div>
	@endif
    <table class="table table-dark table-striped">
    	<thead>
    		<tr>
    			<th>Id</th>
    			<th>Name</th>
    			<th>Action</th>
    		</tr>
    	</thead>
    	@if($resources)
    		<tbody>
    			@foreach($resources as $resource)
		    		<tr>
		    			<td>
		    				{{$resource->id}}
		    			</td>
		    			<td>
		    				{{$resource->name->language[0]}}
		    			</td>
	    				<td>
	    					<form action="{{ route('product.destroy', $resource->id) }}" method="POST">
	    						@method('delete')
	    						@csrf
			    				<a href="{{ route('product.show',$resource->id) }}" class="btn btn-info">
			    					View Product
			    				</a>
	    						<a href="{{ route('product.edit', $resource->id) }}" class="btn btn-primary">Edit</a>
	    						<button class="btn btn-danger">Delete</button>
	    					</form>
	    				</td>
		    		</tr>
		    	@endforeach
		    	<nav aria-label="Page navigation example">
		    		<ul class="pagination">
		    			<?php if ($page>0): ?>
		    				<li class="page-item"><a class="page-link" href="{{ route('product.index','page='.($page-$limit)) }}">Previous</a></li>
		    			<?php endif ?>

		    			<?php if ($page<=($limit+$page) && $count > ($limit+$page)): ?>
		    				<li class="page-item"><a class="page-link" href="{{ route('product.index','page='.($page+$limit)) }}">Next</a></li>
		    			<?php endif ?>
		    		</ul>
		    	</nav>
    		</tbody>
	    @endif
    </table>
</div>
@endsection
