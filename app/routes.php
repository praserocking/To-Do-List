<?php

function compare($a,$b){
	if($a->status>$b->status)
		return 1;
	else if($a->status<$b->status)
		return -1;
	else{
		$at=strtotime($a->deadline);
		$bt=strtotime($b->deadline);
		if($at<$bt)
			return 1;
		else if($at>$bt)
			return -1;
		else{
			if($a->priority<$b->priority)
				return 1;
			else if($a->priority>$b->priority)
				return -1;
			else
				return 0;
		}
	}
}

Route::get('/', function()
{
	return View::make('index');
});

Route::get('/home', function()
{
	$ans=DB::select('select task_name,task_description,deadline,status,priority from tasks where mail=?',array(Session::get('user')));
	usort($ans,"compare");
	if(Session::has('user')){
		if(empty($ans))
			$tmp=0;
		else $tmp=1;
		return View::make('user')->with('tasks',$ans)->with('count',$tmp);
	}
	else
		return View::make('index');
});

Route::post('login','HomeController@login');
Route::post('reguser','HomeController@reguser');
Route::post('home/logout','HomeController@logout');
Route::post( '/home/addnewtask', array(
    'as' => '/home/addnewtask.addNewTask',
    'uses' => 'HomeController@addNewTask'
));
Route::post( '/home/deletetask', array(
    'as' => '/home/deletetask.deleteTask',
    'uses' => 'HomeController@deleteTask'
));
Route::post( '/home/markasdone', array(
    'as' => '/home/markasdone.markAsDone',
    'uses' => 'HomeController@markAsDone'
));