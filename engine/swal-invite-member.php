<?php

?>
<script type="text/javascript">

!function($) {
    "use strict";

    var SweetAlert = function() {};

    var createteamcontent = '<div class="box-body">' +
        '<div class="form-group" id="swal-user-name">' +
          '<input type="text" name="swal-user-name" id="swal-user-name" class="form-control swal-user-name" placeholder="Navn#Nummer">' +
          '<input type="hidden" name="swal-team-name" id="swal-team-name" value="<?php echo getTeamInfo($team, "id") ?>">' +
          '<div id="suggesstion-box"></div>' +
      '</div>' +
      '</div>';

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('#sa-invite-member').click(function(){
        swal({
          title: 'Inviter medlem',
          html: createteamcontent,
          showCancelButton: true,
          focusConfirm: true,
          reverseButtons: true,
          confirmButtonText: 'Inviter',
          cancelButtonText: 'Annuller',
        }).then((result) => {
          if (result.value) {
            var user_name = $("input.form-control.swal-user-name").val();
            var team_name = $("#swal-team-name").val();
            //alert(picked_name);
            $.ajax({
              type: "POST",
              url: "/engine/team-invite.php",
              data: {
                'name': user_name,
                'team': team_name
              },
              datatype: 'json',
              cache: false,
              success: function(response) {
                  response = JSON.parse(response);
                  var invitee = response.invitee;
                  var team = response["team"];
                  var responsecontent = invitee + " er nu inviteret til <b class=\"font-weight-600\">" + team + "</b>";
                  swal({
                  type: "success",
                  title:"Invitiation afsendt!",
                  html: responsecontent
                  }).then(function() {
                    //alert(team_id);
                })
              }
            });  
          }
        });
        swal.disableConfirmButton()

        $("#swal-user-name").keyup(function(){
          swal.disableConfirmButton()
          var user_name = $("input.form-control.swal-user-name").val();
          var team_name = $("#swal-team-name").val();
          $.ajax({
            type: "POST",
            url: "/engine/username-checker.php",
            data: { 
              'keyword': user_name,
              'team_name': team_name
            },
            success: function(data){
              setTimeout(function() {
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
                $("#swal-user-name").css("background","#FFF");
              }, 1);
            }
          });
        });

        
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
