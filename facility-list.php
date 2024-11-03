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

    <title>Facility</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'component/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'component/navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid" id="form-table">

                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-between">
                        <h1 class="h3 mb-2 text-gray-800 display-7">Facility List</h1>
                    </div>

                    <div>
                        
                        <div class="row" id="cardContainer"></div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center" id="pagination">
                            </ul>
                        </nav>
                    
                    </div>

                </div>       
                <!-- /.container-fluid -->

                

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php include 'component/footer.php' ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include 'component/modal.php' ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">

    const limit = 15;
    let currentPage = 1;
  
    $(document).ready(function() {
        
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('a[href="facility-list.php"]').parent().addClass('active');
      
        loadData(currentPage);
        
    });



    function loadData(page = 1) {
        $.ajax({
            url: 'functions.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'fetch-facility',
                limit: limit,
                page: page
            },
            success: function(response) {

                $('#cardContainer').empty();
                if(response.data.length < 1){

                     $('#cardContainer').append(`
                        <div class="col-12 text-center">
                            <p>No Data</p>
                        </div>
                    `);

                }else{

                    if (response.status === 'success') {
        

                        response.data.forEach(function(d) {
                            $('#cardContainer').append(`
                                <div class="col-12 col-sm-6 col-md-4 mb-4">
                                    <div class="card h-100 shadow">
                                        <img src="upload/${d.facility_img}" class="card-img-top" alt="${d.facility_name}" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <h5 class="card-title text-black">${d.facility_name}</h5>
                                            <p class="card-text text-img"><img src="img/location.png" /> ${d.facility_location}</p>
                                            <p class="card-text">${d.facility_desc}</p>
                                            <div class="text-right">
                                                <a class="btn btn-danger btn-sm" data-id="${d.facility_id}" href="facility-booking.php?id=${d.facility_id}">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                        setupPagination(response.total_records, limit, page);
                    }

                }
                
            }
        });
    }

    function setupPagination(totalRecords, limit, currentPage) {
        const totalPages = Math.ceil(totalRecords / limit);
        $('#pagination').empty();

        for (let i = 1; i <= totalPages; i++) {
            $('#pagination').append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="javascript:void(0);" onclick="loadData(${i})">${i}</a>
                </li>
            `);
        }
    }

    

    
      
    </script>

</body>

</html>