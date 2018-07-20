<?php
	if (!isset($_REQUEST["profile"])) {
		//$options = {"id", "user_id", "picture", "created"}
		//$userInfo = getUserInfo(0, $options, 0);
		$profile = getUserInfo(0, "user_id", 0);
	}
	// check if user exists
	if (userExists($profile) == 0) {
		// user dosent exists
		include "no-page.php";
	} else {
		$bc_total = "#252525";
		$bc_squad = "#ee534f";
		$bc_duo = "#faae1c";
		$bc_solo = "#02c293";
?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="row">
				<div class="col-lg-3 col-12">
					<!-- Profile Image -->
					<div class="box bg-sidebar bg-hexagons-white">
						<div class="box-body box-profile">
							<img class="profile-user-img rounded-circle img-fluid mx-auto d-block" src="<?php echo getUserInfo($profile, "picture"); ?>" alt="User profile picture">
							<h3 class="profile-username text-center"><?php echo getUserInfo($profile, "user_id"); ?></h3>
							<p class="text-center">Bruger siden <?php echo strftime("%e %B %Y", getUserInfo($profile, "created")); ?></p>
							<div class="row">
								<div class="col-12">
									<div class="profile-user-info">
										<div style="text-align: center;"><span style="font-size: 16px; font-weight: bold; color: #bbbbbb;">GAMETAGS</span></div>
										<?PHP
											$gametags = getGametags($profile);
											//var_dump($gametags);
											foreach ($gametags as $gts) {
												$i = 0;
												foreach ($gts as $gt) {
													$pid = $gt["pid"];
													$game = $gt["game"];
													if ($pid == 1) {
														$icon = "mdi-desktop-mac";
														$title = "PC / MAC";
													} elseif ($pid == 2) {
														$icon = "mdi-playstation";
														$title = "Playstation 4";
													} else {
														$icon = "mdi-xbox";
														$title = "XBox One";
													}
													if (!isset($display[$game])) {
										?>
										<div><span><?PHP echo $game; ?></span></div>
										<?PHP
														$display[$game] = true;
													}
										?>
										<div><button name="<?PHP echo getGame($game)['tag']."/".getPlatform($pid)['tag']; ?>" class="btn btn-block btn-social btn-epic gametag"><i class="mdi <?PHP echo $icon; ?>" title="<?PHP echo $title; ?>"></i> <?php echo $gt["name"]; ?></button></div>
										<?PHP
													$i++;
												}
												echo "<br />";
											}
										?>
										<script>
											$(function() {
												$(".gametag").on({
													click: function() {
														var url = "//" + window.location.hostname + "/profile/" + "<?PHP echo $profile; ?>";
														if ($(this).attr('name') != 0) {
															url += "/" + $(this).attr('name');
														}
														window.location.href = url;
													}
												});
											});
										</script>
										<br />
										<div style="text-align: center;"><span style="font-size: 16px; font-weight: bold; color: #bbbbbb;">MEDIA CHANNELS</span></div>
										<br />
										<div><button class="btn btn-block btn-social btn-facebook"><i class="mdi mdi-facebook"></i> Facebookside</button></div>
										<div><button class="btn btn-block btn-social btn-youtube"><i class="mdi mdi-youtube-play"></i> Youtube-kanal</button></div>
										<div><button class="btn btn-block btn-social btn-twitch"><i class="mdi mdi-twitch"></i> Twitch-kanal</button></div>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
					</div>
					<?php echo getProfileTeams($profile); ?>
					<!-- /.box -->
				</div>
				<!-- /.col -->
				<div class="col-lg-9 col-12">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li><a href="#alltime" data-toggle="tab">All time</a></li>
							<li><a href="#season4" data-toggle="tab">Sæson 4</a></li>
							<li><a class="active" href="#season5" data-toggle="tab">Sæson 5</a></li>
							<?PHP
								if (getUserInfo($profile, "id") == $_SESSION['userData']['uid']) {
							?>
							<li><a href="#settings" data-toggle="tab">Settings</a></li>
							<?PHP
								}
							?>
						</ul>
						<div class="tab-content">
							<!-- /.tab-pane -->
							<div class="tab-pane" id="alltime">
								<?PHP
									$game = (isset($_GET['game'])) ? getGame($_GET['game'])['id'] : getGametags($profile)[1][0]['gid'];
									$platform = (isset($_GET['platform'])) ? getPlatform($_GET['platform'])['id'] : getGametags($profile)[1][0]['pid'];
									$gametag = getGametag($profile, $game, $platform);
									if ($gametag != NULL) {
										$pid = $gametag['pid'];
										$gid = $gametag['gid'];
										$gname = $gametag['name'];
										$window = "alltime";
										$id = getUserInfo($profile, "id");
										$stats1 = getStats(0, $gid, $pid, $window);
										//var_dump($stats1);
										$ent = getStatsEnt(1);
										$rank = array();
										foreach ($ent as $e) {
											$stats1_temp = array();
											foreach ($stats1 as $key => $val) {
												foreach ($val as $k => $v) {
													//echo $k." > ".$v."<br />";
													if ($k == $e) { $stats1_temp[$key] = $v; }
												}
											}
											arsort($stats1_temp);
											$i = 1;
											foreach ($stats1_temp as $key => $val) {
												$count = count($stats1);
												if ($stats1[$key]["uid"] == $id) {
													$rank[$e]["rank"] = $i;
													$rank[$e]["count"] = round((100-($i/$count*100))+(1/$count*100));
												}
												$i++;
											}
										}
										// get stats for fortnite (game id 1), pc (platform id 1) and season (returns 0 if no record)
										$stats = getStats($id, $gid, $pid, $window);
										//var_dump($stats);
									} else {
										$stats = 0;
									}
									//echo "<br />";
									if ($stats != 0) {
								?>
								<div class="row">
									<h3 style="margin-left: 10px; margin-bottom: 20px;">Alltime stats for '<?PHP echo $gname; ?>'</h3>
								</div>
								<div class="row">
									<span style="margin-left: 10px;">Senest opdateret: <?PHP echo date("F d, Y - H:i:s", $stats['unix']); ?> (<?PHP echo GetTimeDiff($stats['unix']); ?> ago)</span>
								</div>
								<div class="row">
									<!-- 7dage box -->
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header with-border bg-profile-7day">
											<h3 class="box-title">TOTAL</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="margin: 20px 14px 20px 14px;">
													<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score'])); ?></b></span>
													<div class="progress progress-xs" style="width: 100%; margin: 0;">
														<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['score']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
													</div>
													<span>#<?PHP echo $rank['score']["rank"]; ?></span>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="margin: 20px 0 0 0;">
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['winrate']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['wins']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['wins']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['matchesplayed']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- squad box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-squad">
											<h3 class="box-title bold">SQUAD</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_squad'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_squad']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_squad']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_squad']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- duo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-duo">
											<h3 class="box-title bold">DUO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_duo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_duo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_duo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_duo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- solo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-solo">
											<h3 class="box-title bold">SOLO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_solo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_solo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_solo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_solo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.Stats row -->
								<?PHP
									} else {
								?>
								<div class="row">
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px; text-align: center; margin: 0;">
										<span style="font-size: 20px; font-weight: bold;">NO STATS FOUND</span>
									</div>
								</div>
								<?PHP
									}
								?>
							</div>
							<div class="tab-pane" id="season4">
								<?PHP
									$game = (isset($_GET['game'])) ? getGame($_GET['game'])['id'] : getGametags($profile)[1][0]['gid'];
									$platform = (isset($_GET['platform'])) ? getPlatform($_GET['platform'])['id'] : getGametags($profile)[1][0]['pid'];
									$gametag = getGametag($profile, $game, $platform);
									if ($gametag != NULL) {
										$pid = $gametag['pid'];
										$gid = $gametag['gid'];
										$gname = $gametag['name'];
										$window = "season4";
										$id = getUserInfo($profile, "id");
										$stats1 = getStats(0, $gid, $pid, $window);
										//var_dump($stats1);
										$ent = getStatsEnt(1);
										$rank = array();
										foreach ($ent as $e) {
											$stats1_temp = array();
											foreach ($stats1 as $key => $val) {
												foreach ($val as $k => $v) {
													//echo $k." > ".$v."<br />";
													if ($k == $e) { $stats1_temp[$key] = $v; }
												}
											}
											arsort($stats1_temp);
											$i = 1;
											foreach ($stats1_temp as $key => $val) {
												$count = count($stats1);
												if ($stats1[$key]["uid"] == $id) {
													$rank[$e]["rank"] = $i;
													$rank[$e]["count"] = round((100-($i/$count*100))+(1/$count*100));
												}
												$i++;
											}
										}
										// get stats for fortnite (game id 1), pc (platform id 1) and season (returns 0 if no record)
										$stats = getStats($id, $gid, $pid, $window);
										//var_dump($stats);
									} else {
										$stats = 0;
									}
									if ($stats != 0) {
								?>
								<div class="row">
									<h3 style="margin-left: 10px; margin-bottom: 20px;">Season4 stats for '<?PHP echo $gname; ?>'</h3>
								</div>
								<div class="row">
									<span style="margin-left: 10px;">Senest opdateret: <?PHP echo date("F d, Y - H:i:s", $stats['unix']); ?> (<?PHP echo GetTimeDiff($stats['unix']); ?> ago)</span>
								</div>
								<div class="row">
									<!-- 7dage box -->
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header with-border bg-profile-7day">
											<h3 class="box-title">TOTAL</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="margin: 20px 14px 20px 14px;">
													<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score'])); ?></b></span>
													<div class="progress progress-xs" style="width: 100%; margin: 0;">
														<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['score']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
													</div>
													<span>#<?PHP echo $rank['score']["rank"]; ?></span>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="margin: 20px 0 0 0;">
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['winrate']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['wins']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['wins']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['matchesplayed']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- squad box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-squad">
											<h3 class="box-title bold">SQUAD</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_squad'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_squad']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_squad']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_squad']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- duo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-duo">
											<h3 class="box-title bold">DUO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_duo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_duo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_duo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_duo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- solo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-solo">
											<h3 class="box-title bold">SOLO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_solo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_solo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_solo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_solo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.Stats row -->
								<?PHP
									} else {
								?>
								<div class="row">
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px; text-align: center; margin: 0;">
										<span style="font-size: 20px; font-weight: bold;">NO STATS FOUND</span>
									</div>
								</div>
								<?PHP
									}
								?>
							</div>
							<div class="active tab-pane" id="season5">
								<?PHP
									$game = (isset($_GET['game'])) ? getGame($_GET['game'])['id'] : getGametags($profile)[1][0]['gid'];
									$platform = (isset($_GET['platform'])) ? getPlatform($_GET['platform'])['id'] : getGametags($profile)[1][0]['pid'];
									$gametag = getGametag($profile, $game, $platform);
									if ($gametag != NULL) {
										$pid = $gametag['pid'];
										$gid = $gametag['gid'];
										$gname = $gametag['name'];
										$window = "season5";
										$id = getUserInfo($profile, "id");
										$stats1 = getStats(0, $gid, $pid, $window);
										//var_dump($stats1);
										$ent = getStatsEnt(1);
										$rank = array();
										foreach ($ent as $e) {
											$stats1_temp = array();
											foreach ($stats1 as $key => $val) {
												foreach ($val as $k => $v) {
													//echo $k." > ".$v."<br />";
													if ($k == $e) { $stats1_temp[$key] = $v; }
												}
											}
											arsort($stats1_temp);
											$i = 1;
											foreach ($stats1_temp as $key => $val) {
												$count = count($stats1);
												if ($stats1[$key]["uid"] == $id) {
													$rank[$e]["rank"] = $i;
													$rank[$e]["count"] = round((100-($i/$count*100))+(1/$count*100));
												}
												$i++;
											}
										}
										// get stats for fortnite (game id 1), pc (platform id 1) and season (returns 0 if no record)
										$stats = getStats($id, $gid, $pid, $window);
										//var_dump($stats);
									} else {
										$stats = 0;
									}
									if ($stats != 0) {
										if (count($rank) < 1) {
								?>
								<div class="row">
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px; text-align: center; margin: 0;">
										<span style="font-size: 20px; font-weight: bold;">SEASON ADDED, PLEASE REFRESH</span>
									</div>
								</div>
								<?PHP
										} else {
								?>
								<div class="row">
									<h3 style="margin-left: 10px; margin-bottom: 20px;">Season5 stats for '<?PHP echo $gname; ?>'</h3>
								</div>
								<div class="row">
									<span style="margin-left: 10px;">Senest opdateret: <?PHP echo date("F d, Y - H:i:s", $stats['unix']); ?> (<?PHP echo GetTimeDiff($stats['unix']); ?> ago)</span>
								</div>
								<div class="row">
									<!-- 7dage box -->
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header with-border bg-profile-7day">
											<h3 class="box-title">TOTAL</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="margin: 20px 14px 20px 14px;">
													<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score'])); ?></b></span>
													<div class="progress progress-xs" style="width: 100%; margin: 0;">
														<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['score']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
													</div>
													<span>#<?PHP echo $rank['score']["rank"]; ?></span>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="margin: 20px 0 0 0;">
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['winrate']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['wins']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['wins']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['kd']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills']["rank"]; ?></span>
													</div>
													<div style="width: 20%; min-width: 155px; float: left; padding: 0 14px 0 14px;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-darkgrey" style="width: <?PHP echo $rank['matchesplayed']["count"]; ?>%; background-color: <?PHP echo $bc_total; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- squad box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-squad">
											<h3 class="box-title bold">SQUAD</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_squad'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_squad']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_squad']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_squad']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_squad']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_squad']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_squad']["count"]; ?>%; background-color: <?PHP echo $bc_squad; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_squad']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- duo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-duo">
											<h3 class="box-title bold">DUO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_duo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_duo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_duo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_duo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_duo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_duo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_duo']["count"]; ?>%; background-color: <?PHP echo $bc_duo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_duo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- solo box -->
									<div class="box col-lg-4" style="box-shadow: 0px 0px 0px 0px; padding: 5px;">
										<div class="box-header without-border bg-profile-solo">
											<h3 class="box-title bold">SOLO</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body no-padding">
											<div class="table-responsive">
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<td style="width: 100%;">
														<span>Score <b style="float:right; font-size: 16px;"><?PHP echo str_replace(",", ".", number_format((float)$stats['score_solo'])); ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['score_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['score_solo']["rank"]; ?></span>
													</td>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Win% <b style="float:right; font-size: 16px;"><?PHP echo $stats['winrate_solo']; ?>%</b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['winrate_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['winrate_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>K/D <b style="float:right; font-size: 16px;"><?PHP echo $stats['kd_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kd_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kd_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Wins <b style="float:right; font-size: 16px;"><?PHP echo $stats['placetop1_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['placetop1_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['placetop1_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills <b style="float:right; font-size: 16px;"><?PHP echo $stats['kills_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kills_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kills_solo']["rank"]; ?></span>
													</div>
												</div>
												<hr style="margin: 0 14px;" />
												<div style="display: inline-block; width: 100%; margin: 20px 0; padding: 0 14px;">
													<div style="width: 47%; float: left;">
														<span>Matches <b style="float:right; font-size: 16px;"><?PHP echo $stats['matchesplayed_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['matchesplayed_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['matchesplayed_solo']["rank"]; ?></span>
													</div>
													<div style="width: 47%; float: right;">
														<span>Kills pr. match <b style="float:right; font-size: 16px;"><?PHP echo $stats['kpg_solo']; ?></b></span>
														<div class="progress progress-xs" style="width: 100%; margin: 0;">
															<div class="progress-bar progress-bar-danger" style="width: <?PHP echo $rank['kpg_solo']["count"]; ?>%; background-color: <?PHP echo $bc_solo; ?>;"></div>
														</div>
														<span>#<?PHP echo $rank['kpg_solo']["rank"]; ?></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.Stats row -->
								<?PHP
										}
									} else {
								?>
								<div class="row">
									<div class="box col-lg-12" style="box-shadow: 0px 0px 0px 0px; padding: 5px; text-align: center; margin: 0;">
										<span style="font-size: 20px; font-weight: bold;">NO STATS FOUND</span>
									</div>
								</div>
								<?PHP
									}
								?>
							</div>
							<!-- /.tab-pane -->
							<div class="tab-pane" id="settings">
								<h6 class="mt-15 mb-5">OBS! - Sanktioner ved forkert Display Name</h6>
								<!-- Input addon -->
								<?PHP
									foreach ($gametags as $gts) {
								?>
								<div>
									<?PHP
										foreach ($gts as $gt) {
											$pid = $gt["pid"];
											$game = $gt["game"];
											if ($pid == 1) {
												$icon = "mdi-desktop-mac";
												$title = "PC / MAC";
											} elseif ($pid == 2) {
												$icon = "mdi-playstation";
												$title = "Playstation 4";
											} else {
												$icon = "mdi-xbox";
												$title = "XBox One";
											}
											if (!isset($display1[$game])) {
									?>
									<div style="margin-top: 20px;"><span><?PHP echo $game; ?></span></div>
									<?PHP
												$display1[$game] = true;
											}
									?>
									<div class="box pull-up" style="display: inline-block; width: 32%; min-width: 290px; margin-right: 10px;">
										<div class="box-header with-border" style="text-align: center;">
											<h4 class="box-title"><i class="mdi <?PHP echo $icon; ?>" title="<?PHP echo $title; ?>"></i> <?PHP echo $gt["name"]; ?></h4>
										</div>
										<div class="box-body bg-hexagons-dark">
											<div class="media align-items-center">
												<!--<i class="mdi mdi-desktop-mac"></i>-->
												<div style="margin: auto;">
													<div style="display: inline-block;">
														<a class="btn btn-block btn-info" href="#">Rediger</a>
													</div>
													<div style="display: inline-block;">
														<a class="btn btn-block btn-danger" href="#" disabled>Fjern</a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?PHP
										}
										if (count($gts) <= 3) {
									?>
									<div class="box pull-up" style="display: inline-block; width: 32%; min-width: 290px; margin-right: 10px;">
										<div class="box-header with-border" style="text-align: center;">
											<h4 class="box-title">Tilføj gametag</h4>
										</div>
										<div class="box-body bg-hexagons-dark">
											<div class="media align-items-center">
												<!--<i class="mdi mdi-desktop-mac"></i>-->
												<div style="margin: auto;">
													<div style="display: inline-block;">
														<a class="btn btn-block btn-epic" href="#">Tilføj</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<?PHP
										}
									}
								?>
								<div style="margin-top: 50px;"><h6 class="mt-15 mb-5">Tilknyttede medier</h6></div>
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon" title="Facebook"><i class="mdi mdi-facebook"></i></span>
									<input type="text" class="form-control" placeholder="indsæt link til din facebook profil/side">
								</div>
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon" title="Youtube"><i class="mdi mdi-youtube-play"></i></span>
									<input type="text" class="form-control" placeholder="indsæt link til din youtube kanal">
								</div>
								<div class="input-group" style="margin-bottom: 10px;">
									<span class="input-group-addon" title="Twitch"><i class="mdi mdi-twitch"></i></span>
									<input type="text" class="form-control" placeholder="indsæt link til din twitch kanal">
								</div>
							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>
					<!-- /.nav-tabs-custom -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
<?php
	}
?>