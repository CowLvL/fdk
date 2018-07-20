<?php
	ini_set('display_errors', 1); error_reporting(E_ALL);

	// set session
	session_start();

	// set locale
	setlocale(LC_ALL, "da_DK.UTF-8", "Danish_Denmark.1252", "danish_denmark", "danish", "dk_DK@euro");
	ini_set("date.timezone", "Europe/Copenhagen");

	$page = $_REQUEST['page'];
	$profile = (isset($_REQUEST['profile'])) ? $_REQUEST['profile'] : "";
	$team = (isset($_REQUEST['team'])) ? $_REQUEST['team'] : "";

	// include functions
	include "engine/functions.php";

	// require facebook login config
	require_once "engine/fb-config.php";

	// contruct login url
	$redirectURL = "https://fortnitedanmark.dk/engine/fb-callback.php";
	$permissions = ['email'];
	$loginURL = $helper->getLoginUrl($redirectURL, $permissions);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Thornament</title>

		<!-- Bootstrap 4.0-->
		<link rel="stylesheet" href="/assets/vendor_components/bootstrap/dist/css/bootstrap.css">
		<!--amcharts -->
		<link href="https://www.amcharts.com/lib/3/plugins/export/export.css" rel="stylesheet" type="text/css" />
		<!-- Bootstrap-extend -->
		<link rel="stylesheet" href="/css/bootstrap-extend.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="/css/font-awesome-animation.min.css">
		<!-- daterange picker --> 
		<link rel="stylesheet" href="/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.css">
		<!-- bootstrap datepicker --> 
		<link rel="stylesheet" href="/assets/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
		<!-- Bootstrap time Picker -->
		<link rel="stylesheet" href="/assets/vendor_plugins/timepicker/bootstrap-timepicker.css">
		<!-- Select2 -->
		<link rel="stylesheet" href="/assets/vendor_components/select2/dist/css/select2.min.css">
		<!-- theme style -->
		<link rel="stylesheet" href="/css/master_style.css">
		<!-- Crypto_Admin skins -->
		<link rel="stylesheet" href="/css/skins/_all-skins.css">
		<!--alerts CSS -->
		<link href="/assets/vendor_components/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
		<!-- flipclock-->
		<link rel="stylesheet" href="/assets/vendor_components/FlipClock-master/compiled/flipclock.css">
		<?PHP
			$brackets = array("bracket64", "bracket32", "bracket16", "test123");
			if (in_array($page, $brackets)) {
		?>
		<!-- bracket-->
		<link rel="stylesheet" href="/css/bracket.css">
		<?PHP
			}
		?>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery 3 -->
		<script src="/assets/vendor_components/jquery/dist/jquery.min.js"></script>
		<!-- popper -->
		<script src="/assets/vendor_components/popper/dist/popper.min.js"></script>
		<!-- Bootstrap 4.0-->
		<script src="/assets/vendor_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- bootstrap time picker -->
		<script src="/assets/vendor_plugins/timepicker/bootstrap-timepicker.min.js"></script>
		<!-- Select2 -->
		<script src="/assets/vendor_components/select2/dist/js/select2.full.js"></script>
		<!-- SlimScroll -->
		<script src="/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<!-- FastClick -->
		<script src="/assets/vendor_components/fastclick/lib/fastclick.js"></script>
		<!-- FlipCLock -->
		<script src="/assets/vendor_components/FlipClock-master/compiled/flipclock.min.js"></script>
		<!-- This is data table -->
		<script src="/assets/vendor_plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js"></script>
		<!-- DataTables -->
		<script src="/assets/vendor_components/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="/assets/vendor_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="/js/pages/data-table.js"></script>
		<!-- steps  -->
		<script src="/assets/vendor_components/jquery-steps-master/build/jquery.steps.js"></script>
		<!-- validate  -->
		<script src="/assets/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.js"></script>
		<!-- wizard  -->
		<script src="/js/pages/steps.js"></script>
		<!-- Sweet-Alert  -->
		<script src="/assets/vendor_components/sweetalert/sweetalert2.all.js"></script>
		<!-- InputMask -->
		<script src="/assets/vendor_plugins/input-mask/jquery.inputmask.js"></script>
		<script src="/assets/vendor_plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
		<script src="/assets/vendor_plugins/input-mask/jquery.inputmask.extensions.js"></script>
		<!-- date-range-picker -->
		<script src="/assets/vendor_components/moment/min/moment.min.js"></script>
		<script src="/assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap datepicker -->
		<script src="/assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<script src="/assets/vendor_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.da.min.js"></script>
		<!-- bootstrap color picker -->
		<script src="/assets/vendor_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
		<!-- slimscroll -->
		<script src="/assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
		<!-- iCheck 1.0.1 -->
		<script src="/assets/vendor_plugins/iCheck/icheck.min.js"></script>
		<!-- Crypto_Admin for advanced form element -->
		<script src="/js/pages/advanced-form-element.js"></script>
		<!-- Crypto_Admin App -->
		<script src="/js/template.js"></script>
		<!-- Crypto_Admin for demo purposes -->
		<script src="/js/demo.js"></script>
		<!-- Gametag -->
		<script src="/js/gametag.js"></script>

		<script type="text/javascript">
			getInvites();
		</script>
	</head>
	<?php
		// check if user is logged in
		if (empty($_SESSION['access_token'])) { 
			include('login.php');
		} else {
			if (getUserInfo(0, "locked") == 1) {
				include "locked.php";
			} else {
				include "wrapper.php";
			}
		}
	?>
</html>