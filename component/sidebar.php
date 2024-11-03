<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="img/logo.png" style="max-width: 80px; border-radius: 6px;" />
        </div>
        <!-- <div class="sidebar-brand-text mx-3" style="font-size: 12px !important;">BookMyCheckup</div> -->
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

   
   <!--  <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenuA"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-calendar-check"></i>
            <span>Appointment</span>
        </a>
        <div id="collapseMenuA" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu</h6>
                <?php if($role =='admin' || $role =='patient'){ ?>
                <a class="collapse-item" href="new-appointment.php">Create Appointment</a>
                <?php } ?>
                <a class="collapse-item" href="appointment.php">Appointment List</a>
            </div>
        </div>
    </li>

    <?php if($role =='admin'){ ?>
    <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenuS"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-briefcase-medical"></i>
            <span>Service</span>
        </a>
        <div id="collapseMenuS" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu</h6>
                
                <a class="collapse-item" href="new-service.php">New Service</a>
                
                <a class="collapse-item" href="service.php">Service List</a>
            </div>
        </div>
    </li>
    <?php } ?> -->

   
   <!--  <hr class="sidebar-divider d-none d-md-block mb-0">

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenu"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw  fa-user-md"></i>
            <span>Doctor</span>
        </a>
        <div id="collapseMenu" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Menu</h6>
                <?php if($role =='admin'){ ?>
                <a class="collapse-item" href="new-doctor.php">Create Doctor</a>
                <?php } ?>
                
                <a class="collapse-item" href="doctor.php">Doctor List</a>
        
            </div>
        </div>
    </li>
    -->



    <?php if($role =='admin'){ ?>
    <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenu"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Admin</span>
        </a>
        <div id="collapseMenu" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="new-admin.php">New Admin</a>
                <a class="collapse-item" href="admin.php">Admin List</a>
            </div>
        </div>
    </li>
     <hr class="sidebar-divider d-none d-md-block mb-0">
     <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMenuF"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-building"></i>
            <span>Facility</span>
        </a>
        <div id="collapseMenuF" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="new-facility.php">New Facility</a>
                <a class="collapse-item" href="facility.php">Facility List</a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link" href="all-booking.php">
            <i class="fas fa-fw fa-calendar"></i>
            <span>All Booking</span>
        </a>
    </li>
    <?php } ?>

    <?php if($role !=='admin'){ ?>
    <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link" href="facility-list.php">
            <i class="fas fa-fw fa-building"></i>
            <span>Facility</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block mb-0">
    <li class="nav-item">
        <a class="nav-link" href="my-booking.php">
            <i class="fas fa-fw fa-calendar"></i>
            <span>My Booking</span>
        </a>
    </li>


    <?php } ?>

    <hr class="sidebar-divider d-none d-md-block mb-0"> 
    <?php 
        if($role =='admin'){ 
            $url = 'admin-profile.php';
        }else{
            $url = 'student-profile.php';
        }
    ?>


    <li class="nav-item">
        <a class="nav-link" href="<?php echo $url; ?>">
            <i class="fas fa-fw fa-user"></i>
            <span>Profile</span>
        </a>
    </li>
   
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>