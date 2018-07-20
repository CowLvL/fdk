<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">

    <!-- Validation wizard -->
    <div class="box box-solid bg-dark">
      <div class="box-header with-border">
        <h3 class="box-title">Opret turnerinng</h3>
        <h6 class="box-subtitle">Udfyld nedenstående felter</h6>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body wizard-content">
        <form action="/engine/create-tournament.php" enctype="multipart/form-data" method="post" class="validation-wizard wizard-circle" id="create-tournament">
        <!-- Step 1 -->
        <h6>Basis information</h6>
        <section class="bg-hexagons-dark">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label> Turneringsnavn: </label><label style="padding-left: 10px;" id="error_handler"></label>
                <input type="text" class="form-control tour_name" id="tour_name" name="name">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label> Tilmeldingsform: <span class="danger">*</span> </label>
                <select class="custom-select form-control required" id="tour_invite" name="invite">
                  <option value="">Vælg</option>
                  <option value="open"> Åben</option>
                  <option value="invite"> Invite</option>
                  <option value="request"> Request</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label> Vælg platform: <span class="danger">*</span> </label>
                <select class="custom-select form-control required" id="tour_platform" name="platform">
                  <option value="">Vælg</option>
                  <option value="PC"> PC</option>
                  <option value="Playstation"> Playstation</option>
                  <option value="Crossplay"> Crossplay</option>
                  <option value="XBOX"> XBOX</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label> Vælg type: <span class="danger">*</span> </label>
                <select class="custom-select form-control required" id="tour_type" name="type">
                  <option value="">Vælg</option>
                  <option value="solo"> Solo</option>
                  <option value="duo"> Duo</option>
                  <option value="squad"> Squad</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Dato for turneringsstart: <span class="danger">*</span></label>
                <div class="input-group date" id="tour_start-date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right required" id="datepicker" name="start_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-3">
              <div class="bootstrap-timepicker">
              <div class="form-group">
                <label>Klokkeslæt for turneringsstart: <span class="danger">*</span></label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker required" id="tour_start-time" name="start_time">
                </div>
                <!-- /.input group -->
              </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group date" style="padding-top: 40px;">
                  <input type="checkbox" id="basic_checkbox_1" value="1" name="tour_advanced" />
                  <label for="basic_checkbox_1">Avanceret</label>
                </div>
                <!-- /.input group -->
              </div>
            </div>
          </div>
          <div class="row" id="create-tournament-advanced">
            <div class="col-md-2">
              <div class="form-group">
                <label>Dato for tilmeldingsfrist:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker1" name="deadline_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-2">
              <div class="bootstrap-timepicker">
              <div class="form-group">
                <label>Klokkeslæt for tilmeldingsfrist:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker1" name="deadline_time">
                </div>
                <!-- /.input group -->
              </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Dato for turneringsvisning:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker2" name="publish_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-2">
              <div class="bootstrap-timepicker">
              <div class="form-group">
                <label>Klokkeslæt for turneringsvisning:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker2" name="publish_time">
                </div>
                <!-- /.input group -->
              </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Dato for tilmeldingsåbning:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="datepicker3" name="signup_date">
                </div>
                <!-- /.input group -->
              </div>
            </div>
            <div class="col-md-2">
              <div class="bootstrap-timepicker">
              <div class="form-group">
                <label>Klokkeslæt for tilmeldingsåbning:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                  <input type="text" class="form-control timepicker3" name="signup_time">
                </div>
                <!-- /.input group -->
              </div>
              </div>
            </div>
          </div>
          </section>

          <!-- Step 2 -->
          <h6>Arrangør information</h6>
          <section class="bg-hexagons-dark">

          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label for="jobTitle3">Arrangør:</label>
                <input type="text" class="form-control" id="tour_sponsor" name="sponsor">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="webUrl3">Arrangør URL:</label>
                <input type="url" class="form-control" id="tour_sponsor_www" name="sponsor_www">
              </div>
            </div>
            <!-- Fee -->
            <div class="col-md-6">
              <div class="form-group validate">
                <label for="webUrl3">Spiller indskud</label>
                <div class="input-group"> <span class="input-group-addon">kr.</span>
                  <input type="number" name="fee" class="form-control" > 
                </div>
              </div> 
            </div>
            <!-- Total prize sum -->
            <div class="col-md-6">
              <div class="form-group validate">
                <label for="webUrl3">Arrangør indskud:</label>
                <div class="input-group"> <span class="input-group-addon">kr.</span>
                  <input type="number" name="prize" class="form-control" > 
                </div>
              </div> 
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="shortDescription3">Tournerings kort (billede):</label>
                <input type="file" name ="file"> 
              </div>
            </div>
          </div>

          </section>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->