<?php

?>
<script type="text/javascript">

!function($) {
    "use strict";

    var SweetAlert = function() {};

    //examples 
    SweetAlert.prototype.init = function() {

    // assign players for tournament
    $('#sa-upload-team-picture').click(function(){

        // inputOptions can be an object or Promise
        var inputOptions = new Promise((resolve) => {
          setTimeout(() => {
            resolve({
              '#ff0000': 'Red',
              '#00ff00': 'Green',
              '#0000ff': 'Blue'
            })
          }, 2000)
        })

        const {value: color} = await swal({
          title: 'Select color',
          input: 'radio',
          inputOptions: inputOptions,
          inputValidator: (value) => {
            return !value && 'You need to choose something!'
          }
        })

        if (color) {
          swal({html: 'You selected: ' + result})
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
