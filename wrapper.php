<body class="hold-transition skin-yellow sidebar-mini">
	<div id="fb-root"></div>
	<!-- Site wrapper -->
	<div class="wrapper">
		<?php
			include "header.php";
			include "sidebar.php";
		?>
		<!-- =============================================== -->
		<?php
			if (!isset($page)) {
				include "dashboard.php";
			} elseif ($page == "dashboard") {
				include "dashboard.php";
			} elseif ($page == "profile") {
				include "profile.php";
			} elseif ($page == "tournaments") {
				include "tournaments.php";
			} elseif ($page == "tournament") {
				include "tournament.php";
			} elseif ($page == "create-tournament") {
				include "create-tournament.php";
			} elseif ($page == "teams") {
				include "teams.php";
			} elseif ($page == "bracket64") {
				include "bracket64.php";
			} elseif ($page == "bracket32") {
				include "bracket32.php";
			} elseif ($page == "bracket16") {
				include "bracket16.php";
			} elseif ($page == "bracket8") {
				include "bracket8.php";
			} elseif ($page == "team") {
				include "team.php";
			} elseif ($page == "gametag") {
				include "gametag.php";
			} elseif ($page == "test123") {
				include "test123.php";
			} elseif ($page == "elimination-tournament") {
				include "elimination-tournament.php";
			}
		?> 
		<!--<footer class="main-footer">
			<div class="pull-right d-none d-sm-inline-block">
				<ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
					<li class="nav-item">
						<a class="nav-link" href="javascript:void(0)">FAQ</a>
					</li>
				</ul>
			</div>
			&copy; 2018 <a href="https://fortnitedanmark/">Thornament</a>. All Rights Reserved.
		</footer>-->
		<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>
	</div>
	<!-- ./wrapper -->
	<?php 
		if ($page == "tournaments") {
			include "engine/swal-tournaments.php";
		} elseif ($page == "team") {
			if (getUserInfo(getTeamInfo($team, "captain_id"), "user_id", 0) == getUserInfo(0, "user_id", 0) || getUserInfo(getTeamInfo($team, "coop_id"), "user_id", 0) == getUserInfo(0, "user_id", 0)) {
				include "engine/swal-upload-team-picture.php";
				include "engine/swal-invite-member.php";
			}
		}
		include "engine/swal-create-team.php";
		include "engine/swal-show-tos.php";
		$tos = getTos();
		if (getUserInfo(0, "tos_version") < $tos['id']) {
			//echo $tos['id'];
		}
		//echo count(getGametags(0));
		if (count(getGametags(0)) == 0) {
			include "engine/swal-add-gametag.php";
		}
	?>	
	<script type="text/javascript">
		$(function() {
			$("#create-tournament-advanced").hide();
			$("#basic_checkbox_1").click(function() {
				$("#create-tournament-advanced").toggle();
			});
		});
	</script>
</body>