@extends('layouts.app')
@section('content')

	<div class="container"> 	
		<div class="col-md-offset-1 col-md-10">
			<div class="row">
				<h1>Todo List</h1>
			</div>


			<!-- display success -->

			@if (Session::has('success'))

			<div class="alert alert-success">
				<strong>Success:</strong>{{  Session::get('success') }}
			</div>

			@endif

			<!-- display error -->

			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>Error:</strong>
					<ul>
						@foreach($errors->all() as $error)
							<li>{{  $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif	


			<div class="row">
				<form action="{{  route('tasks.update',[$task->id]) }}" method="POST">
				{{ csrf_field() }}
					<input type="hidden" name="_method" value="PUT">
			        <div class="col-md-6">
			          <input type="text" name='description' class='form-control' value="{{  $task->description }}">
			        </div>

			        <div class="col-md-2">
			        	<select class="form-control" name="category" value="{{  $task->category }}">
			        		  <option value="Work">Work</option>
							  <option value="Family">Family</option>
							  <option value="Birthday">Birthday</option>
			        	</select>
			        </div>

			        <div class="col-md-2">
			        	<input type="date" name="duedate" class="form-control" value="{{ $task->due }}">
			        </div>

			        <input type="hidden" name="id" value="{{  Auth::user()->id }}">

			        <div class="col-md-2">
			          <input type="submit" class='btn btn-primary btn-block' value='Change'>
			        </div>
				</form>
			</div>			

		</div>
	</div>



@endsection