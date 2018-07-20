<?php

?>
<script type="text/javascript">

!function($) {
    "use strict";

    var SweetAlert = function() {};

    var createteamcontent = '<div class="box-body">' +
        '<div class="form-group" id="team_name">' +
          '<label class="control-label" id="team_name_label"></label>' +
          '<input type="text" name="team_name" id="" class="form-control team_name" placeholder="Team navn">' +
      '</div>' +
      '</div>';

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('#sa-create-team').click(function(){
        swal({
          title: 'Opret team',
          html: createteamcontent,
          showCancelButton: true,
          focusConfirm: true,
          reverseButtons: true,
          confirmButtonText: 'Opret',
          cancelButtonText: 'Annuller',
        }).then((result) => {
          if (result.value) {
            var picked_name = $("input.form-control.team_name").val();
            //alert(picked_name);
            $.ajax({
              type: "POST",
              url: "/engine/create-team.php",
              data: { 'name': picked_name},
              cache: false,
              success: function(team_id) {
                  swal({
                  type: "success",
                  title:"Team oprettet!",
                  text: "Dit team er nu oprettet."
                  }).then(function() {
                    window.location.href = "/team/" + team_id;
                })
              }
            });  
          }
        });
        swal.disableConfirmButton()

        var x_timer;    
        $("input.form-control.team_name").keyup(function (e){
          clearTimeout(x_timer);
          var team_name = $(this).val();
          x_timer = setTimeout(function(){
            check_team_name_ajax(team_name);
          }, 1);
        });

        function check_team_name_ajax(team_name){
          $.post("/engine/teamname-checker.php", {"team_name":team_name}, function(data) {
            if (team_name.length < 2) {
              $("#team_name").attr("class","has-warning");
              $("#team_name_label").attr("for","inputWarning");
              $("#team_name_label").html("<i class=\"fa fa-bell-o\"></i> Navnet er for kort.");
              swal.disableConfirmButton()
            } else {
              $("#team_name").attr("class",data);
              if(data == "has-success") {
                $("#team_name_label").attr("for","inputSuccess");
                $("#team_name_label").html("<i class=\"fa fa-check\"></i> Navnet er ledigt.");
                swal.enableConfirmButton();
              } else if(data == "has-danger") {
                $("#team_name_label").attr("for","inputError");
                $("#team_name_label").html("<i class=\"fa fa-times-circle-o\"></i> Navnet er optaget.");
                swal.disableConfirmButton();
              }
            }
          });
        }
    });

    

    },
    //init
    $.SweetAlert = new SweetAlert, $.SweetAlert.Constructor = SweetAlert
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.SweetAlert.init()
}(window.jQuery);


</script>
