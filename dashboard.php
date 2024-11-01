<?php 

    include 'user-session.php'; 
    include 'dbcon.php';

    $sql1 = "SELECT COUNT(*) as Total FROM student";
    $result1 = mysqli_query($conn, $sql1);

    $sql2 = "SELECT COUNT(*) as Total FROM booking WHERE status = 'pending'";
    $result2 = mysqli_query($conn, $sql2);

    $sql3 = "SELECT COUNT(*) as Total FROM booking WHERE status = 'confirmed'";
    $result3 = mysqli_query($conn, $sql3);



    if(mysqli_num_rows($result1) > 0){
        $rows1 = mysqli_fetch_assoc($result1);
        $student = $rows1['Total'];
    }else{
        $student = 0;
    }

    if(mysqli_num_rows($result2) > 0){
        $rows2 = mysqli_fetch_assoc($result2);
        $booking = $rows2['Total'];
    }else{
        $booking = 0;
    }

    if(mysqli_num_rows($result3) > 0){
        $rows3 = mysqli_fetch_assoc($result3);
        $comfirmed = $rows3['Total'];
    }else{
        $confirmed = 0;
    }


    if($role == 'student'){

        $sql7 = "SELECT COUNT(*) as Total FROM booking WHERE status = 'confirmed' AND student_id = '$uid'";
        $result7 = mysqli_query($conn, $sql7);

       
        if(mysqli_num_rows($result7) > 0){
            
            $rows7 = mysqli_fetch_assoc($result7);
            $totalP = $rows7['Total'];

        }else{

            $totalP = 0;
            
        }

        $sql8 = "SELECT COUNT(*) as Total FROM booking WHERE status = 'pending' AND student_id = '$uid'";
        $result8 = mysqli_query($conn, $sql8);

        if(mysqli_num_rows($result8) > 0){
            
            $rows8 = mysqli_fetch_assoc($result8);
            $totalPP = $rows8['Total'];

        }else{

            $totalPP = 0;
            
        }

    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <div id="wrapper">

        <?php include 'component/sidebar.php' ?>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include 'component/navbar.php' ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-3 text-gray-800">Dashboard</h1>

                    <div class="row">

                        <?php if($role == 'admin'){ ?>
                        <!-- Student Card -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Student Count</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $student; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks  text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-dark shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Pending Booking Count</div>
                                            <?php if($role == 'admin'){ ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $booking; ?></div>
                                            <?php }else{?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalPP; ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Comfirmed Booking Count</div>
                                            <?php if($role == 'admin'){ ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $comfirmed; ?></div>
                                            <?php }else{ ?>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalP; ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        
                    </div>

                </div>


            </div>

            <?php include 'component/footer.php'; ?>

        </div>

    </div>

    <?php include 'component/modal.php'; ?>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $('.nav-item .nav-link[href="dashboard.php"]').parent().addClass('active');
            
        });
    </script>
  
</body>

</html>