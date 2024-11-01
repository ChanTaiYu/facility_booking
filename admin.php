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

    <title>Admin</title>

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
                        <h1 class="h3 mb-2 text-gray-800 display-7">Admin List</h1>
                        <?php if($role =='admin'){ ?>
                        <a class="btn btn-dark btn-add mb-2 px-4 btn-sm" href="new-admin.php">New</a>
                        <?php } ?>
                    </div>

                    <div class="card">
                        <div class="card-body overflow-auto">
                            <table class="table table-bordered" id="tableData">
                                <thead>
                                    <tr>
                                        <th>Admin ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="adminTableBody"></tbody>
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
            $('a[href="admin.php"]').addClass('active');
            $('a[data-target="#collapseMenu"]').parent().addClass('active');
            if(window.matchMedia('(min-width: 769px)').matches){
                $('#collapseMenu').addClass('show');
            }

            loadAdmins(currentPage);
            
        });



    function loadAdmins(page = 1) {
        $.ajax({
            url: 'functions.php',
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'fetch-admins',
                limit: limit,
                page: page
            },
            success: function(response) {
                if(response.data.length < 1){

                    $('#adminTableBody').append(`
                                <tr>
                                    <td colspan='10'>No Data</td>
                                </tr>
                            `);
                }else{

                    if (response.status === 'success') {
                        $('#adminTableBody').empty();

                        response.data.forEach(function(admin) {
                            $('#adminTableBody').append(`
                                <tr data-id="${admin.id}">
                                    <td>${admin.id}</td>
                                    <td>${admin.name}</td>
                                    <td>${admin.email}</td>
                                    <?php if($role == 'admin') { ?>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a class="btn btn-dark btn-sm" href="edit-admin.php?eid=${admin.id}" title="Edit">Edit</a>
                                            <button class="btn btn-danger ml-2 delete-admin btn-sm" data-id="${admin.id}" title="Delete">Delete</button>
                                        </div>
                                    </td>
                                    <?php } ?>
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
                    <a class="page-link" href="javascript:void(0);" onclick="loadAdmins(${i})">${i}</a>
                </li>
            `);
        }
    }

    $(document).on('click', '.delete-admin', function() {
        const id = $(this).data('id');

        if (confirm('Confirm to delete admin info?')) {
            $.ajax({
                url: 'functions.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'delete-admin',
                    id: id
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        loadAdmins(currentPage); // Reload admins after deletion
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    
      
    </script>

</body>

</html>