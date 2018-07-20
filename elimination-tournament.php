<?PHP
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">

<form method="GET">
	<input type="text" name="person">
	<button>SUBMIT</button>
</form>

<?php
	echo (isset($_GET['person'])) ? $_GET['person']." er fræk" : "Indtast navn";
?>

</section>
</div>