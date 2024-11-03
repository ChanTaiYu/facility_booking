<?php
	 date_default_timezone_set("Asia/Kuala_Lumpur");
	include 'dbcon.php';
	include 'user-session.php';

    switch ($_POST['action']) {
    	case 'fetch-my-booking':
    		fetchMyBooking();
    		break;
    	case 'cancel-booking':
    		cancelBooking();
    		break;
    	case 'approve-booking':
    		approveBooking();
    		break;
    	case 'view-all-booking':
    		allBooking();
    		break;
    	case 'book-slot':
    		bookSlot();
    		break;
    	case "get-time-slot":
    		getTimeSlot();
    		break;
    	case "fetch-admins":
	        fetchAdmins();
	        break;
	    case "delete-admin":
	        deleteAdmin();
	        break;
    	case "create-admin":
    		createAdmin();
    		break;
    	case "update-admin":
    		updateAdmin();
    		break;
    	case "fetch-facility":
	        fetchFacility();
	        break;
	    case "delete-facility":
	        deleteFacility();
	        break;
    	case "create-facility":
		    createFacility();
		    break;
		case "update-facility":
		    updateFacility();
		    break;
		case "update-student-profile":
			updateStudentProfile();
			break;
		case "update-admin-profile":
			updateAdminProfile();
			break;
		default:
		    echo "<script>alert('No function found!'); window.location.href='dashboard.php';</script>";
	}

	function fetchMyBooking() {
	    global $conn;
	    $student_id = $_POST['student_id'];
	    $limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Records per page
	    $page = isset($_POST['page']) ? $_POST['page'] : 1;
	    $offset = ($page - 1) * $limit;

	    // Query to fetch booking and facility details
	    $sql = "SELECT * FROM booking 
	            INNER JOIN facility ON booking.facility_id = facility.facility_id 
	            WHERE booking.student_id = '$student_id' 
	            LIMIT $offset, $limit";
	    $result = mysqli_query($conn, $sql);
	    $data = [];

	    if ($result && mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            $data[] = $row;
	        }
	    }

	    // Query to get the total number of records
	    $count_sql = "SELECT COUNT(*) AS total FROM booking WHERE student_id = '$student_id'";
	    $count_result = mysqli_query($conn, $count_sql);
	    $total_records = mysqli_fetch_assoc($count_result)['total'];

	    $response = [
	        'status' => 'success',
	        'data' => $data,
	        'total_records' => $total_records,
	        'limit' => $limit,
	    ];

	    echo json_encode($response);
	}


	function cancelBooking(){

		global $conn;
		$booking_id = $_POST['id'];
		$sql = "UPDATE booking SET status='cancel'";
        $sql .= "WHERE booking_id = '$booking_id'";
	 
        if (mysqli_query($conn, $sql)) {
           	echo json_encode(array(
			    'msg' => 'Cancel Successfully!',
			    'status' => true
			));
        } else {
            echo json_encode(array(
			    'msg' => 'Failed to cancel!',
			    'status' => false
			));
        }
      	


	}

	function approveBooking(){
		global $conn;
		$booking_id = $_POST['id'];
		$sql = "UPDATE booking SET status='confirmed'";
        $sql .= ", booking_id = '$booking_id'";
	 
        if (mysqli_query($conn, $sql)) {
           	echo json_encode(array(
			    'msg' => 'Confirmed Successfully!',
			    'status' => true
			));
        } else {
            echo json_encode(array(
			    'msg' => 'Failed to confirm!',
			    'status' => false
			));
        }
	}

	function allBooking(){

		global $conn;
	    $limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Records per page
	    $page = isset($_POST['page']) ? $_POST['page'] : 1;
	    $offset = ($page - 1) * $limit;

	    $sql = "SELECT * FROM booking INNER JOIN facility ON booking.facility_id = facility.facility_id INNER JOIN student ON booking.student_id = student.student_id LIMIT $offset, $limit";
	    $result = mysqli_query($conn, $sql);
	    $data = [];

	    if ($result && mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            $data[] = $row;
	        }
	    }

	    // Get total number of records
	    $count_sql = "SELECT COUNT(*) AS total FROM booking";
	    $count_result = mysqli_query($conn, $count_sql);
	    $total_records = mysqli_fetch_assoc($count_result)['total'];

	    $response = [
	        'status' => 'success',
	        'data' => $data,
	        'total_records' => $total_records,
	        'limit' => $limit,
	    ];

	    echo json_encode($response);

	}

	function getTimeSlot() {
	    global $conn;
	    $facility_id = $_POST['facility_id'];
	    $date = $_POST['date'];
	    
	    // Define available slots from 9:00 to 17:00
	    $slots = [];
	    for ($hour = 9; $hour <= 16; $hour++) {
	        $slots[] = [
	            'time' => $hour . ':00',
	            'status' => 'available'
	        ];
	    }

	    // Query to check existing bookings for the facility on the selected date
	    $query = "SELECT start_time, end_time FROM booking 
	              WHERE facility_id = '$facility_id' 
	              AND booking_date = '$date' 
	              AND (status = 'confirmed' OR status = 'pending')";

	    $result = mysqli_query($conn, $query);

	    while ($row = mysqli_fetch_assoc($result)) {
	        $startHour = (int) date('H', strtotime($row['start_time']));
	        $endHour = (int) date('H', strtotime($row['end_time']));

	        // Update the slot status for each booked hour within start and end times
	        foreach ($slots as &$slot) {
	            $slotHour = (int) explode(':', $slot['time'])[0];
	            if ($slotHour >= $startHour && $slotHour < $endHour) {
	                $slot['status'] = 'booked';
	            }
	        }
	    }

	    echo json_encode($slots);
	}


	function bookSlot() {
	    global $conn;
	    $student_id = $_POST['student_id'];
	    $facility_id = $_POST['facility_id'];
	    $date = $_POST['date'];
	    $current = date('Y-m-d H:i:s');
	    $start_time = $_POST['start_time'] . ':00'; // Append seconds for TIME format
	    $end_time = $_POST['end_time'] . ':00';

	    // Get current date and time
	    $currentDate = date('Y-m-d');
	    $currentTime = date('H:i:s');

	    // Step 1: Validate if the slot is still available if the booking date is today and time has passed
	    if ($date == $currentDate && $currentTime > $end_time) {
    		echo json_encode(array(
		        'msg' => 'This time slot has already ended. Please select another slot.',
		        'status' => false
		    ));
		    return;
		}

	    // Step 2: Check for existing booking with pending or confirmed status
	    $checkSql = "SELECT * FROM booking
	                 WHERE facility_id = '$facility_id' 
	                   AND booking_date = '$date' 
	                   AND start_time = '$start_time' 
	                   AND (status = 'pending' OR status = 'confirmed')";

	    $checkResult = mysqli_query($conn, $checkSql);

	    if (mysqli_num_rows($checkResult) > 0) {
	        // If a booking exists, return an error message
	        echo json_encode(array(
			    'msg' => 'This time slot is already booked by others. Please select another slot.',
			    'status' => false
			));


	    } else {
	
	        $insertSql = "INSERT INTO booking (facility_id, booking_date, start_time, end_time, status, student_id, created_at)
	                      VALUES ('$facility_id', '$date', '$start_time', '$end_time', 'pending', '$student_id', '$current')";

	        if (mysqli_query($conn, $insertSql)) {
	           	echo json_encode(array(
				    'msg' => 'Booking Successfully!',
				    'status' => true
				));
	        } else {
	            echo json_encode(array(
				    'msg' => 'Error: ' . mysqli_error($conn),
				    'status' => false
				));
	        }
	    }
	}


	function fetchAdmins() {
	    global $conn;

	    $limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Records per page
	    $page = isset($_POST['page']) ? $_POST['page'] : 1;
	    $offset = ($page - 1) * $limit;

	    $sql = "SELECT * FROM admin LIMIT $offset, $limit";
	    $result = mysqli_query($conn, $sql);
	    $admins = [];

	    if ($result && mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            $admins[] = $row;
	        }
	    }

	    // Get total number of records
	    $count_sql = "SELECT COUNT(*) AS total FROM admin";
	    $count_result = mysqli_query($conn, $count_sql);
	    $total_records = mysqli_fetch_assoc($count_result)['total'];

	    $response = [
	        'status' => 'success',
	        'data' => $admins,
	        'total_records' => $total_records,
	        'limit' => $limit,
	    ];

	    echo json_encode($response);

	}

	function deleteAdmin() {
	    global $conn;
	    $id = $_POST['id'];

	    $sql = "DELETE FROM admin WHERE id = '$id'";
	    if (mysqli_query($conn, $sql)) {
	        echo json_encode(['status' => 'success', 'message' => 'Admin deleted successfully']);
	    } else {
	        echo json_encode(['status' => 'error', 'message' => 'Failed to delete admin']);
	    }
	}
	


	function createAdmin(){
  			
		global $conn;
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        // date_default_timezone_set("Asia/Kuala_Lumpur");
        $current = date('Y-m-d H:i:s'); //Returns IST

        $sql = "SELECT * FROM admin where email = '$email'";
	    $result = mysqli_query($conn, $sql);

	    if (mysqli_num_rows($result) > 0) {

	          $msg = "This email is existed.";
      		  $status = "danger";
      		  returnMsg($msg, $status);
	   		  header('location:new-admin.php');

	    }else{

	       

            $sql = "INSERT INTO admin (name, email, password) VALUES ('$name', '$email', '$password')";

            $query_run = mysqli_query($conn, $sql);
           
            if ($query_run) {

	      		$msg = "Successfully create new admin";
	      		$status = "success";
	      		returnMsg($msg, $status);
	   			header('location:new-admin.php');
	        
	      	} else {
		        $msg = "Failed to create new admin";
	      		$status = "danger";
	      		returnMsg($msg, $status);
	   			header('location: new-admin.php');
	      	}

	        

	    }

    }

    function updateAdmin(){
    	global $conn;
    	$id = $_POST['id'];
        $name = $_POST['name'];
        $password = $_POST['password'];
    	$sql = "UPDATE admin SET name='$name'";

      	if(isset($password) && !empty($password)){

      		$encrypPass =$password;
        	$sql .= ", password = '$password'";
	 

      	}

        $sql .= "WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {

      		$msg = "Successfully update admin";
      		$status = "success";
      		returnMsg($msg, $status);
   			header('location:edit-admin.php?eid='.$id);
        
      	} else {
	        $msg = "Failed to update admin";
      		$status = "danger";
      		returnMsg($msg, $status);
   			header('location: edit-admin.php?eid='.$id);
      	}
    }

    if(isset($_POST['create-patient'])){

        $name =  mysqli_real_escape_string($conn, $_POST['name']);
        $email = $_POST['email'];
        $address =  mysqli_real_escape_string($conn, $_POST['address']);
        $password = $_POST['password'];
        $mobile =  mysqli_real_escape_string($conn, $_POST['mobile']);
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
  		$postcode =  mysqli_real_escape_string($conn, $_POST['postcode']);

        // date_default_timezone_set("Asia/Kuala_Lumpur");
        $current = date('Y-m-d H:i:s'); //Returns IST

        $sql = "SELECT * FROM patient where patient_email = '$email'";
	    $result = mysqli_query($conn, $sql);

	    if (mysqli_num_rows($result) > 0) {

	          $msg = "This email is existed.";
      		  $status = "danger";
      		  returnMsg($msg, $status);
	   		  header('location:new-patient.php');

	    }else{

	       
	            $sql = "INSERT INTO patient (patient_email, patient_password, patient_name, patient_contact, patient_address, patient_gender, patient_dob, patient_postcode, patient_created) VALUES ('$email', '$password', '$name', '$mobile', '$address', '$gender', '$dob', '$postcode', '$current')";

	            $query_run = mysqli_query($conn, $sql);
	           
	            if ($query_run) {

		      		$msg = "Successfully create new patient";
		      		$status = "success";
		      		returnMsg($msg, $status);
		   			header('location:new-patient.php');
		        
		      	} else {
			        $msg = "Failed to create new doctor";
		      		$status = "danger";
		      		returnMsg($msg, $status);
		   			header('location: new-patient.php');
		      	}

	        

	    }

    }

	function updateStudentProfile(){

		global $conn;
    	$id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $student_id =  mysqli_real_escape_string($conn, $_POST['student_id']);
        $password = $_POST['password'];
 
    	$sql = "UPDATE student SET name='$name', email='$email' ";

      	if(isset($password) && !empty($password)){


        	$sql .= ", password = '$password'";
	 

      	}

        $sql .= "WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {

      		$msg = "Successfully update profile";
      		$status = "success";
      		returnMsg($msg, $status);
   			header('location:student-profile.php');
        
      	} else {
	        $msg = "Failed to update profile";
      		$status = "danger";
      		returnMsg($msg, $status);
   			header('location:student-profile.php');
      	}

    }


    function updateAdminProfile(){
    	global $conn;
    	$id = $_POST['id'];
        $admin_username = $_POST['name'];
        $admin_email = $_POST['email'];
        $admin_password = $_POST['password'];
    	$sql = "UPDATE admin SET name='$admin_username', email = '$admin_email'";

      	if(isset($admin_password) && !empty($admin_password)){

      		$encrypPass =$admin_password;
        	$sql .= ", password = '$encrypPass'";
	 

      	}

        $sql .= "WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {

      		$msg = "Successfully update profile";
      		$status = "success";
      		returnMsg($msg, $status);
   			header('location:admin-profile.php');
        
      	} else {
	        $msg = "Failed to update profile";
      		$status = "danger";
      		returnMsg($msg, $status);
   			header('location:admin-profile.php');
      	}
    }

    function fetchFacility() {
	    global $conn;

	    $limit = isset($_POST['limit']) ? $_POST['limit'] : 10; // Records per page
	    $page = isset($_POST['page']) ? $_POST['page'] : 1;
	    $offset = ($page - 1) * $limit;

	    $sql = "SELECT * FROM facility LIMIT $offset, $limit";
	    $result = mysqli_query($conn, $sql);
	    $datas = [];

	    if ($result && mysqli_num_rows($result) > 0) {
	        while ($row = mysqli_fetch_assoc($result)) {
	            $datas[] = $row;
	        }
	    }

	    // Get total number of records
	    $count_sql = "SELECT COUNT(*) AS total FROM facility";
	    $count_result = mysqli_query($conn, $count_sql);
	    $total_records = mysqli_fetch_assoc($count_result)['total'];

	    $response = [
	        'status' => 'success',
	        'data' => $datas,
	        'total_records' => $total_records,
	        'limit' => $limit,
	    ];

	    echo json_encode($response);

	}

	function deleteFacility() {
	    global $conn;
	    $id = $_POST['id'];

	    $sql = "DELETE FROM facility WHERE facility_id = '$id'";
	    if (mysqli_query($conn, $sql)) {
	        echo json_encode(['status' => 'success', 'message' => 'Data deleted successfully']);
	    } else {
	        echo json_encode(['status' => 'error', 'message' => 'Failed to delete data']);
	    }
	}

    function createFacility(){
    	global $conn;
    	$name = mysqli_real_escape_string($conn, $_POST['name']);
        $location = mysqli_real_escape_string($conn, $_POST['location']);
        $desc =  mysqli_real_escape_string($conn, $_POST['desc']);
        
        if(isset($_FILES['image']['name']) && $_FILES['image']["name"] != '' ){

            $img = $_FILES['image'];
            $filename = date('YmdHis').'_'.(str_replace(' ','',$img['name']));
            $path = $img['tmp_name'];
            $move = move_uploaded_file($path,'upload/'.$filename);

            $sql = "INSERT INTO facility (facility_name, facility_location, facility_desc, facility_img) VALUES ('$name', '$location', '$desc', '$filename')";

            $query_run = mysqli_query($conn, $sql);

           
            if ($query_run) {

	      		$msg = "Successfully create new facility";
	      		$status = "success";
	      		returnMsg($msg, $status);
	   			header('location:new-facility.php');
	        
	      	} else {
		        $msg = "Failed to create new facility";
	      		$status = "danger";
	      		returnMsg($msg, $status);
	   			header('location: new-facility.php');
	      	}

        }
    }


    function updateFacility(){

    	global $conn;
    	$id = $_POST['id'];
        $name =  mysqli_real_escape_string($conn, $_POST['name']);
        $location =  mysqli_real_escape_string($conn, $_POST['location']);
        $desc =  mysqli_real_escape_string($conn, $_POST['desc']);
        $oldpath = $_POST['oldpath'];

       
    	$sql = "UPDATE facility SET facility_name='$name', facility_location = '$location', facility_desc = '$desc'";
        
        if(isset($_FILES['image']['name']) && $_FILES['image']["name"] != '' ){

            $img = $_FILES['image'];
            $filename = date('YmdHis').'_'.(str_replace(' ','',$img['name']));
            $path = $img['tmp_name'];
            $move = move_uploaded_file($path,'upload/'.$filename);

            if($move){
            	$sql .= ", facility_img = '$filename'";
            	if(!empty($oldpath)){
		              if (file_exists("upload/".$oldpath)) {
		                  unlink("upload/".$oldpath);
		              } 
		        }
            }

        }

        $sql .= "WHERE facility_id = '$id'";

        if (mysqli_query($conn, $sql)) {

      		$msg = "Successfully update facility info";
      		$status = "success";
      		returnMsg($msg, $status);
   			header('location:edit-facility.php?eid='.$id);
        
      	} else {
	        $msg = "Failed to update facility";
      		$status = "danger";
      		returnMsg($msg, $status);
   			header('location: edit-facility.php?eid='.$id);
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