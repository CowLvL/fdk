<?php

?>
<script type="text/javascript">

!function($) {
    "use strict";

    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('.sa-assign-decline').click(function(e){
      var tournament_id = $(e.currentTarget).data("user-id");
      swal({
        title: 'Afmeld turnering?',
        text: "Er du sikker på du vil afmelde?",
        type: 'warning',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Ja, afmeld!',
        cancelButtonText: 'Annuller'
      }).then((result) => {
        if (result.value) {
          $.ajax({
              type: "POST",
              url: "/engine/tournament-invite-decline.php",
              data: { 'tournament_id': tournament_id},
              cache: false,
              success: function() {
                swal(
                  'Afmeldt!',
                  'Afmelding er registeret.',
                  'success'
                )
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
