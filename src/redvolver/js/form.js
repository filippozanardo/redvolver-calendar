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

          let projectcode = $('#projectcode');
          let currentdate = new Date();
          let projectnum = 'XX';

          function setCodeString () {
            if ( $('#customid').length > 0 && $('#customid').val() != '' ) projectnum = $('#customid').val();
            console.log( $('#customid').val() );
            console.log( projectnum );
            let codestring = $('#project_type').find(':selected').data('slug');
            codestring += '-'+currentdate.getFullYear();
            codestring += '-'+projectnum;
            codestring += '-'+$('#projectname').val().replaceAll(" ", "");
            return codestring;
          }

          $('#project_type').on('change',function(e) {

            let codestring = setCodeString;

            projectcode.val(codestring);
          });

          $('#projectname').on('input',function(e) {

            let codestring = setCodeString;

            projectcode.val(codestring);
          });

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
            let contact = $('#contact').val();
            let referent = $('#referent').val();

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 pid: pid,
                 action: 'add_client',
                 clientname: clientname,
                 content: content,
                 contact: contact,
                 referent: referent,
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
            let contact = $('#contact').val();
            let referent = $('#referent').val();

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 pid: pid,
                 action: 'add_agency',
                 agencyname: agencyname,
                 content: content,
                 contact: contact,
                 referent: referent,
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

          if ( $('#file_drop').length > 0 ) {

            let whoami = $('#whoami').data('id');
            let dropzarro = $('#file_drop').dropzone({
                url: rvlocalize.ajaxurl+'?action=handle_dropped_media&id='+whoami, // Set the url for your upload script location
                paramName: "file", // The name that will be used to transfer the file
                maxFiles: 1,
                maxFilesize: 5, // MB
                addRemoveLinks: true,
                //autoProcessQueue: false,
                // accept: function(file, done) {
                //     if (file.name == "justinbieber.jpg") {
                //         done("Naha, you don't.");
                //     } else {
                //         done();
                //     }
                // },
                success: function(file, response){
                    console.log(file);
                    console.log(response);
                    $('#files_list').append(response.data.out).fadeIn(500);
                },
                complete: function(file) {
                  setTimeout(() => {
                    this.removeFile(file); // right here after 3 seconds you can clear
                  }, 200);
                },
            });

          }

          $(document).on('click','.btnpreview',function(e){

            let iframesrc = $(this).data('url');

            $('#file_preview').attr('src',iframesrc);
            $('#filemodal').modal({
                // keyboard: false
            });
          });

          $(document).on('click','.scaricall',function(e){

            // let projid = $(this).data('id');
            // //alert($(this).data('id') );
            //
            // $.ajax({
            //    type : "POST",
            //    url : rvlocalize.ajaxurl,
            //    data : {
            //      action: 'rv_download_all',
            //      pid: projid,
            //    },
            //   success: function(response) {
            //     if ( response.success ) {
            //       toastr.success("Successfully saved!");
            //
            //     }else{
            //       toastr.error("Capra Capra!");
            //       toastr.error("Error!");
            //     }
            //   }
            // });

          });




          // $('#file_preview').on('hidden.bs.modal', function (event) {
          //   // do something...
          // });

          $(document).on('click','.delete_file',function(e){
            e.preventDefault();
            let whoami = $('#whoami').data('id');
            let id = $(this).data('id');

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                 id: id,
                 whoami: whoami,
                 action: 'delete_project_file',
               },
              success: function(response) {
                if ( response.success ) {
                  toastr.success("Successfully deleted!");
                  $('#file_item_'+id).remove();
                }else{
                  toastr.error("Capra Capra!");
                  toastr.error("Error!");
                }
              }
            });

          });

          // $('#addprojectbutton').on('click',function(e){
          //   e.preventDefault();
          //   let projectname = $('#projectname').val();
          //   if ( projectname == '' ) {
          //     toastr.error("Compila il nome progetto!");
          //     toastr.error("Error!");
          //     return false;
          //   }
          //   $("#addproject").submit();
          // });

          $( "#addproject" ).on('submit',function( e ) {
          // $( "#addproject" ).submit(function( e ) {
            e.preventDefault();

            console.log('add project');
            KTApp.block($('#kt_content'), {
              overlayColor: '#ffffff',
              type: 'loader',
              state: 'success',
              opacity: 0.6,
              size: 'lg'
            });

            // let form_array2 = $( this ).serializeArray();
            // console.log($( this ));
            // console.log(form_array2);
            // console.log($('#referent').select2('data')) ;

            // return false;

            let projectname = $('#projectname').val();
            if ( projectname == '' ) {
              toastr.error("Compila il nome progetto!");
              toastr.error("Error!");
              return false;
            }


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
                    finalarray[this.name].push(this.value || '');
                } else {

                  let va = this.value;
                  if ( this.name.indexOf("timing")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }else if ( this.name.indexOf("datestart")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }else if ( this.name.indexOf("dateend")  >= 0 ) {
                    va = moment(this.value, "DD/MM/YYYY").format('YYYY-MM-DD');
                  }
                  if(this.name == "content" || this.name == "request")
                    va = $("#"+this.name).parent().children().last().children(".ck-editor__main").children().html();

                  finalarray[this.name] = va || '';
                }

              }

            });
            finalarray.onairs = onairs;

            console.log(finalarray);
            // return false;

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : finalarray,
              success: function(response) {
                if ( response.success ) {
                  KTApp.unblock($('#kt_content'));
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

          $('#pm').select2({
            placeholder: 'Select PM',
          });

          $('#graphic').select2({
            placeholder: 'Select graphic',
          });

          $('#dev').select2({
            placeholder: 'Select dev',
          });

          $('#referent').select2({
            placeholder: 'Select dev',
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

Dropzone.autoDiscover = false;

jQuery(document).ready(function() {

    RVForm.init();
});
