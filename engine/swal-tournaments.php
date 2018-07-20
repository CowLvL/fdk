<?php

?>
<script type="text/javascript">



!function($) {
    "use strict";

    var SweetAlert = function() {};

    var htmlteams = <?php echo getProfileTeamsSwal(getUserInfo(0, "id")); ?>;

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('.sa-assign-duo').click(function(e){
      var tournament_id = $(e.currentTarget).data("user-id");
        swal({
          title: 'Vælg team',
          html: htmlteams,
          showCancelButton: true,
          focusConfirm: true,
          reverseButtons: true,
          confirmButtonText: 'Næste',
          cancelButtonText: 'Annuller',
        }).then((result) => {
          if (result.value) {
            var team_id = $("input[name='tour_team']:checked").val();
            $.ajax({
              type: "POST",
              url: "/engine/tournamentsignup-get-members.php",
              data: { 'team_id': team_id},
              cache: false,
              success: function(data) {
                var limit = 1;
                $("input[type='checkbox']").on('change', function(evt) {
                   if($(this).siblings(':checked').length >= limit) {
                       this.checked = false;
                   }
                });

                function checkboxlimit(checkgroup, limit){
        					for (var i=0; i<checkgroup.length; i++){
        						checkgroup[i].onclick=function(){
        							var checkedcount=0
        							for (var i=0; i<checkgroup.length; i++)
        							checkedcount+=(checkgroup[i].checked)? 1 : 0
        							if (checkedcount>limit){
        								//alert("You can check a maximum of "+limit+" boxes.")
        								this.checked=false
        							}
        						}
        					}
        				}

                var htmlplayers = '<div id="test"><form id="memberform" name="memberform">' + 
                '<input type="hidden" name="team_id" value="' + team_id + '">' +
                '<input type="hidden" name="tournament_id" value="' + tournament_id + '">' +
                data + 
                '</form></div>';

                swal({
                  title: 'Vælg medspiller',
                  showCancelButton: true,
                  focusConfirm: true,
                  reverseButtons: true,
                  confirmButtonText: 'Inviter',
                  cancelButtonText: 'Annuller',
                  html: htmlplayers
                }).then((result) => {
                  if (result.value) {
                    var form = $('#memberform')[0];
                    var formData = new FormData(form);
                    $.ajax({
                      type: "POST",
                      url: "/engine/tournament-invite.php",
                      contentType: false,
                      processData: false,
                      data: formData,
                      success: function(response) {
                          swal({
                          type: "success",
                          title:"Invitiation afsendt!",
                          html: response
                          }).then(function() {
                            //alert(team_id);
                        })
                      }
                    });
                  }
                })
                swal.disableConfirmButton()
                var $checkboxes = $('#memberform input[type="checkbox"]');
        
                $checkboxes.change(function(){
                    var countCheckedCheckboxes = $checkboxes.filter(':checked').length;
                    if(countCheckedCheckboxes == 2) {
                      swal.enableConfirmButton()
                    } else if(countCheckedCheckboxes != 2) {
                      swal.disableConfirmButton()
                    }
                    //alert(countCheckedCheckboxes);
                });
                checkboxlimit(document.forms.memberform['player[]'], 2);
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
