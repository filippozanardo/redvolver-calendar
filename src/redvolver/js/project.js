"use strict";
var RVProject = function() {

	var initTable1 = function() {

		$.fn.dataTable.moment('DD/MM/YYYY');

		var table = $('#project_table');

		// begin first table
		table.DataTable({
			// fixedHeader: true,
			// scrollY: '500px',
			// scrollCollapse: true,
			// scroller: true,
			responsive: true,
			dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
			<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
			pageLength: 50,
			lengthMenu: [ 25, 50, 75, 100 ],
			stateSave: true,
			// colReorder: {
			// 	fixedColumnsRight: 1
			// },
			colReorder: true,
			buttons: [
				'colvis',
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

			$('#single_project_table').DataTable({
				dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
					<'row'<'col-sm-12'tr>>
					<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
				responsive: true,
				paging : false,
				buttons: [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5',
					'pdfHtml5'
						],
			});


			$(document).on('click','.getprojectpdf',function(e){
				e.preventDefault();
				let id = $(this).data('id');
				$.ajax({
					 type : "POST",
					 url : rvlocalize.ajaxurl,
					 data : {
							action:"get_project_pdf",
							id: id,
					},
					success: function(response) {
						console.log(response);
						if ( response.success ) {
							var a = document.createElement("a");
							a.href = response.data.redirect;
							a.setAttribute("download", response.data.name);
							a.click();
						}


					}
				});

			});

		}
	};
}();

jQuery(document).ready(function() {
	RVProject.init();
});
