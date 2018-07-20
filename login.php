<body class="hold-transition login-page">
	<?PHP
		include "engine/swal-show-tos.php";
	?>
	<script type="text/javascript">
		$(function() {
		});
		if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$("video").children().filter("video").each(function(){
				this.pause();
				$(this).remove();
			});
			$("video").empty();  
		}
	</script>
	<video autoplay muted loop id="myVideo">
		<source src="images/login-splash-video.mp4" type="video/mp4">
	</video>
	<div class="login-box">
		<div>
			<span style="display: block;" class="login-logo"><b>Thor</b>nament</span>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<div><span class="login-box-tos">Ved at anvende vores site accepterer du vores <a href="#tos" id="tos" class="show-tos">vilkår</a>.</span></div>
			<div class="social-auth-links text-center">
				<button class="btn btn-block btn-social btn-facebook" onclick="window.location = '<?php echo $loginURL; ?>'">
					<i class="mdi mdi-facebook"></i> Sign in with Facebook
				</button>
			</div>
			<!-- /.social-auth-links -->
		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->
</body>