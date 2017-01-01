@extends('layouts.app')
@section('content')
	<div class="container"> 	
		<div class="col-md-offset-1 col-md-10">
			<div class="row">
				<h1>Todo List</h1>
				<br>
                {!! Form::open(['method'=>'GET','url'=>'tasks']) !!}
                <div class="input-group custom-search-form col-md-offset-1 col-md-10">
                  <input type="text" name="search" class="form-control" placeholder="Search">
                  <span class="input-group-btn">
                    <input type="submit" class="btn btn-default-sm" value="Search">
                    <a href="./tasks?order=id" class="btn btn-default-sm">Id</a>
                    <a href="./tasks?order=description" class="btn btn-default-sm">Description</a>
                    <a href="./tasks?order=category" class="btn btn-default-sm">Category</a>
                    <a href="./tasks?order=due" class="btn btn-default-sm">Due</a>
                    <a href="./tasks?lookat=tomorrow" class="btn btn-default-sm">Tomorrow</a>
                    <a href="./tasks?lookat=next7days" class="btn btn-default-sm">Next 7 Days</a>
                  </span>
                </div>
                {!! Form::close() !!}
			</div>

			<br>

			<!-- display past due -->

			@if (Session::has('FATAL'))

			<div class="alert alert-danger">
				<strong>PAST DUE: </strong>{{  Session::get('FATAL') }}
			</div>

			@endif

			<!-- display warning -->

			@if (Session::has('WARNING'))

			<div class="alert alert-warning">
				<strong>WARNING: </strong>{{  Session::get('WARNING') }}
			</div>

			@endif

			<!-- display success -->

			@if (Session::has('success'))

			<div class="alert alert-success alert-dismissible">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<strong>Success:</strong>{{  Session::get('success') }}
			</div>

			@endif

			<!-- display error -->

			@if (count($errors) > 0)
				<div class="alert alert-danger alert-dismissible">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<strong>Error:</strong>
					<ul>
						@foreach($errors->all() as $error)
							<li>{{  $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif			

			<div class="row">
				<form action=" {{  route('tasks.store') }}" method="POST">
				{{ csrf_field() }}
			        <div class="col-md-6">
			          <input type="text" name='description' class='form-control'>
			        </div>

			        <div class="col-md-2">
			        	<select class="form-control" name="category">
			        		  <option value="">Category</option>
			        		  <option value="Work">Work</option>
							  <option value="Family">Family</option>
							  <option value="Birthday">Birthday</option>
							  <option value="Errands">Errands</option>
							  <option value="Shopping">Shopping</option>
			        	</select>
			        </div>

			        <div class="col-md-2">
			        	<input type="date" name="duedate" class="form-control">
			        </div>

			        <input type="hidden" name="id" value="{{  Auth::user()->id }}">

			        <div class="col-md-2">
			          <input type="submit" class='btn btn-primary btn-block' value='Add Task'>
			        </div>
				</form>
			</div>

			<!-- display tasks about this user -->

			<div class="row">
			@if (count($userTasks) > 0)
				<table class="table table-hover">
					<thead>
						<th>Task #</th>
						<th>Description</th>
						<th>Category</th>
						<th>Due</th>
						<th>Edit</th>
						<th>Delete</th>
					</thead>
					<tbody>
						@foreach ($userTasks as $userTask)
							<p hidden="">{{$strdate = strtotime("+1 day", strtotime($userTask->due))}}
							{{$dueNewFormat = date('Ymd', strtotime($userTask->due))}}
							{{$dateNewFormat = date('Ymd', $strdate)}}</p>
							<tr>
								<th>{{  $userTask->id }}</th>
								<th><a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{  $userTask->description  }}&dates={{ $dueNewFormat }}/{{ $dateNewFormat }}">{{  $userTask->description }}</a></th>
								<th>{{  $userTask->category }}</th>
								<th>{{  $userTask->due }}</th>
								<th><a href="{{ route('tasks.edit', ['tasks'=>$userTask->id]) }}" class='btn btn-default'><i class="icon-edit"></i></a></th>
								<th>
					                <form action="{{ route('tasks.destroy', ['tasks'=>$userTask->id]) }}" method='POST'>
					                  {{ csrf_field() }}
					                  <input type="hidden" name='_method' value='DELETE'>

					                  <button type="submit" class='btn btn-danger'><i class="icon-trash"></i></button>
<!-- 					                  <input type="submit" class='btn btn-danger' value='Delete'> -->
					                </form>									
								</th>
							</tr>
						@endforeach
					</tbody>
				</table>

			@endif
			</div>

		</div>
	</div>
@endsection