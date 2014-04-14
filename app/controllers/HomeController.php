<?php

class HomeController extends BaseController {

	private function checkRegistered($mail){

		$ans=DB::select("select * from users where mail=?",array($mail));
		return $ans;

	}

	private function testInput($data){

       	$data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
    	return $data;

 	}

 	private function checkIfAvailable($email){

 		$domain=explode("@",$email)[1];
 		$url = $domain;
    		$ch = curl_init($url);
	    	curl_setopt($ch, CURLOPT_NOBODY, true);
	    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    	curl_exec($ch);
	    	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    	curl_close($ch);
	    	if (200==$retcode) {
	        	return true;
	    	} else {
	        return false;
	    	}

 	}

	public function login(){

		if(Session::token()!==Input::get('_token')){
            Session::put('login_error',"<script>alert('Invalid Login Attempt!');</script>");
            return Redirect::to('/');
        }

		$mail=Input::get('useremail');

		if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
			Session::put('login_error',"<script>alert('Invalid E-Mail ID!');</script>");
			return Redirect::to('/');
		}

		if(strlen($mail)<10){
			Session::put('login_error',"<script>alert('E-Mail ID Given is too short to exist!');</script>");
			return Redirect::to('/');
		}

		if(count(self::checkRegistered($mail))==1){
			Session::put('user',$mail);
			return Redirect::to('/home');
		}else{
			Session::put('login_error',"<script>alert('Mail ID not registered!');</script>");
			return Redirect::to('/');
		}
		
	}


	public function reguser(){

		if(Session::token()!==Input::get('_token')){
            echo "<script>alert('Invalid Registration Attempt!');</script>";
            return Redirect::to('/');
        }

        $mail=Input::get('regemail');
		
		if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
			Session::put('reg_error',"<script>alert('Invalid E-Mail ID!');</script>");
			return Redirect::to('/');
		}
		
		if(strlen($mail)<10){
			Session::put('reg_error',"<script>alert('E-Mail ID Given is too short to exist!');</script>");
			return Redirect::to('/');
		}
		
		if(count(self::checkRegistered($mail))>0){
			Session::put('reg_error',"<script>alert('User Already Registered!');</script>");
			return Redirect::to('/');
		}

		if(!self::checkIfAvailable($mail)){
			Session::put('reg_error',"<script>alert('Email is on invalid domain!');</script>");
			return Redirect::to('/');	
		}

		DB::insert('insert into users values(?)',array($mail));
		Session::put('user',$mail);

		return Redirect::to('/home');

	}

	public function logout(){

		Session::flush();
		return Redirect::to('/');
		
	}

	public function addNewTask(){

		if(Session::token()!==Input::get('_token')){
            echo "<script>alert('Invalid Attempt!');</script>";
            return Redirect::to('/');
        }

        if(Session::has('user')){
        	$taskname=Input::get('task_shortname');
			$taskdescription=Input::get('task_description');
			$taskdeadline=Input::get('task_deadline');
			$priority=intval(Input::get('task_priority'));

			$taskname=self::testInput($taskname);
			$taskdescription=self::testInput($taskdescription);

			$errors=array();

			if(strlen($taskname)<6)
				$errors[]="Task Short Name should have atleast 6 Characters";
			if(strlen($taskname)>32)
				$errors[]="Task Short Name should have maximum of 32 Characters";
			if(strlen($taskdescription)<6)
				$errors[]="Task Description should have atleast 6 characters";
			if(strstr($taskname,"'")||preg_match('/"/',$taskname))
				$errors[]="Task Short Name cannot have single quotes or double quotes";
			if(($deadline=strtotime($taskdeadline))==false){
				$errors[]="Wrong Date Format!";
			}else{
				$timeforDB=gmdate('Y-m-d H:i:s',$deadline);
			}

			$mail=Session::get('user');


			$errors=array_filter($errors);
			if(empty($errors)){
				$values=array($mail,$taskname,$taskdescription,$timeforDB,0,$priority);
				DB::insert('insert into tasks values(?,?,?,?,?,?)',$values);
			}

			return Response::json($errors);
        }
	}

	public function deleteTask(){

		$taskname=Input::get('task_name');
		$deadline=Input::get('task_deadline');
		$user=Session::get('user');
		if(DB::delete('delete from tasks where mail=? and task_name=? and deadline=?',array($user,$taskname,$deadline))){
			$resp="<h4 style='color:green'>Deleted.Reload The Page to View Changes</h4>";
		}else{
			$resp="<h4 style='color:red'>Some Error has Occured!</h4>";
		}
		return Response::json(array($resp));
		
	}

	public function markAsDone(){

		$taskname=Input::get('task_name');
		$deadline=Input::get('task_deadline');
		$user=Session::get('user');
		if(DB::update("update tasks set status=1 where task_name=? and mail=? and deadline=?",array($taskname,$user,$deadline))){
			$resp="<h4 style='color:green'>Marked as Done.Reload The Page to view Changes</h4>";
		}else{
			$resp="<h4 style='color:red'>Some Error has Occured!</h4>";
		}
		return Response::json(array($resp));

	}
}
