<?php 
    include 'user-session.php';
    include 'dbcon.php';

    if( isset($_GET["id"]) ){
        $edtid = $_GET['id'];
        $sqlE = "SELECT * FROM facility WHERE facility_id = '$edtid'";
        $resultE = mysqli_query($conn, $sqlE);
        $rowE = mysqli_fetch_assoc($resultE);
    }else{
        echo "<script>alert('Select one facility.');</script>";
        echo "<script>window.location.href='facility-list.php';</script>";
    }

    // Get today's date for min date
    $todayDate = date('Y-m-d');

 ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Facility Booking</title>

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
                                        <h1 class="h3 mb-0 text-gray-800 display-7">Facility Booking</h1>
                                    </div>
                                    
                                </div>
                                <div class="card-body">

                                    <?php if(isset($_SESSION['msg']) && !empty($_SESSION['msg']) ){ ?>

                                        <div class="alert alert-<?php echo $_SESSION['msg_status'] ?> alert-dismissible fade show" role="alert">
                                          <strong><?php echo $_SESSION['msg'] ?></strong>
                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>

                                    <?php $_SESSION['msg'] = ''; $_SESSION['msg_status'] = ''; } ?>

                                    <table class="table table-bordered">
                                        <tr class="bg-dark text-white">
                                            <td colspan="2">Facility Information</td>
                                        </tr>
                                        <tr>
                                            <td>Name</td>
                                            <td><?php echo $rowE['facility_name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Location</td>
                                            <td><?php echo $rowE['facility_location']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td><?php echo html_entity_decode($rowE['facility_desc']); ?></td>
                                        </tr>
                                    </table>

                                    <div class="row">
                                        <div class="col-12 col-md-6 pr-1">
                                            <label for="date" class="text-label">Booking Date</label>
                                            <input type="date" class="form-control" id="date" name="date" min="<?php echo $todayDate; ?>">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <div class="btn btn-dark mt-4 sm-text btn-search">Search</div>
                                        </div>
                                    </div>
                                  
                                        
                                  
                                </div>
                            </div>
                            <div class="card option-card" style="display: none;">

                                <div class="card-header">Please Select Date</div>
                                <div class="card-body"></div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <div class="btn btn-danger sm-text" id="bookNowBtn">Book Now</div>
                                    </div>
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
            
            $('.btn-search').on('click', function() {
                showOption();
            });

            $('#bookNowBtn').on('click', function() {
                const selectedSlot = $('input[name="time-slot"]:checked').val();
                
                if (!selectedSlot) {
                    alert('Please select a time slot.');
                    return;
                }

                // Define start and end times based on selected slot
                const startTime = selectedSlot;
                const endTime = parseInt(startTime) + 1; // Assuming each slot is 1 hour long

                // Send the booking request to the server
                $.ajax({
                    url: 'functions.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        facility_id: "<?php echo $rowE['facility_id']; ?>",
                        date: $('#date').val(),
                        start_time: startTime,
                        end_time: endTime,
                        action: 'book-slot',
                        student_id : '<?php echo $uid; ?>'
                    },
                    success: function(response) {
                        alert(response.msg);
                        showOption();
                    }
                });
            });
                    
        });

        function showOption(){
            const date = $('#date').val();
            const facilityId = "<?php echo $rowE['facility_id']; ?>";

            if (!date) {
                alert('Please select a date.');
                return;
            }
            $.ajax({
                url: 'functions.php',
                method: 'POST',
                dataType: 'json',
                data: { facility_id: facilityId, date: date, action: 'get-time-slot', student_id : '<?php echo $uid; ?>' },
                success: function(response) {
                    const selectedDate = new Date($('#date').val());
                    const today = new Date();
                    const isToday = selectedDate.toDateString() === today.toDateString();
                    
                    const currentHour = today.getHours();
                    const currentMinutes = today.getMinutes();

                    $('.option-card').show();
                    $('.option-card .card-body').empty();

                    var info = `<div class="d-flex align-items-center gap-3 mb-2"><div class="circle-black"></div><p class="text-note">Selected</p> <div class="circle-gray ml-3"></div><p class="text-note">Unavailable</p></div>`;
                    $('.option-card .card-body').append(info);

                    response.forEach(function(slot) {
                        const slotTime = parseInt(slot.time); // Convert slot time to integer hour

                        // Determine if slot is available:
                        // - If not today, all "available" slots are selectable
                        // - If today, allow only future slots or the current hour if not fully passed
                        const isAvailable = slot.status === 'available' && (
                            !isToday || slotTime > currentHour || 
                            (slotTime === currentHour && currentMinutes < 60)
                        );

                        // Format time for 12-hour AM/PM display
                        const timeIn12HrFormat = formatTime(slot.time);
                        const buttonRadio = `
                            <div class="form-check form-check-inline">
                                <input class="form-check-input slot-radio" type="radio" name="time-slot" id="slot-${slot.time}" value="${slot.time}" ${isAvailable ? '' : 'disabled'}>
                                <label class="btn ${isAvailable ? 'btn-available' : 'btn-unavailable'} m-1 text-sm" for="slot-${slot.time}">
                                    ${timeIn12HrFormat} - ${formatTime(slotTime + 1)}
                                </label>
                            </div>
                        `;
                        
                        $('.option-card .card-body').append(buttonRadio);
                    });
                }
            });
        }

        


        function formatTime(time24) {
            let hours = parseInt(time24);
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            return `${hours}:00 ${ampm}`;
        }

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