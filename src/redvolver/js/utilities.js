/*

      */
"use strict";

var RVUtil = function() {

    return {
        //main function to initiate the module
        init: function() {
          if ( $('#timesheetpicker').length > 0 ) {

            $('#timesheetpicker').datepicker({
                format: "mm/yyyy",
                //format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                //orientation: "bottom left",
            });
          }

          $('#usertimesheetgo').on('click',function(e){
              e.preventDefault();

              let thedate = $('#timesheetpicker').val();
              let theuser = $('#usertimesheet').val();

              if ( thedate === '' ) {
                alert('Seleziona una data');
                return false;
              }

              if ( theuser === '' ) {
                alert('Seleziona un utente');
                return false;
              }

              let splitdate = thedate.split('/');
              let month = splitdate[0];
              let year = splitdate[1];
              // KTApp.block($('#port-let'), {
              //     overlayColor: '#ffffff',
              //     type: 'loader',
              //     state: 'success',
              //     opacity: 0.3,
              //     size: 'lg'
              // });

              $.ajax({
          			type : 'POST',
          			url : rvlocalize.ajaxurl,
          			data : {
                  action:"rv_get_timesheet_excel",
                  month: month,
                  year: year,
                  user: theuser,
                },
          			success: function(response) {
                  console.log(response);
                  if (response.success) {
                    toastr.success("Tienilo tu questo montaggio analogico!");
                    window.open(response.data.redirect);
                  }else{
                    toastr.error("Error please reload the page!");
                  }
                }
              });

          });

        }
    };
}();

jQuery(document).ready(function() {
    RVUtil.init();
});
