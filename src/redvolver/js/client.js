"use strict";
let client_table;
var RVClient = function() {

	var initTable1 = function() {

		// var openIcon = function(cell, data, cell, row, options){ //plain text value
		// 	console.log('val',cell.getValue());
		// 	console.log('data',data);
		// 	console.log('cell',cell);
		// 	console.log('row',row);
		// 	console.log('options',options);
		//
		// 	//console.log(value, data, cell, row, options);
    // 	return "<i class='fa fa-search'></i>"
		// };

		client_table = new Tabulator("#client-tabulator", {
    	//ajaxURL:"http://www.getmydata.com/now", //ajax URL
			ajaxURL: rvlocalize.ajaxurl, //ajax URL
    	ajaxParams:{
				action:"get_client_tabulator",
			},
			height:"500px",
			layout:"fitColumns",
			responsiveLayout:"collapse",
	    columns:[
				{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
	        {
						title:"Client Name",
						field:"name",
						minWidth:300,
						responsive: 0,
						headerFilter:"input",
					},
					{
						title:"Commerciale",
						field:"referent",
						responsive: 0,
						headerFilter:"input",
					},
					{
						title:"Contatto",
						field:"contact",
						responsive: 0,
						headerFilter:"input",
					},
					{
						title:"Action",
						field:"action",
						headerSort:false,
						download:false,
						formatter:function(cell, formatterParams, onRendered){
						    //cell - the cell component
						    //formatterParams - parameters set for the column
						    //onRendered - function to call when the formatter has been rendered
								let html = '<a href="'+cell.getValue()+'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View"><i class="la la-edit"></i></a>';
								html += '<a href="#" data-id="'+cell.getRow().getData().id+'" class="delclient btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"><i class="la la-trash"></i></a>'


								return html;

						    //return "Mr" + cell.getValue(); //return the contents of the cell;
						},
					},
	        // {title:"Progress", field:"progress", width:150, formatter:"progress", sorter:"number", headerFilter:minMaxFilterEditor, headerFilterFunc:minMaxFilterFunction, headerFilterLiveFilter:false},
	        // {title:"Gender", field:"gender", editor:"select", editorParams:{values:{"male":"Male", "female":"Female"}}, headerFilter:true, headerFilterParams:{values:{"male":"Male", "female":"Female", "":""}}},
	        // {title:"Rating", field:"rating", editor:"star", hozAlign:"center", width:100, headerFilter:"number", headerFilterPlaceholder:"at least...", headerFilterFunc:">="},
	        // {title:"Favourite Color", field:"col", editor:"input", headerFilter:"select", headerFilterParams:{values:true}},
	        // {title:"Date Of Birth", field:"dob", hozAlign:"center", sorter:"date",  headerFilter:"input"},
	        // {title:"Driver", field:"car", hozAlign:"center", formatter:"tickCross",  headerFilter:"tickCross",  headerFilterParams:{"tristate":true},headerFilterEmptyCheck:function(value){return value === null}},
	    ],
		});


		// var table = $('#client_table');
		//
		// // begin first table
		// table.DataTable({
		// 	scrollY: '500px',
		// 	scrollCollapse: true,
		// 	scroller: true,
		// 	responsive: true,
		// 	dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
		// 	<'row'<'col-sm-12'tr>>
		// 	<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
		//
		// 	buttons: [
		// 		'print',
		// 		'copyHtml5',
		// 		'excelHtml5',
		// 		'csvHtml5',
		// 		'pdfHtml5',
		// 	],
		// 	paging: true,
		// 	columnDefs: [
		// 		{
		// 			targets: -1,
		// 			title: 'Actions',
		// 			orderable: false,
		// 		},
		// 	],
		// });
	};

	return {

		//main function to initiate the module
		init: function() {

			if ( $('#client-tabulator').length > 0 ) {
				initTable1();



				$(document).on('click','.delclient',function(e) {
					var result = confirm("Want to delete?");
					if (result) {
						let id = $(this).data('id');
						$.ajax({
							 type : "POST",
							 url : rvlocalize.ajaxurl,
							 data : {
									action:"del_post",
									id: id,
							},
							success: function(response) {
									if(response.success) {
										toastr.success("Successfully deleted!");
										setTimeout(function(){
											client_table.setData();
										},500);

									}
								}
						});
					}

				});

				$(document).on('click','#clientxls',function(e) {
					client_table.download("xlsx", "client.xlsx", {});
				});

				$(document).on('click','#clientpdf',function(e) {
					client_table.download("pdf", "client.pdf", {});
				});


			}

			// $('.taxdelete').on('click',function(e){
		  //   e.preventDefault();
		  //   var result = confirm("Want to delete?");
		  //   if (result) {
			// 		let id = $(this).data('id');
			// 		let tax = $(this).data('tax');
		  //     $.ajax({
		  //        type : "POST",
		  //        url : rvlocalize.ajaxurl,
		  //        data : {
		  //           action:"rv_del_tax",
		  //           cid: id,
			// 					tax: tax,
		  //       },
		  //       success: function(response) {
		  //           if(response.success) {
		  //             toastr.success("Successfully deleted!");
		  //             setTimeout(function(){
		  //               location.reload();
		  //             },500);
			//
		  //           }
		  //         }
		  //     });
		  //   }
		  // });
		}
	};
}();

jQuery(document).ready(function() {
	RVClient.init();
});
