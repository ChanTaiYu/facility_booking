<?php
    
    session_start();

    if(empty($_SESSION["uid"])){


        echo '<script>alert("Please login first."); 
                              window.location.href = "index.php";

                        </script>';

    }else{

        $uid = $_SESSION['uid'];
        $uemail = $_SESSION['email'];
        $role = $_SESSION['role'];
       
    }


 ?>