<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css">
        html, body{
            height: auto;
        }

        .bg-body {
            position: relative;
            background: url('img/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh; /* or adjust to fit your container */
        }

        .bg-body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3); /* Dark overlay with 50% opacity */
            /* backdrop-filter: blur(5px); /* Adjust blur level as needed */
            z-index: 1; /* Ensures overlay is above background */
        }

        /* To style content inside the .bg-body */
        .bg-body > * {
            position: relative;
            z-index: 2; /* Keeps content above the overlay */
        }

        .custom-select{
            height: 50px;
        }
    </style>

</head>

<body class="bg-body">

    <div class="container h-100">

        

        <!-- Outer Row -->
        <div class="row justify-content-center align-items-center h-100">


    

            <div class="col-xl-5 col-lg-6 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0" >
                        <div class="row">
                            
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center mb-3">
                                        <div class="d-flex justify-content-center align-items-center mb-4">
                                            <img src="img/logo.png " style="max-width: 100px;" />
                                        </div>
                                        <div class="text-center">
                                            <h6 class="text-uppercase">Facility Booking</h6>
                                        </div>
                                    </div>
                                    <?php session_start(); ?>
                                    <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']) ){ ?>

                                        <div class="alert alert-<?php echo $_SESSION['msg_status'] ?> alert-dismissible fade show" role="alert">
                                          <strong><?php echo $_SESSION['msg'] ?></strong>
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                    <?php $_SESSION['msg'] = ''; $_SESSION['msg_status'] = ''; } ?>

                                
                                    <form class="user" method="POST" action="auth.php" >
                                        <label>Login As Admin</label>
                                        <input type="hidden" class="form-control form-control-user rounded"
                                                id="role" name="role" value="admin" required >
            
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user rounded"
                                                id="email" name="email" 
                                                placeholder="Enter Email" required autocomplete="off">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user rounded"
                                                id="password" placeholder="Enter Password" name="password" required>
                                        </div>

                                        <input type="hidden" class="form-control form-control-user rounded"
                                                id="action" name="action" value="login" required >

                                        <input type="submit" value="LOGIN" name="login" class="btn btn-black btn-user btn-block mt-4 mb-2 rounded">
                                     
                                    </form>

                                    <div class="row">

                                        <div class="col-12 col-md-12 mb-2 mb-md-0">
                                            <a class="btn btn-dark rounded w-100" href="index.php">
                                                <img src="img/student.png" width="20" />
                                                Student
                                            </a>
                                        </div>
                                       
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>