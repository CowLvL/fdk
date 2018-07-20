<?php

?>
<script type="text/javascript">

!function($) {
    "use strict";

    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('.sa-assign-duo-answer').click(function(e){
      var tournament_id = $(e.currentTarget).data("user-id");


      $.ajax({
        type: "POST",
        url: "/engine/tournament-get-invites.php",
        data: {
          'tournament_id': tournament_id
        },
        cache: false,
        success: function(invites) {
          
          swal({
            title: 'Invitiationer',
            html: invites
          });

          $("#tournamentInvitesForm button").on({
            click: function() {
              //Loading the variable
              var string = $(this).val();

              //Splitting it with : as the separator
              var explode = string.split(",");
              var response = explode[0];
              var tournament_id = explode[1];
              var invite_id = explode[2];

              $.ajax({
                type: "POST",
                url: "/engine/tournament-invite-response.php",
                data: { 
                response: response,
                tournament_id: tournament_id,
                invite_id: invite_id
                }, 
                success: function(result) {
                  //alert(result);
                  if(result == "declined") {
                    swal({
                      type: 'error',
                      title: 'Invitation afvist',
                      showConfirmButton: false,
                      timer: 1500
                    })
                  } else if(result == "accepted") {
                    swal({
                      type: 'success',
                      title: 'Invitation accepteret',
                      showConfirmButton: false,
                      timer: 1500
                    })
                  } else if(result == "full") {
                    swal({
                      type: 'error',
                      title: 'Turneringen er fyldt',
                      showConfirmButton: true
                    })
                  }
                }
              });
 
            }
          });
          
        }
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
