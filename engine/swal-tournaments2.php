<?php

?>
<script type="text/javascript">
!function($) {
    "use strict";

    var SweetAlert = function() {};

    var htmlcontent = '<div class="">' +
  '<div class="box">' +
    '<div class="media-list media-list-divided media-list-hover">' +
      '<div class="media" style="padding: 5px;">' +
        '<div class="avatar avatar-sm status-success" href="#">' +
          '<img src="<?php echo getUserInfo(0, "picture"); ?>" alt="...">' +
        '</div>' +
         '<div class="media-body" style="line-height: 29px; height: 29px;">' +
          '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">' +
            '<strong><?php echo getUserInfo(0, "user_id"); ?> ' +
            '<input type="checkbox" id="basic_checkbox_1" />' +
			'<label for="basic_checkbox_1" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>' +
            '</strong>' +
          '</p>' +
        '</div>' +
      '</div>' +
      '<div class="media" style="padding: 5px;">' +
        '<div class="avatar avatar-sm status-success" href="#">' +
          '<img src="<?php echo getUserInfo(0, "picture"); ?>" alt="...">' +
        '</div>' +
        '<div class="media-body" style="line-height: 29px; height: 29px;">' +
          '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">' +
            '<strong><?php echo getUserInfo(0, "user_id"); ?> ' +
            '<input type="checkbox" id="basic_checkbox_2" />' +
			'<label for="basic_checkbox_2" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>' +
            '</strong>' +
          '</p>' +
        '</div>' +
      '</div>' +
      '<div class="media" style="padding: 5px;">' +
        '<div class="avatar avatar-sm status-success" href="#">' +
          '<img src="<?php echo getUserInfo(0, "picture"); ?>" alt="...">' +
        '</div>' +
        '<div class="media-body" style="line-height: 29px; height: 29px;">' +
          '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">' +
            '<strong><?php echo getUserInfo(0, "user_id"); ?> ' +
            '<input type="checkbox" id="basic_checkbox_3" />' +
			'<label for="basic_checkbox_3" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>' +
            '</strong>' +
          '</p>' +
        '</div>' +
      '</div>' +
      '<div class="media" style="padding: 5px;">' +
        '<div class="avatar avatar-sm status-success" href="#">' +
          '<img src="<?php echo getUserInfo(0, "picture"); ?>" alt="...">' +
        '</div>' +
        '<div class="media-body" style="line-height: 29px; height: 29px;">' +
          '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">' +
            '<strong><?php echo getUserInfo(0, "user_id"); ?> ' +
            '<input type="checkbox" id="basic_checkbox_4" />' +
			'<label for="basic_checkbox_4" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>' +
            '</strong>' +
          '</p>' +
        '</div>' +
      '</div>' +
      '<div class="media" style="padding: 5px;">' +
        '<div class="avatar avatar-sm status-success" href="#">' +
          '<img src="<?php echo getUserInfo(0, "picture"); ?>" alt="...">' +
        '</div>' +
        '<div class="media-body" style="line-height: 29px; height: 29px;">' +
          '<p style="line-height: 29px; height: 29px; text-align: left; font-size: 13px; font-family: Poppins;">' +
            '<strong><?php echo getUserInfo(0, "user_id"); ?> ' +
            '<input type="checkbox" id="basic_checkbox_5" />' +
			'<label for="basic_checkbox_5" style="float: right; line-height: 29px; height: 29px; margin-top: 4px;"></label>' +
            '</strong>' +
          '</p>' +
        '</div>' +
      '</div>' +
    '</div>' +
  '</div>' +
'</div>';

    var htmlteams = <?php echo getProfileTeamsSwal(getUserInfo(0, "id")); ?>;
    var htmlplayers = "Team id: " + $("input[name='tour_team']:checked").val();

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('#sa-assign-duo').click(function(){
      swal.setDefaults({
  		  confirmButtonText: 'Næste',
  		  cancelButtonText: "Annuller",
  		  showCancelButton: true,
  		  reverseButtons: true,
  		  progressSteps: ['1', '2', '3']
		  })

		var steps = [
		  {
		    title: 'disclaimer',
		    html: 'disclaimer'
		  },
		  {
		    title: 'Vælg team',
		    html: htmlteams
		  },
      {
        title: 'Vælg spillere',
        html: htmlplayers
      }
		]

		swal.queue(steps).then((result) => {
		  swal.resetDefaults()

		  if (result.value) {
		    swal(
		      'Requests send!',
		      'Your tournament participation requests has been send to the selected player(s).',
		      'success'
		    )
		  } else if (
		    // Read more about handling dismissals
		    result.dismiss === swal.DismissReason.cancel
		  ) {
		    swal(
		      'Cancelled',
		      'Your signup was cancelled.',
		      'error'
		    )
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
