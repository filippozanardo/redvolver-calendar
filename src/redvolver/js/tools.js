"use strict";
var RVTools = function() {

	return {

		//main function to initiate the module
		init: function() {

      $(document).on('click','.timesheet-excel',function(e) {
          e.preventDefault();
          console.log('excel');

          let month = $(this).data('month');
          let year = $(this).data('year');
          
          $.ajax({
      			type : 'POST',
      			url : rvlocalize.ajaxurl,
      			data : {
              action:"rv_get_timesheet_excel",
              month: month,
              year: year,
            },
      			success: function(response) {
              console.log(response);

              if (response.success) {
                toastr.success("Tienilo tu questo montaggio analogico!");
                window.open(response.data.redirect);
              }else{
                toastr.error("Error please reload the page!");
              }
      			},
      			error : function(){
              console.log('error');
            },
      		});

        });

		}
	};
}();

jQuery(document).ready(function() {
	RVTools.init();
});
