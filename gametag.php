<?PHP
	// check and request epic_id
	if (!isset($_REQUEST["profile"])) {
		$profile = getUserInfo(0, "user_id", 0);
	}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div style="width:100%;">
				<span style="display:inline-block; width:100px; text-align:right; padding-right: 5px;">Game:</span>
				<select id="game">
					<?PHP
						getGames();
					?>
				</select>
			</div>
			<div style="width:100%;">
				<span style="display:inline-block; width:100px; text-align:right; padding-right: 5px;">Platform:</span>
				<select id="platform">
					<?PHP
						getPlatforms();
					?>
				</select>
			</div>
			<div style="width:100%;">
				<span style="display:inline-block; width:100px; text-align:right; padding-right: 5px;">Gametag:</span>
				<input type="text" id="gametag" alt="" />
			</div>
			<div style="width:100%;">
				<span style="display:inline-block; width:100px; text-align:right; padding-right: 5px;"></span>
				<button id="gt_search">SEARCH</button>
			</div>
		</div>
		<div class="row" style="margin-top: 20px;">
			<span id="result"></span>
			<?PHP
				//echo getUserInfo($profile, "epic_id")."<br /><br />";
				//$epic = getGametagInfo("PiXiBiN I CowLvL", "pc");
				//echo $epic->epicUserHandle;
			?>
		</div>
	</section>
</div>
