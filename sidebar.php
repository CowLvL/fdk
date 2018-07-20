<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
	 <div class="ulogo">
		 <a href="/dashboard">
		  <!-- logo for regular state and mobile devices -->
		  <span><b>Thor</b>nament</span>
		</a>
	</div>
      <div class="image">
        <img src="<?php echo getUserInfo(0, "picture"); ?>" class="rounded-circle" alt="User Image">
      </div>
      <div class="info">
        <p>
          <a href="/profile/<?PHP echo str_replace("#", "", getUserInfo(0, "user_id", 0)); ?>" style="display: inline-block;"><?php echo getUserInfo(0, "user_id"); ?></a>
        </p>
		<a href="" class="link" data-toggle="tooltip" title="" data-original-title="Profilindstillinger"><i class="ion ion-gear-b"></i></a>
          <a href="" class="link" data-toggle="tooltip" title="" data-original-title="Support"><i class="ion ion-help-circled"></i></a>
          <a href="/engine/logout.php" class="link" data-toggle="tooltip" title="" data-original-title="Log ud"><i class="ion ion-power"></i></a>
      </div>
    </div>
    
    <!-- sidebar menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="nav-devider"></li>
      <li <?php echo getActivePage("dashboard"); ?>>
        <a href="/dashboard">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <li>
        <a href="https://discord.gg/MU5Yt2d" target="_blank">
          <i class="mdi mdi-discord"></i> <span>Join Discord</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <li>
        <a href="https://facebook.com/fortnitedanmark.dk" target="_blank">
          <i class="mdi mdi-facebook"></i> <span>Facebook</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <li class="header nav-small-cap">Turneringer</li>   
      <li <?php echo getActivePage("tournaments"); ?>>
        <a href="/tournaments">
          <i class="fa fa-trophy"></i> <span>Custom Games</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <li <?php echo getActivePage("elimination"); ?>>
        <a href="/elimination-tournament">
          <i class="mdi mdi-seal"></i> <span>Elimination</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <?php
	  if (checkUserGroup("", 2)) {
      //if (getUserInfo(0, "rank") > 0) {
      ?>
      <li>
        <a href="/tournament-create">
          <i class="fa fa-cart-plus"></i> <span>Opret turneringer</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      <?php
      }
      ?>
      <li class="header nav-small-cap">Personlig</li>
      <li <?php echo getActivePage("profile", $profile); ?>>
        <a href="/profile/<?php echo getUserInfo(0, "user_id", 0); ?>">
          <i class="fa fa-user"></i> <span>Profil</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
      </li>
      
      <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Dine teams</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php echo getSidebarTeams(getUserInfo(0, "id")); ?>
            <li>
              <a id="sa-create-team" href="#">
                <i class="mdi mdi-account-multiple-plus font-size-18"></i> <span>Opret team</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-right pull-right"></i>
                </span>
              </a>
            </li>
          </ul>
        </li>


    </ul>
  </section>
</aside>