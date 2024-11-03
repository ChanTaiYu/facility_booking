<?php 
    include 'user-session.php';
    include 'dbcon.php';
 ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>New Facility</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/summernote/summernote-bs4.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style type="text/css">

    
        .file-input{
            padding: 0.3rem 0.75rem;
        }

        .file-input::-webkit-file-upload-button{
            font-size: 12px;
        }

        .note-editable {
            height: 300px; /* Adjust the height as needed */
        }

    </style>

</head>

<body id="page-top">

    <div id="wrapper">

        <?php include 'component/sidebar.php'; ?>
       
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include 'component/navbar.php'; ?>

                <div class="container-fluid">
                    

                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">

                                    <div class="d-flex align-items-center justify-content-between">
                                        <h1 class="h3 mb-0 text-gray-800 display-7">New Facility</h1>
                                    </div>
                                    
                                </div>
                                <div class="card-body p-5">

                                    <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']) ){ ?>

                                        <div class="alert alert-<?php echo $_SESSION['msg_status'] ?> alert-dismissible fade show" role="alert">
                                          <strong><?php echo $_SESSION['msg'] ?></strong>
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>

                                    <?php $_SESSION['msg'] = ''; $_SESSION['msg_status'] = ''; } ?>
                                        
                                    <form method="POST" action="functions.php" enctype="multipart/form-data">

                                        <div class="row">

                                            <div class="col-12">

                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="location">Location</label>
                                                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location" required>
                                                </div>

                                

                                                <div class="form-group">
                                                    <label for="desc">Description</label>
                                                    <textarea class="form-control" name="desc"></textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label for="image">Image</label>
                                                    <input type="file" class="form-control file-input" name="image" required>
                                                </div>
                                                
                                                 <input type="hidden" class="form-control" id="action" name="action" value="create-facility" required>
                                                
                                            </div>

                                           
                                        </div>
                                        
                                   

                                        <div class="my-3 text-center">
                                            <input class="btn btn-dark btnAction" type="submit" value="Create" />
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        

            </div>

            <?php include 'component/footer.php' ?>

        </div>

    </div>

    <?php include 'component/modal.php' ?>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/admin.min.js"></script>
 

    <script type="text/javascript">

        $(document).ready(function() {
            
            $('a[href="new-facility.php"]').addClass('active');
            $('a[data-target="#collapseMenuF"]').parent().addClass('active');
            if(window.matchMedia('(min-width: 769px)').matches){
                $('#collapseMenuF').addClass('show');
            }
            
        });

          //Allow Number Only
        $(".numberOnly").on("input", function (evt) {
            var self = $(this);
            self.val(self.val().replace(/[^0-9\.]/g, ''));
            if ((evt.which !== 46 || self.val().indexOf('.') !== -1) && (evt.which < 48 || evt.which > 57))
            {
                evt.preventDefault();
            }
        });

       
    </script>

</body>

</html>