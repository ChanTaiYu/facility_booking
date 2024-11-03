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

    <title>All Booking</title>

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
                        <h1 class="h3 mb-2 text-gray-800 display-7">All Booking</h1>
                    </div>

                    <div class="card">
                        <div class="card-body overflow-auto">
                            <table class="table table-bordered" id="tableData">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Facility</th>
                                        <th>Booking Date</th>
                                        <th>Booking Time</th>
                                        <th>Student Info</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                            <!-- Pagination Controls -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center" id="pagination">
                                </ul>
                            </nav>
                        </div>
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
        $('a[href="all-booking.php"]').parent().addClass('active');
    

        loadData(currentPage);
        
    });



    function loadData(page = 1) {
        $.ajax({
            url: 'functions.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'view-all-booking',
                limit: limit,
                page: page
            },
            success: function(response) {

                if(response.data.length < 1){

                    $('#tableBody').append(`
                                <tr>
                                    <td colspan='10'>No Data</td>
                                </tr>
                            `);
                }else{

                    if (response.status === 'success') {
                        $('#tableBody').empty();

                        response.data.forEach(function(d) {
                            var s = '';
                            if(d.status == 'cancel'){
                                s = '<p class="mt-0 mb-0 text-uppercase text-danger">Cancelled</p>';
                                btn = '-';
                            }else if(d.status == 'confirmed'){
                                s = '<p class="mt-0 mb-0 text-uppercase text-success">Confirmed</p>';
                                btn = '-';
                            }else{
                                s = '<p class="mt-0 mb-0 text-uppercase">Pending</p>';
                                btn = `<div class="d-flex align-items-center gap-2">
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-success ml-2 confirm-data btn-sm" data-id="${d.booking_id}" title="confirm">Confirm</button>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-danger ml-2 cancel-data btn-sm" data-id="${d.booking_id}" title="cancel">Cancel</button>
                                        </div>
                                        </div>
                                    `;
                            }
                            const startTime = new Date('1970-01-01T' + d.start_time).toLocaleString('en-US', {
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });
                            const endTime = new Date('1970-01-01T' + d.end_time).toLocaleString('en-US', {
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            });

                            $('#tableBody').append(`
                                <tr data-id="${d.booking_id}">
                                    <td>${d.booking_id}</td>
                                    <td>
                                        <img src="upload/${d.facility_img}" width="80" class="rounded" />
                                        <p class="mt-3 mb-0">Name: ${d.facility_name}</p>
                                        <p class="mt-0 mb-0">Location: ${d.facility_location}</p>
                                    </td>
                                    <td>${d.booking_date}</td>
                                    <td>${startTime} ~ ${endTime}</td>
                                    <td>
                                        <p class="mt-0 mb-0">Name: ${d.name}</p>
                                        <p class="mt-0 mb-0">Email: ${d.email}</p>
                                        <p class="mt-0 mb-0">Student ID: ${d.student_id}</p>
                                    </td>
                                    <td>${s}</td>
                                    <td>
                                        ${btn}
                                    </td>
                 
                                </tr>
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

    $(document).on('click', '.cancel-data', function() {
        const id = $(this).data('id');

        if (confirm('Confirm to cancel booking?')) {
            $.ajax({
                url: 'functions.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'cancel-booking',
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        alert(response.msg);
                        loadData(currentPage); // Reload admins after deletion
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
    });


    $(document).on('click', '.confirm-data', function() {
        const id = $(this).data('id');

        if (confirm('Confirm to confirm booking?')) {
            $.ajax({
                url: 'functions.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'approve-booking',
                    id: id
                },
                success: function(response) {
                    if (response.status) {
                        alert(response.msg);
                        loadData(currentPage); // Reload admins after deletion
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
    });

    
      
    </script>

</body>

</html>