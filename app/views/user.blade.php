
<!DOCTYPE html>
<head>
	<title> Manage To Do List | Home</title>
	<link rel="stylesheet" type="text/css" href={{ asset('css/bootstrap.min.css') }}>
	<link rel="stylesheet" type="text/css" href={{ asset('css/user.css') }}>
	<script type="text/javascript" src={{ asset('js/jquery.js') }}></script>
	<script type="text/javascript" src={{ asset('js/user.js') }}></script>
</head>
<body>
<h4 id='head'>Your Mail ID: {{ Session::get('user') }} </h4>
<div id="loading"><img src={{ asset('loading.gif') }} /></div>
<div id="info"></div>
<div id="logout">
	{{ Form::open(array('url'=>'home/logout','method'=>'post','action'=>'HomeController@logout'))}}
	{{ Form::submit('Logout',array('class'=>'btn btn-danger'))}}
	{{ Form::close() }}
</div>

<div id="addnewtask" class="stitched shadow">
<div id='error'></div>
	{{ Form::open(array('route'=>'/home/addnewtask.addNewTask','method'=>'post','id'=>'newTaskForm'))}}
		<div class="form-group">
			{{ Form::label('task_shortname',"Enter Task's Short Name") }}
			{{ Form::text('task_shortname',"",array('class'=>'form-control','id'=>'shortname')) }}
		</div>
		<div class="form-group">
			{{ Form::label('task_description',"Enter Task's Description") }}
			{{ Form::macro('task_description',function(){
				return "<textarea rows='5' cols='20' name='descrip' class='form-control' id='description'></textarea>";
			})}}
			{{ Form::task_description()}}
		</div>
		<div class="form-group">
			{{ Form::label('task_deadline',"Enter Task's Deadline") }}
			{{ Form::macro('task_deadline',function(){
				return "<input type='datetime-local' name='task_deadline' class='form-control' id='deadline'>";
			})}}
			{{ Form::task_deadline()}}
			datetime-local, works on chrome. In firefox, enter any valid date format or phrases like 'today','tomorrow','next monday' etc
		</div>
		<div class="form-group">
			{{ Form::label('task_priority',"Enter Task's Priority") }}
			{{ Form::select('task_priority',
							array('3'=>'Critical',
								  '2'=>'Moderate',
								  '1'=>'Normal'),
								  '1',
								  array('class'=>'form-control','id'=>'priority')) }}
		</div>
		<div class="form-group">
			<center>{{ Form::submit('Add this Task',array('class'=>'btn btn-success'))}}</center>
		</div>
	{{Form::close()}}
		{{ Form::open(array('route'=>'/home/deletetask.deleteTask','method'=>'post','id'=>'deleteForm'))}} 
			{{ Form::hidden('task_name','',array('id'=>'hidtaskname')) }}
			{{ Form::hidden('task_deadline','',array('id'=>'hidtaskdeadline')) }}
		{{ Form::close()}}
		{{ Form::open(array('route'=>'/home/markasdone.markAsDone','method'=>'post','id'=>'markForm'))}} 
			{{ Form::hidden('task_name','',array('id'=>'mrktaskname')) }}
			{{ Form::hidden('task_deadline','',array('id'=>'mrktaskdeadline')) }}
		{{ Form::close()}}
</div>
<div id="tasks" class="stitched shadow">
<table class="table">
	<tr>
		<td><b>Task Name</b></td>
		<td><b>Priority</b></td>
		<td><b>Deadline</b></td>
		<td><b>Status</b></td>
		<td><b>Delete?</b></td>
	</tr>
	@if($count==1)
		@foreach($tasks as $task)
		<tr>
			<td>{{ $task->task_name}}</td>
			<td>@if($task->priority==1)
					<span style="color:green">Normal</span>
				 @elseif($task->priority==2)
					<span style="color:#ff9900">Moderate</span>
				@else
					<span style="color:red">Critical</span>
				@endif
			</td>
			<td>{{ $task->deadline}}</td>
			<td>
			@if($task->status==0)
				<button onclick="markTask('{{$task->task_name}}','{{$task->deadline}}')" class="btn btn-success">Done?</button>
			@else
				Task Done
			@endif
			</td>
			<td> <button onclick="delTask('{{$task->task_name}}','{{$task->deadline}}')" class="btn btn-danger">X</button></td>
		</tr>
		<tr>
			<td colspan="5"><center><b>Description for ' {{$task->task_name}} ' : </b>{{ $task->task_description }}</center></td>
		</tr>
		@endforeach
	@else
		<center><h3>You have not added any tasks!</h3></center>
	@endif
</table>
</div>
</body>
<html>