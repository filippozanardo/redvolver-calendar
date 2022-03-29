"use strict";

var RVForm = function() {

    return {
        //main function to initiate the module
        init: function() {

          if ( $('#client').length > 0 ) {
            $('#client').select2({
              placeholder: 'Please Select',
              //data: rvdata,
              ajax: {
                url : rvlocalize.ajaxurl,
                method: 'POST',
                data: function (params) {
                  var query = {
                    action: "get_client_select2",
                    q: params.term,
                    page: params.page,
                  }
                  //console.log(query);
                  // Query parameters will be ?search=[term]&type=public
                  return query;
                },
                processResults: function (data) {
                  // console.log(data);
                  // console.log(data.results);
                  //results: data.results,
                  return {
                    results: data.results,
                  };
                },
                delay: 250,
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              }
            });
          }


          if ( $('#agency').length > 0 ) {
            $('#agency').select2({
              placeholder: 'Please Select',
              //data: rvdata,
              ajax: {
                url : rvlocalize.ajaxurl,
                method: 'POST',
                data: function (params) {
                  var query = {
                    action: "get_agency_select2",
                    q: params.term,
                    page: params.page,
                  }
                  //console.log(query);
                  // Query parameters will be ?search=[term]&type=public
                  return query;
                },
                processResults: function (data) {
                  // console.log(data);
                  // console.log(data.results);
                  //results: data.results,
                  return {
                    results: data.results,
                  };
                },
                delay: 250,
                dataType: 'json'
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
              }
            });
          }

          if ( $('#timing').length > 0 ) {

            $('#timing').datepicker({
                format: 'dd/mm/yyyy',
                //format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });
          }



          if ( $('#content').length > 0 ) {
            ClassicEditor
      			.create( document.querySelector( '#content' ),{
              removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed'],
            } )
      			.then( editor => {
      				console.log( editor );
      			} )
      			.catch( error => {
      				console.error( error );
      			} );
          }

          if ( $('#request').length > 0 ) {
            ClassicEditor
      			.create( document.querySelector( '#request' ),{
              removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle', 'ImageToolbar', 'ImageUpload', 'MediaEmbed'],
            } )
      			.then( editor => {
      				console.log( editor );
      			} )
      			.catch( error => {
      				console.error( error );
      			} );
          }

          $('#userselect').select2({
            placeholder: 'Please Select',
            allowClear: true
          });


          if ( $('#datestart').length > 0 ) {
            $('#datestart').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });
          }

          if ( $('#dateend').length > 0 ) {
            $('#dateend').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });
          }

          if ( $('#onair_repeater').length > 0 ) {

            $('.onair_start').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });

            $('.onair_end').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });

            $('#onair_repeater').repeater({
                // initEmpty: false,
                //
                // defaultValues: {
                //     'text-input': 'foo'
                // },

                show: function() {

                  $(this).slideDown();
                  $(this).find('.onair_start').datepicker({
                      format: 'dd/mm/yyyy',
                      autoclose: true,
                      todayHighlight: true,
                      orientation: "bottom left",
                  });

                  $(this).find('.onair_end').datepicker({
                      format: 'dd/mm/yyyy',
                      autoclose: true,
                      todayHighlight: true,
                      orientation: "bottom left",
                    });
                },

                hide: function(deleteElement) {
                    if(confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });
          }

          $( "#changepassword" ).submit(function( e ) {
            e.preventDefault();
            let new_password = $('#new_password').val();
            let confirm_new_password = $('#confirm_new_password').val();

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 action: 'change_password',
                 new_password: new_password,
                 confirm_new_password: confirm_new_password,
               },
              success: function(response) {
                if ( response.success ) {
                  window.location.reload();
                  // toastr.success("Successfully saved!");
                  // if ( response.data.url ) {
                  //   window.location.href = response.data.url;
                  // }
                }else{
                  // toastr.error("Capra Capra!");
                  // toastr.error("Error!");
                  toastr.error(response.data.errors);

                }
                console.log(response);
              }
            });
          });

          $( "#addclient" ).submit(function( e ) {
            e.preventDefault();

            let clientname = $('#clientname').val();
            let content = $('#content').val();
            let pid = $('#pid').val();

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 pid: pid,
                 action: 'add_client',
                 clientname: clientname,
                 content: content,
               },
              success: function(response) {
                if ( response.success ) {
                  toastr.success("Successfully saved!");
                  if ( response.data.url ) {
                    window.location.href = response.data.url;
                  }
                }else{
                  toastr.error("Capra Capra!");
                  toastr.error("Error!");
                }
              }
            });

          });

          $( "#addagency" ).submit(function( e ) {
            e.preventDefault();

            let agencyname = $('#agencyname').val();
            let content = $('#content').val();
            let pid = $('#pid').val();

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 pid: pid,
                 action: 'add_agency',
                 agencyname: agencyname,
                 content: content,
               },
              success: function(response) {
                if ( response.success ) {
                  toastr.success("Successfully saved!");
                  if ( response.data.url ) {
                    window.location.href = response.data.url;
                  }
                }else{
                  toastr.error("Capra Capra!");
                  toastr.error("Error!");
                }
              }
            });

          });

          // $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
          //
          //    // newly activated tab
          //   let projectname = $('#projectname').val();
          //   if ( projectname == '' ) {
          //     return false;
          //     console.log(event.target);
          //     Swal.fire({
  				// 			text: "Sorry, looks like there are some errors detected, please try again.",
  				// 			icon: "error",
  				// 			buttonsStyling: false,
  				// 			confirmButtonText: "Ok, got it!",
  				// 			customClass: {
  				// 				confirmButton: "btn font-weight-bold btn-light"
  				// 			}
  				// 		}).then(function () {
  				// 			KTUtil.scrollTop();
  				// 		});
          //
          //   }
          // });


          $( "#addproject" ).submit(function( e ) {
            e.preventDefault();

            let form_array = $( this ).serializeArray();
            console.log(form_array);

            let finalarray = {};
            let rcounter = 0;
            let hcounter = 0;
            let onairs = [];

            $.each(form_array, function (index, value) {

              console.log(this.name.indexOf("onair_title"));

              if (this.name.indexOf("onair_title") >= 0) {
                onairs.push({
                  'title':this.value,
                  'start': moment(form_array[index+1].value, "DD/MM/YYYY").format('YYYY-MM-DD'),
                  'end': moment(form_array[index+2].value, "DD/MM/YYYY").format('YYYY-MM-DD')
                });
                rcounter++;
              }else{
                if (this.name.indexOf("onair_start") >= 0) {
                  return true;
                }
                if (this.name.indexOf("onair_end") >= 0) {
                  return true;
                }

                if (finalarray[this.name]) {
                    if (!finalarray[this.name].push) {
                        finalarray[this.name] = [finalarray[this.name]];
                    }
                    console.log(1);
                    finalarray[this.name].push(this.value || '');
                } else {
                  console.log(2);
                  console.log( this.name);
                  let va = this.value;
                  if ( this.name.indexOf("timing")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }else if ( this.name.indexOf("datestart")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }else if ( this.name.indexOf("dateend")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }

                  finalarray[this.name] = va || '';
                }

              }

            });
            finalarray.onairs = onairs;

            // console.log(finalarray);
            // return false;

            // let projectname = $('#projectname').val();
            // let referent = $('#referent').val();
            // let pm = $('#pm').val();
            // let graphic = $('#graphic').val();
            // let dev = $('#dev').val();
            // let client = $('#client').val();
            // let agency = $('#agency').val();
            // let timing = $('#timing').val();
            // let type = $('#type').val();
            // let content = $('#content').val();
            // let on_air = $('#on_air').val();
            // let archive = $("#archive").prop("checked") ? 1 : 0;
            // let rentman = $('#rentman').val();
            // let datestart = $('#datestart').val();
            // let dateend = $('#dateend').val();
            // let pid = $('#pid').val();
            // let status = $('#status').val();
            // let project_type = $('#project_type').val();
            //
            //
            // let brand = $('#brand').val();
            // let sector = $('#sector').val();
            // let contact = $('#contact').val();
            // let duration = $('#duration').val();
            // let target = $('#target').val();
            // let objective = $('#objective').val();
            // let concept = $('#concept').val();
            // let staff = $('#staff').val();
            // let location = $('#location').val();
            // let output = $('#output').val();
            // let delivery = $('#delivery').val();
            // let budget = $('#budget').val();
            // let request = $('#request').val();

            //console.log(timing);
            // if ( timing ) {
            //   timing = moment(timing, "DD/MM/YYYY").format('YYYY-MM-DD');
            // }

            // if ( on_air ) {
            //   on_air = moment(on_air, "DD/MM/YYYY").format('YYYY-MM-DD');
            // }

            // if ( datestart ) {
            //   datestart = moment(datestart, "DD/MM/YYYY").format('YYYYMMDD');
            // }
            //
            // if ( dateend ) {
            //   dateend = moment(dateend, "DD/MM/YYYY").format('YYYYMMDD');
            // }
            //console.log(mimmo);
            //return;

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : finalarray,
               // data : {
               //   pid: pid,
               //   action: 'add_project',
               //   projectname: projectname,
               //   referent: referent,
               //   pm: pm,
               //   graphic: graphic,
               //   dev: dev,
               //   client: client,
               //   agency: agency,
               //   timing: timing,
               //   type: type,
               //   content: content,
               //   on_air: on_air,
               //   start: datestart,
               //   end: dateend,
               //   rentman: rentman,
               //   archive: archive,
               //   brand : brand,
               //   sector : sector,
               //   contact : contact,
               //   duration : duration,
               //   target : target,
               //   objective : objective,
               //   concept : concept,
               //   staff : staff,
               //   location : location,
               //   output : output,
               //   delivery : delivery,
               //   budget : budget,
               //   request : request,
               //   status: status,
               //   project_type: project_type,
               // },
              success: function(response) {
                if ( response.success ) {
                  toastr.success("Successfully saved!");
                  if ( response.data.url ) {
                    window.location.href = response.data.url;
                  }
                }else{

                  $('#pjtab li:first-child a').tab('show');
                  KTUtil.scrollTop();
                  $('#projectname').focus();

                  toastr.error("Compila il nome progetto!");
                  toastr.error("Error!");
                }
              }
            });

          });


          $(document).on('click','.delpost',function(e){
    				e.preventDefault();
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
    		                location.reload();
    		              },500);

    		            }
    		          }
    		      });
    				}
    			});


          $('#u-select').select2({
            placeholder: 'Select a User',
            allowClear: true
          });

          $('#gouser_report').on('click',function(e){
              e.preventDefault();

              KTApp.block($('#port-let'), {
                  overlayColor: '#ffffff',
                  type: 'loader',
                  state: 'success',
                  opacity: 0.3,
                  size: 'lg'
              });

              if ( $('#u-select').select2('data')[0].id == '') {
                alert('Please select a user');
                KTApp.unblock($('#port-let'));
                return false;
              }



              $.ajax({
                 type : "POST",
                 url : rvlocalize.ajaxurl,
                 data : {
                    action:"rv_user_report",
                    uid: $('#u-select').select2('data')[0].id,
                    start: $('#datestart').val(),
                    end: $('#dateend').val(),
                },
                success: function(response) {
                    //console.log(response);
                    if(response.success) {
                      $('#user-report-container').html('');
                      $('#user-report-container').append(response.data.table);
                      $('#remaketable').DataTable({
                        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
                          <'row'<'col-sm-12'tr>>
                          <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
                        responsive: true,
                        paging : false,
                        buttons: [
                              'print',
                              'copyHtml5',
                              'excelHtml5',
                              'csvHtml5',
                              'pdfHtml5',
                            ],
                      });
                      KTApp.unblock($('#port-let'));
                    }else{
                      KTApp.unblock($('#port-let'));
                      $('#user-report-container').html('');
                    }

                  }
              });

          });

          $('#tax-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#tax-select').on("change", function (e) {

              $("#tax-select").prop("disabled", true);
              KTApp.block($('#port-let'), {
                  overlayColor: '#ffffff',
                  type: 'loader',
                  state: 'success',
                  opacity: 0.3,
                  size: 'lg'
              });

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_project_report_tax",
                  tax: $('#tax-select').select2('data')[0].id,
              },
              success: function(response) {
                  //console.log(response);
                  KTApp.unblock($('#port-let'));
                  if(response.success) {

                    $('#project-report-container').html('');
                    $('#project-report-container').append(response.data.table);

                    $('#naive').bootstrapTable({

                    });
                  }else{
                    $('#project-report-container').html('');
                  }
                  $("#tax-select").prop("disabled", false);
                }
            });

          });

          $('#client-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#client-select').on("change", function (e) {

              if ( $('#client-select').select2('data')[0].id == '' ) return false;
              $("#client-select").prop("disabled", true);
              KTApp.block($('#port-let'), {
                  overlayColor: '#ffffff',
                  type: 'loader',
                  state: 'success',
                  opacity: 0.3,
                  size: 'lg'
              });

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_client_report",
                  tax: $('#client-select').select2('data')[0].id,
              },
              success: function(response) {
                  // console.log(response);
                    KTApp.unblock($('#port-let'));
                  if(response.success) {

                    $('#client-report-container').html('');
                    $('#client-report-container').append(response.data.table);

                    // $('#naive').bootstrapTable({});
                  }else{
                    $('#client-report-container').html('');
                  }
                  $("#client-select").prop("disabled", false);
                }
            });

          });

          $('#tax-select-estimate').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#pm-select').select2({
            placeholder: 'Select PM',
            allowClear: true
          });

          $('#goestimate_report').on('click',function(e){

            e.preventDefault();

            KTApp.block($('#port-let'), {
                overlayColor: '#ffffff',
                type: 'loader',
                state: 'success',
                opacity: 0.3,
                size: 'lg'
            });


            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_project_estimate",
                  tax: $('#tax-select-estimate').select2('data')[0].id,
                  pm: $('#pm-select').select2('data')[0].id,
              },
              success: function(response) {
                $('#estimate-report-container').html('');
                if(response.success) {
                  $('#estimate-report-container').append(response.data.table);
                }
                //
                KTApp.unblock($('#port-let'));
                $('#m_table_no_pag').DataTable( {
                    "paging": false
                });
                $("#tax-select-estimate").prop("disabled", false);
                $("#pm-select").prop("disabled", false);
              }
            });



          });

          $('.scambio').on('click',function(e) {
            e.preventDefault();
            var ii = $(this).data('id');
            var value = $(this).data('value');
            var that = this;

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_menu_scambio",
                  id: ii,
                  val: value
              },
              success: function(response) {

                if(response.success) {

                    $(that).parent().parent().html(response.data.out);
                  // if (response.data.out ) {
                  //
                  //   $(that).parent().parent().html(response.data.out);
                  // }
                  toastr.success("Successfully saved!");
                }else{
                  toastr.error("Error!");
                }

              }
            });

          });

          $('#goexport').on('click',function(e){

            e.preventDefault();

            if ( $('#datestart').val() == '' ) {
              alert('Please select a start date');
              KTApp.unblock($('#port-let'));
              return false;
            }

            if ( $('#dateend').val() == '' ) {
              alert('Please select an end date');
              KTApp.unblock($('#port-let'));
              return false;
            }

            KTApp.block($('#port-let'), {
                overlayColor: '#ffffff',
                type: 'loader',
                state: 'success',
                opacity: 0.3,
                size: 'lg'
            });

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_exp_report",
                  uid: $('#u-select').select2('data')[0].id,
                  pid: $('#tax-select-estimate').select2('data')[0].id,
                  start: $('#datestart').val(),
                  end: $('#dateend').val(),
              },
              success: function(response) {

                  if(response.success) {
                    $('#exp-report-container').html('');
                    $('#exp-report-container').append(response.data.table);
                    $('#remaketable2').DataTable({
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
                    KTApp.unblock($('#port-let'));
                  }else{
                    KTApp.unblock($('#port-let'));
                    $('#exp-report-container').html('');
                  }

                }
            });

          });

          $('#search_time_card_rep').on('click',function(e){

            e.preventDefault();

            KTApp.block($('#cardcontent'), {
              overlayColor: '#ffffff',
              type: 'loader',
              state: 'primary',
              opacity: 0.5,
              size: 'lg'
             });

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_get_attendance",
                  uid: $('#userselect').select2('data')[0].id,
                  month: $('#monthselect').val(),
                  year: $('#yearselect').val(),
              },
              success: function(response) {
                if(response.success) {
                  $('#datareport_table').html('');
                  $('#datareport_table').append(response.data.table);
                  KTApp.unblock($('#cardcontent'));
                }else{
                  KTApp.unblock($('#cardcontent'));
                  $('#exp-report-container').html('');
                }
              }
            });

          });

          $('#gogopony2').on('click',function(e){

            e.preventDefault();

            KTApp.block($('#port-let'), {
                overlayColor: '#ffffff',
                type: 'loader',
                state: 'success',
                opacity: 0.3,
                size: 'lg'
            });


            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_get_lunch_table",
                  month: $('#monthselect').val(),
                  year: $('#yearselect').val(),
              },
              success: function(response) {
                if(response.success) {
                  $('#app-time-container').html('');
                  $('#app-time-container').append(response.data.out);
                  KTApp.unblock($('#port-let'));
                }else{
                  KTApp.unblock($('#port-let'));
                  $('#app-time-container').html('');
                }
              }
            });

          });

          $('#tax-select-group').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#tax-select-group').on("change", function (e) {

              $("#tax-select-group").prop("disabled", true);
              KTApp.block($('#port-let'), {
                  overlayColor: '#ffffff',
                  type: 'loader',
                  state: 'success',
                  opacity: 0.3,
                  size: 'lg'
              });

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"rv_group_report_tax",
                  tax: $('#tax-select-group').select2('data')[0].id,
              },
              success: function(response) {
                  // console.log(response);
                  KTApp.unblock($('#port-let'));
                  // console.log( $('#tax-select-group').select2('data')[0] );
                  if(response.success) {

                    $('#group-report-container').html('');
                    $('#group-report-container').append(response.data.table);

                    $('#naive').bootstrapTable({
                      search: true,
                      buttonsToolbar: $('.buttons-toolbar'),
                      showExport:true,
                      exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
                      // exportTypes: ['csv'],
                      exportOptions: {
                          ignoreColumn: [0],
                          fileName: function () {
                             return $('#tax-select-group').select2('data')[0].text
                          },
                          onBeforeSaveToFile: function(data, fileName, type, charset, encoding) {
                            console.log(data);
                            console.log(fileName);
                            console.log(type);
                            console.log(encoding);
                            var result = $.csv.toArrays(data);
                            data = 'gino,paolo';
                            console.log(result);
                            return data;
                          },
                       },
                      detailView: true ,
                      // detailFormatter: detailFormatter,
                      onExpandRow: function (index, row, $detail) {

                        $.ajax({
                           type : "POST",
                           url : rvlocalize.ajaxurl,
                           data : {
                              action:"rv_group_report_user",
                              tax: $('#tax-select-group').select2('data')[0].id,
                              rank: row[0],
                          },
                          success: function(response) {
                            if(response.success) {
                              $detail.html(response.data.table);
                            }else{
                              $detail.html('Nessun dato');
                            }
                          }
                        });

                      }
                    });



                  }else{
                    $('#group-report-container').html('');
                  }
                  $("#tax-select-group").prop("disabled", false);
                }
            });

          });

          $('.send-pranzo').on('click',function(e){
            e.preventDefault();
            var dataid = $(this).data('id');
            $.ajax({
              type : 'POST',
              url : rvlocalize.ajaxurl,
              data : {
                action:"rv_send_pranzo",
                id: dataid,
              },
              success: function(response) {
                console.log(response);
                if (response.success) {
                  toastr.success("Success!");

                }else{
                  toastr.error("Error please reload the page!");
                }
              },
              error : function(){
                toastr.error("Error please reload the page!");
                console.log('error');
              },
            });
          });

        }
    };
}();

jQuery(document).ready(function() {
    RVForm.init();
});
