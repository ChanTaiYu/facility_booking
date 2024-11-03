<?php

	include 'dbcon.php';
	session_start();


	switch ($_POST['action']) {
    	case "login":
		    login();
		    break;
		case "register":
		    register();
		    break;
		default:
		    echo "<script>alert('No function found!'); window.location.href='index.php';</script>";
	}

	function login(){

		global $conn;
		
      	$pass = $_POST['password'];
		$role = $_POST['role'];


		if($role == 'student'){

			$student_id = $_POST['student_id'];
			$sql = "SELECT * FROM student where student_id = '$student_id'";

		}else {

			$email = $_POST['email'];
			$sql = "SELECT * FROM admin where email = '$email'";

		}
	
	    $result = mysqli_query($conn, $sql);
			

		if(mysqli_num_rows($result) > 0){

		
				$row = mysqli_fetch_assoc($result);

				if($role == 'student'){

					$pwDb = $row['password'];
					$sid = $row['student_id'];
					$semail= $row['email'];

				}else{

					$pwDb = $row['password'];
					$sid = $row['id'];
					$semail= $row['email'];

				}

				// check password
				if($pwDb == $pass){
	
					$_SESSION['uid'] = $sid;
					$_SESSION['email'] = $semail;
					$_SESSION['role'] = $role;

					echo '<script>alert("Welcome Back."); 
						  window.location.href = "dashboard.php";

					</script>';
											  

				}else{

				    $err = "Invalid Credential.";
			   		$msg = $err;
		      		$status = "danger";
		      		returnMsg($msg, $status);
			   		if($role == 'student'){

						$url = 'index.php';

					}else{

						$url = 'admin-login.php';

					}
			   		header('location:' . $url);

				}

		}else{


			$err = "Invalid Credential.";
	   		$msg = $err;
      		$status = "danger";
      		returnMsg($msg, $status);
			if($role == 'student'){

				$url = 'index.php';

			}else{

				$url = 'admin-login.php';

			}
	   		header('location:' . $url);

		}


	}


	function register(){

		global $conn;
		$name =  mysqli_real_escape_string($conn, $_POST['name']);
        $email = $_POST['email'];
        $student_id =  mysqli_real_escape_string($conn, $_POST['student_id']);
        $password = $_POST['password'];
   
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $current = date('Y-m-d H:i:s'); //Returns IST

        $sql = "SELECT * FROM student where student_id = '$student_id'";
	    $result = mysqli_query($conn, $sql);

	    if (mysqli_num_rows($result) > 0) {

	          $msg = "This student is existed.";
      		  $status = "danger";
      		  returnMsg($msg, $status);
	   		  header('location: register.php');

	    }else{
            $sql = "INSERT INTO student (email, name, password, student_id) VALUES ('$email', '$name', '$password', '$student_id')";

            $query_run = mysqli_query($conn, $sql);
           
            if ($query_run) {

	      		$msg = "Successfully create new account! Login Now.";
	      		$status = "success";
	      		returnMsg($msg, $status);
	   			header('location: index.php');
	        
	      	} else {
		        $msg = "Failed to create new account";
	      		$status = "danger";
	      		returnMsg($msg, $status);
	   			header('location: register.php');
	      	}
	    }

	}



    function returnMsg($msgR, $statusR){

	   	$_SESSION['msg'] = $msgR;
	    $_SESSION['msg_status'] = $statusR;
	   
	}


	function returnMsgE($msgE, $statusE){

	   	$_SESSION['msgE'] = $msgE;
	    $_SESSION['msg_statusE'] = $statusE;
	   
	}



?>