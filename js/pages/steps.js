$(".tab-wizard").steps({
    headerTag: "h6"
    , bodyTag: "section"
    , transitionEffect: "none"
    , titleTemplate: '<span class="step">#index#</span> #title#'
    , labels: {
        finish: "Submit"
    }
    , onFinished: function (event, currentIndex) {
       swal("Your Form Submitted!", "Sed dignissim lacinia nunc. Curabitur tortor. Pellentesque nibh. Aenean quam. In scelerisque sem at dolor. Maecenas mattis. Sed convallis tristique sem. Proin ut ligula vel nunc egestas porttitor.");
            
    }
});


var form = $(".validation-wizard").show();



$(".validation-wizard").steps({
    headerTag: "h6"
    , bodyTag: "section"
    , transitionEffect: "none"
    , titleTemplate: '<span class="step">#index#</span> #title#'
    , labels: {
        finish: "Submit"
    }
    , onStepChanging: function (event, currentIndex, newIndex) {
        return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && ($("#tour_name").attr("class") != "has-danger form-control tour_name") && ($("#tour_name").attr("class") != "has-warning form-control tour_name") && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
    }
    , onFinishing: function (event, currentIndex) {
        return form.validate().settings.ignore = ":disabled", form.valid()
    }
    , onFinished: function (event, currentIndex) {
      var form = $('#create-tournament')[0];
      var formData = new FormData(form);
       $.ajax({
            type: "POST",
            url: "/engine/create-tournament.php",
            contentType: false,
            processData: false,
            data: formData,
            success: function(team_id) {
                swal({
                type: "success",
                title:"Your note has been saved!",
                text: "success"
                }).then(function() {
                  //window.location.href = "/team/" + team_id;
                  alert(team_id);
              })
            }
          });
    }
}), $(".validation-wizard").validate({
    ignore: "input[type=hidden]"
    , errorClass: "text-danger"
    , successClass: "text-success"
    , highlight: function (element, errorClass) {
        $(element).removeClass(errorClass)
    }
    , unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass)
    }
    , errorPlacement: function (error, element) {
        error.insertAfter(element)
    }
    , rules: {
        email: {
            email: !0
        }
    }
})

var x_timer;    
$("input.form-control.tour_name").keyup(function (e){
  clearTimeout(x_timer);
  var tournament_name = $(this).val();
  x_timer = setTimeout(function(){
    check_tournament_name_ajax(tournament_name);
  }, 1);
});

function check_tournament_name_ajax(tournament_name){
  $.post("/engine/tournamentname-checker.php", {"tournament_name":tournament_name}, function(data) {
    if (tournament_name.length == 0) {
      $("#tour_name").attr("class","form-control tour_name");
      $("#error_handler").attr("for","");
      $("#error_handler").html("");
    } else if (tournament_name.length < 2) {
      $("#tour_name").attr("class","has-warning form-control tour_name");
      $("#error_handler").attr("for","inputWarning");
      $("#error_handler").html(" <i class=\"fa fa-bell-o\"></i> Navnet er for kort.");
    } else {
      $("#tour_name").attr("class",data+" form-control tour_name");
      if(data == "has-success") {
        $("#error_handler").attr("for","inputSuccess");
        $("#error_handler").html(" <i class=\"fa fa-check\"></i> Navnet er ledigt.");
      } else if(data == "has-danger") {
        $("#error_handler").attr("for","inputError");
        $("#error_handler").html(" <i class=\"fa fa-times-circle-o\"></i> Navnet er optaget.");
      }
    }
  });
}
