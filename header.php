<header class="main-header">
  <!-- Logo -->
  <a href="/dashboard" class="logo">
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg" style="font-family: Poppins, sans-serif; font-weight: 300px; font-size: 18px; line-height: 1.5;">
      
        <b style="font-weight: bold;">Thor</b>nament
      
    </span>
  </a>
  
  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">	
	  
        <!-- Messages -->
        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" id="team-invites-dropdown" data-toggle="dropdown">
            <i class="fa fa-inbox" id="team-invites-inbox"></i>
          </a>
          <ul class="dropdown-menu scale-up">
            <li class="header" id="team-invites-num"></li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu inner-content-div" id="team-invites">
                
                <!-- end message -->
              </ul>
            </li>
          </ul>
        </li>
        <!-- Notifications -->
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="mdi mdi-bell"></i>
          </a>
          <ul class="dropdown-menu scale-up">
            <li class="header">You have 7 notifications</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu inner-content-div">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> Curabitur id eros quis nunc suscipit blandit.
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-warning text-yellow"></i> Duis malesuada justo eu sapien elementum, in semper diam posuere.
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-users text-red"></i> Donec at nisi sit amet tortor commodo porttitor pretium a erat.
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-shopping-cart text-green"></i> In gravida mauris et nisi
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-user text-red"></i> Praesent eu lacus in libero dictum fermentum.
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-user text-red"></i> Nunc fringilla lorem 
                  </a>
                </li>
                <li>
                  <a href="#">
                    <i class="fa fa-user text-red"></i> Nullam euismod dolor ut quam interdum, at scelerisque ipsum imperdiet.
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer"><a href="#">View all</a></li>
          </ul>
        </li>
	     <!-- User Account -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo getUserInfo(0, "picture"); ?>" class="user-image rounded-circle" alt="User Image">
          </a>
          <ul class="dropdown-menu scale-up">
            <!-- User image -->
            <li class="user-header">
              <img src="<?php echo getUserInfo(0, "picture"); ?>" class="float-left rounded-circle" alt="User Image">

              <p>
                <?php echo getUserInfo(0, "user_id"); ?>
                <small class="mb-5"><?php echo $_SESSION['userData']['email']; ?></small>
                <a href="#" class="btn btn-danger btn-sm btn-rounded">View Profile</a>
              </p>
            </li>
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row no-gutters">
                <div class="col-12 text-left">
                  <a href="#"><i class="ion ion-person"></i> My Profile</a>
                </div>
                <div class="col-12 text-left">
                  <a href="#"><i class="ion ion-email-unread"></i> Inbox</a>
                </div>
                <div class="col-12 text-left">
                  <a href="#"><i class="ion ion-settings"></i> Setting</a>
                </div>
			<div role="separator" class="divider col-12"></div>
			  <div class="col-12 text-left">
                  <a href="#"><i class="ti-settings"></i> Account Setting</a>
                </div>
			<div role="separator" class="divider col-12"></div>
			  <div class="col-12 text-left">
                  <a href="/engine/logout.php"><i class="fa fa-power-off"></i> Log ud</a>
                </div>				
              </div>
              <!-- /.row -->
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>