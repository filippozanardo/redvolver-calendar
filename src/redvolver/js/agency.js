"use strict";
let agency_table;
var RVAgency = function() {

	var initAgencyTable = function() {

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

		agency_table = new Tabulator("#agency-tabulator", {
    	//ajaxURL:"http://www.getmydata.com/now", //ajax URL
			ajaxURL: rvlocalize.ajaxurl, //ajax URL
    	ajaxParams:{
				action:"get_agency_tabulator",
			},
			height:"500px",
			layout:"fitColumns",
			responsiveLayout:"collapse",
	    columns:[
				{formatter:"responsiveCollapse", width:30, minWidth:30, hozAlign:"center", resizable:false, headerSort:false},
	        {
						title:"Agency Name",
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
								html += '<a href="#" data-id="'+cell.getRow().getData().id+'" class="delagency btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete"><i class="la la-trash"></i></a>'


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

	};

	return {

		//main function to initiate the module
		init: function() {

			if ( $('#agency-tabulator').length > 0 ) {
				initAgencyTable();

				$(document).on('click','.delagency',function(e) {
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
											agency_table.setData();
										},500);

									}
								}
						});
					}

				});

				$(document).on('click','#agencyxls',function(e) {
					agency_table.download("xlsx", "agency.xlsx", {});
				});

				$(document).on('click','#agencypdf',function(e) {
					agency_table.download("pdf", "agency.pdf", {});
				});

			}

		}
	};
}();

jQuery(document).ready(function() {
	RVAgency.init();
});
