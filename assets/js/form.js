"use strict";

var RVForm = function() {

    return {
        //main function to initiate the module
        init: function() {



          if ( rvclientdata ) {
            $('#rvclient').select2({
              placeholder: 'Please Select a Client',
              data: rvclientdata,
              allowClear: true
            });
          }

          if ( rvpm ) {
            $('#rvpm').select2({
              placeholder: 'Please Select a PM',
              data: rvpm,
              allowClear: true
            });
          }

          $('#userselect').select2({
            placeholder: 'Please Select',
            allowClear: true
          });


          $('#datestart').datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight: true,
              orientation: "bottom left",
          });


          $('#dateend').datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight: true,
              orientation: "bottom left",
          });



          $('#rv_repater_budget').repeater({
              // initEmpty: false,
              //
              // defaultValues: {
              //     'text-input': 'foo'
              // },

              show: function() {
                  $(this).slideDown();
              },

              hide: function(deleteElement) {
                  if(confirm('Are you sure you want to delete this element?')) {
                      $(this).slideUp(deleteElement);
                  }
              }
          });

          var currentotal = 0;

          $(document).on('change','.hourcontrol', function() {
            // console.log($(this).val());
            if ($(this).val() > 0 ) {
              currentotal = 0;
              $('.hourcontrol').each(function(index){
                currentotal += parseInt($(this).val() );
              });
              //currentotal +=  Parseint($(this).val() );
              $('#budget').val(currentotal);
            }
          });


          $( "#tagadd" ).submit(function( e ) {
            e.preventDefault();
            var orio = $( this ).serializeArray();
            var o = {};
            var rcounter = 0;
            var hcounter = 0;
            var ranked = [];

            $.each(orio, function (index, value) {

              if (this.name.indexOf("rank") >= 0) {
                ranked.push({
                  'rank':this.value,
                  'hour': orio[index+1].value
                });
                rcounter++;
              }else if (this.name.indexOf("hour") >= 0) {
                // ranked.push({'hour':this.value});
                // // o['hour'][0] = this.value;
                // hcounter++;
              }else{
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
              }

            });
            o.rank = ranked;
            o['action'] = 'rv_addtag';

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : o,
              success: function(response) {
                if ( response.success ) {
                  toastr.success("Successfully saved!");
                  if ( response.data.url ) {
                    window.location.href = response.data.url;
                  }
                }else{
                  toastr.error("Progetto con lo stesso nome!");
                  toastr.error("Error!");
                }
              }
            });

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
            console.log(ii);
            console.log(value);
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
                  console.log(response);
                  console.log($(that).parent().parent());
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

          $('#gogopony').on('click',function(e){

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
                  action:"rv_get_attendance",
                  uid: $('#userselect').select2('data')[0].id,
                  month: $('#monthselect').val(),
                  year: $('#yearselect').val(),
              },
              success: function(response) {
                if(response.success) {
                  $('#datareport_table').html('');
                  $('#datareport_table').append(response.data.table);
                  KTApp.unblock($('#port-let'));
                }else{
                  KTApp.unblock($('#port-let'));
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
