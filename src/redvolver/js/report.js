"use strict";

var RVReport = function() {

    return {
        //main function to initiate the module
        initProjectReport: function() {

          $('#project-report-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#project-report-select').on("change", function (e) {


            $("#project-report-select").prop("disabled", true);
            KTApp.block('#kt_content', {});

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"project_report",
                  pid: $('#project-report-select').select2('data')[0].id,
              },
              success: function(response) {
                KTApp.unblock('#kt_content', {});
                $("#project-report-select").prop("disabled", false);
                if(response.success) {
                  $('#project_report').html('');
                  $('#project_report').append(response.data.table);

                  $('#naive').bootstrapTable({
                    search: true,
                    buttonsToolbar: $('.buttons-toolbar'),
                    showExport:true,
                    exportTypes: [ 'csv', 'txt', 'excel'],
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
                      console.log($('#project-report-select').select2('data')[0].id);
                      console.log(row[0]);
                      $.ajax({
                         type : "POST",
                         url : rvlocalize.ajaxurl,
                         data : {
                            action:"user_by_project_report",
                            pid: $('#project-report-select').select2('data')[0].id,
                            department: row[0],
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
                  $('#project_report').html('');
                }
              }
            });

          });

        },

        initClientReport: function() {

          $('#report-agency-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#report-client-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#report-agency-select').on("change", function (e) {

            $("#report-agency-select").prop("disabled", true);
            KTApp.block('#kt_content', {});

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"client_report",
                  agency: $('#report-agency-select').select2('data')[0].id,
                  client: $('#report-client-select').select2('data')[0].id,
              },
              success: function(response) {
                KTApp.unblock('#kt_content', {});
                $("#report-agency-select").prop("disabled", false);
                $('#client_report').html('');
                if(response.success) {

                  $('#client_report').append(response.data.table);

                }
              }
            });

          });

          $('#report-client-select').on("change", function (e) {

            $("#report-client-select").prop("disabled", true);
            KTApp.block('#kt_content', {});

            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"client_report",
                  agency: $('#report-agency-select').select2('data')[0].id,
                  client: $('#report-client-select').select2('data')[0].id,
              },
              success: function(response) {
                KTApp.unblock('#kt_content', {});
                $("#report-client-select").prop("disabled", false);
                $('#client_report').html('');
                if(response.success) {

                  $('#client_report').append(response.data.table);

                }else{

                }
              }
            });

          });

        },

        initExportReport: function() {

          $('#project-export-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#user-export-select').select2({
            placeholder: 'Select an option',
            allowClear: true
          });

          $('#export-datestart').datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight: true,
              orientation: "bottom left",
          });

          $('#export-dateend').datepicker({
              format: 'dd/mm/yyyy',
              autoclose: true,
              todayHighlight: true,
              orientation: "bottom left",
          });


          $('#goexport').on('click',function(e){
            KTApp.block('#kt_content', {});
            e.preventDefault();

            if ( $('#export-datestart').val() == '' ) {
              alert('Please select a start date');
              KTApp.unblock($('#kt_content'));
              return false;
            }

            if ( $('#export-dateend').val() == '' ) {
              alert('Please select an end date');
              KTApp.unblock($('#kt_content'));
              return false;
            }


            $.ajax({
               type : "POST",
               url : rvlocalize.ajaxurl,
               data : {
                  action:"export_report",
                  user_id: $('#user-export-select').select2('data')[0].id,
                  project_id: $('#project-export-select').select2('data')[0].id,
                  start: $('#export-datestart').val(),
                  end: $('#export-dateend').val(),
              },
              success: function(response) {
                  console.log(response);
                  if(response.success) {
                    $('#export_report').html('');
                    $('#export_report').append(response.data.table);
                    $('#export_table').DataTable({
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

                  }else{

                    $('#export_report').html('');
                  }
                  KTApp.unblock($('#kt_content'));

                }
            });

          });
        }
    };
}();

jQuery(document).ready(function() {
  if ( $('#project-report-select').length > 0 ) RVReport.initProjectReport();
  if ( $('#client_report').length > 0 ) RVReport.initClientReport();
  if ( $('#export_report').length > 0 ) RVReport.initExportReport();
});
