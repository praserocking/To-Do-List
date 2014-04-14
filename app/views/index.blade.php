<!DOCTYPE html>
<head>
	<title> Manage To Do List | Login</title>
	<link rel="stylesheet" type="text/css" href={{ asset('css/bootstrap.min.css') }}>
	<link rel="stylesheet" type="text/css" href={{ asset('css/index.css') }}>
</head>
<body>
	<div class="container">
	<center><h2>To Do List Manager</h2></center>
		<div id="loginform" class="stitched shadow">
		<h3>Login here...</h3> 
				{{ Form::open(array('url'=>'login','method'=>'post','action'=>'HomeController@login'))}}
				{{ Form::macro('login_email',function(){
					return "<input type='email' name='useremail' class='form-control' placeholder='Enter your mail here...' /><br/>";
				})}}
				{{ Form::login_email()}}
				<center>{{ Form::submit('Login',array('class'=>'btn btn-success')) }}</center>
				{{ Form::close() }}
			<div>
				@if(Session::has('login_error'))
				{{ Session::get('login_error') }}
				{{ Session::forget('login_error') }}
				@endif
			</div>
		</div>
		<center><h3 id='or'> OR </h3></center>
		<div id="regform" class="stitched shadow">
		<h3>Register here...</h3>
				{{ Form::open(array('url'=>'reguser','method'=>'post','action'=>'HomeController@reguser'))}}
				{{ Form::macro('login_email',function(){
					return "<input type='email' name='regemail' class='form-control' placeholder='Enter your mail here...' /><br/>";
				})}}
				{{ Form::login_email()}}
				<center>{{ Form::submit('Register',array('class'=>'btn btn-success'))}}</center>
				{{ Form::close() }}
			<div>
				@if(Session::has('reg_error'))
				{{ Session::get('reg_error') }}
				{{ Session::forget('reg_error') }}
				@endif
			</div>
		</div>
	</div>
</body>
</html>