"use strict";
var RVProject = function() {

	var initTable1 = function() {
		var table = $('#kt_table_1');

		// begin first table
		table.DataTable({
			responsive: true,
			dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

			buttons: [
				'print',
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5',
			],
			paging: true,
			columnDefs: [
				{
					targets: -1,
					title: 'Actions',
					orderable: false,
				},
			],
		});
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();

			$('.taxdelete').on('click',function(e){
		    e.preventDefault();
		    var result = confirm("Want to delete?");
		    if (result) {
					let id = $(this).data('id');
					let tax = $(this).data('tax');
		      $.ajax({
		         type : "POST",
		         url : rvlocalize.ajaxurl,
		         data : {
		            action:"rv_del_tax",
		            cid: id,
								tax: tax,
		        },
		        success: function(response) {
		            if(response.success) {
		              toastr.success("Successfully deleted!");
		              setTimeout(function(){
		                location.reload();
		              },500);

		            }
		          }
		      });
		    }
		  });
		}
	};
}();

jQuery(document).ready(function() {
	RVProject.init();
});
