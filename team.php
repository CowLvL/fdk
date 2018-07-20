<?php
// check if user exists
if (teamExists($team) == 0) {
	// user dosent exists
	include "no-page.php";
} else {
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-lg-3 col-12">

      <!-- Profile Image -->
      <div class="box bg-inverse bg-dark bg-hexagons-white">
        <div class="box-body box-profile">
          <div style="position: relative;">
          	<?php if (getTeamInfo($team, "captain_id") == getUserInfo(0, "id", 0) || getTeamInfo($team, "coop_id") == getUserInfo(0, "id", 0)) { ?>
          	<a id="sa-upload-team-picture" href="#" class="bg-success"><i class="fa fa-camera camera-upload" aria-hidden="true"></i></a>
          	<?php } ?>
          	<img class="profile-user-img rounded-circle img-fluid mx-auto d-block" src="<?php echo getTeamInfo($team, "picture"); ?>" alt="Team profile picture">
          </div>

          <h3 class="profile-username text-center"><?php echo getTeamInfo($team, "name"); ?></h3>

          <p class="text-center">
          	Captain: <?php echo getUserInfo(getTeamInfo($team, "captain_id"), "user_id"); ?><br>
          	<?php if (!empty(getUserInfo(getTeamInfo($team, "coop_id"), "user_id", 0))) {echo "Coop: " . getUserInfo(getTeamInfo($team, "coop_id"), "user_id");} ?>
          </p>
        
          <div class="row">
          	<div class="col-12">
          		<div class="profile-user-info">
					<div class="row">
						<div class="col-md-6">
							<a class="box box-link-pop text-center" href="javascript:void(0)">
								<div class="box-body py-25">
									<p class="font-size-40">
										<strong>12</strong>
									</p>
									<p class="font-weight-600">Kampe</p>
								</div>
							</a>
						</div>
						<div class="col-md-6">
							<a class="box box-link-pop text-center" href="javascript:void(0)">
								<div class="box-body py-25">
									<p class="font-size-40">
										<strong>50%</strong>
									</p>
									<p class="font-weight-600">Win %</p>
								</div>
							</a>
						</div>
					</div>
				</div>
         	</div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-lg-9 col-12">
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <?php if (getTeamInfo($team, "captain_id") == getUserInfo(0, "id", 0) || getTeamInfo($team, "coop_id") == getUserInfo(0, "id", 0)) { ?>  
          <li><a id="sa-invite-member" href="#">Inviter medlem</a></li>
          <?php } ?>
          <li><a class="active" href="#overview" data-toggle="tab">Oversigt</a></li>
          <li><a href="#stats" data-toggle="tab">Stats</a></li>
        </ul>
                    
        <div class="tab-content">
         <div class="active tab-pane" id="overview">
		  <div class="media-list media-list-divided media-list-hover">

		  	<?php echo getTeamMembers(getTeamInfo($team, "id")); ?>

		  </div>

          </div>    
          
          <div class="tab-pane" id="stats">
            DISABLED
          </div>
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
  </div>
  <!-- /.row -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
}
?>