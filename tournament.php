<?php
$tournament = $_REQUEST["tournament"];
// check if user exists
if (tournamentExists($tournament) == 0) {
	// user dosent exists
	include "no-page.php";
} else {
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-4">
			<?php getTournaments("fortnite", 1, 1, $tournament); ?>	
			

			<!-- Statusbox -->
			<div class="box bg-pale-success">
				<div class="box-header with-border">
				  <h4 class="box-title">Tilmeldingsstatus</h4>
				</div>
				<div class="box-body bg-hexagons-dark">
				  	<div class="media flex-column text-center p-40">
					  <span class="avatar avatar-xxl bg-success opacity-60 mx-auto">
						<i class="align-center ion ion-checkmark font-size-60" style="line-height: inherit;"></i>
					  </span>
					  <div class="mt-20">
						  <h3 class="text-uppercase fw-500">Tilmeldt!</h3>
						  <br> eyal
						  <br> deffen
						  <br> SpyN
						  <br> QuakE

					  </div>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>


			<!-- Statusbox -->
			<div class="box bg-pale-warning">
				<div class="box-header with-border">
				  <h4 class="box-title">Tilmeldingsstatus</h4>
				</div>
				<div class="box-body bg-hexagons-dark">
				  	<div class="media flex-column text-center p-40">
					  <span class="avatar avatar-xxl bg-warning opacity-60 mx-auto">
						<i class="align-center ion ion-alert font-size-60"></i>
					  </span>
					  <div class="mt-20">
						  <h3 class="text-uppercase fw-500">Afventer spillere:</h3>
						  <br> <i class="ion ion-help"></i> eyal
						  <br> <i class="ion ion-help"></i> deffen
						  <br> <i class="ion ion-checkmark"></i> SpyN
						  <br> <i class="ion ion-checkmark"></i> QuakE

					  </div>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>

			<!-- Statusbox -->
			<div class="box bg-pale-danger">
				<div class="box-header with-border">
				  <h4 class="box-title">Tilmeldingsstatus</h4>
				</div>
				<div class="box-body bg-hexagons-dark">
				  	<div class="media flex-column text-center p-40">
					  <span class="avatar avatar-xxl bg-danger opacity-60 mx-auto">
						<i class="align-center ion ion-close font-size-60"></i>
					  </span>
					  <div class="mt-20">
						  <h3 class="text-uppercase fw-500">Spiller har afvist.<br>Tilmeld på ny.</h3>
						  <br> <i class="ion ion-close"></i> eyal
						  <br> <i class="ion ion-checkmark"></i> deffen
						  <br> <i class="ion ion-checkmark"></i> SpyN
						  <br> <i class="ion ion-checkmark"></i> QuakE

					  </div>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>


			<!-- Statusbox -->
			<div class="box bg-pale-danger">
				<div class="box-header with-border">
				  <h4 class="box-title">Tilmeldingsstatus</h4>
				</div>
				<div class="box-body bg-hexagons-dark">
				  	<div class="media flex-column text-center p-40">
					  <span class="avatar avatar-xxl bg-danger opacity-60 mx-auto">
						<i class="align-center ion ion-close font-size-60"></i>
					  </span>
					  <div class="mt-20">
						  <h3 class="text-uppercase fw-500">Turneringen er fyldt.<br>Spiller nåede ikke at acceptere.</h3>
						  <br> <i class="ion ion-close"></i> eyal
						  <br> <i class="ion ion-checkmark"></i> deffen
						  <br> <i class="ion ion-checkmark"></i> SpyN
						  <br> <i class="ion ion-checkmark"></i> QuakE
					  </div>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>


			<!-- Statusbox -->
			<div class="box bg-lighter">
				<div class="box-header with-border">
				  <h4 class="box-title">Tilmeldingsstatus</h4>
				</div>
				<div class="box-body bg-hexagons-dark">
				  	<div class="media flex-column text-center p-40">
					  <span class="avatar avatar-xxl bg-pale-dark opacity-60 mx-auto">
						<i class="align-center ion ion-help font-size-60"></i>
					  </span>
					  <div class="mt-20">
						  <h3 class="text-uppercase fw-500">Tilmelding er åben</h3>


					  </div>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>

		</div>

		<!-- Højre kolonne -->
		<div class="col-8">
			<!-- Nedtælling -->
			<div class="box box-dark bg-hexagons-white">
				<div class="box-body">
					<h5 class="text-white text-center">Tid tilbage før Custom Key</h5>
					<div class="countdownv2_holder text-center mb-50 mt-20">
						<div class="clock"></div>
					</div>


					<h6 class="text-white text-left mb-2">Turneringen starter om:  <span class="text-bold">X dage / X Timer / X Minutter</span></h6>
					<div class="progress mb-2">
						<div class="progress-bar progress-bar-warning progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
							<span class="text-left pl-2">60 tilmeldte</span>
						</div>
					</div>
					<ul class="flexbox flex-justified text-center my-10">

					</ul>
				</div>
			</div>
			<!-- /.nedtæling -->

         <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Tilmeldte spillere</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div class="table-responsive">
				  <table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Spiller</th>
							<th>Team</th>
							<th>Rank</th>
							<th>Platform</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
						<tr>
							<td>Spillernavn</td>
							<td>Teamnavn</td>
							<td>Medlem/Streamer</td>
							<td>PC</td>
						</tr>
					</tfoot>
				  </table>
				</div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->  


		</div>





	</div>
    <!-- /.content -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
}
?>