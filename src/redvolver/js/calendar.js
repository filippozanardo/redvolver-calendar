"use strict";

var RVCalendar = function() {

    return {
        //main function to initiate the module
        init: function() {
            moment.locale('it');

            var editmodal = $("#rvmodaledit");
            var currentedit = null;

            var todayDate = moment().startOf('day');
            var YM = todayDate.format('YYYY-MM');
            var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
            var TODAY = todayDate.format('YYYY-MM-DD');
            var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

            $('#project').select2({
              placeholder: 'Please Select',
              data: projects
            });

            // $('#project').select2({
            //   placeholder: 'Please Select',
            //   //data: rvdata,
            //   ajax: {
            //     url : rvlocalize.ajaxurl,
            //     method: 'POST',
            //     data: function (params) {
            //       var query = {
            //         action: "get_projects",
            //         q: params.term,
            //         page: params.page,
            //       }
            //       //console.log(query);
            //       // Query parameters will be ?search=[term]&type=public
            //       return query;
            //     },
            //     processResults: function (data) {
            //       // console.log(data);
            //       // console.log(data.results);
            //       //results: data.results,
            //       return {
            //         results: data.results,
            //       };
            //     },
            //     delay: 250,
            //     dataType: 'json'
            //     // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            //   }
            // });

            var calendarEl = document.getElementById('rv_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                //plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','listPlugin' ],
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                themeSystem: 'bootstrap',
                locale: 'it',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                allDaySlot: false,
                height: 800,
                contentHeight: 780,
                aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio
                nowIndicator: true,
                views: {
                    dayGridMonth: { buttonText: 'month' },
                    timeGridWeek: { buttonText: 'week' },
                    timeGridDay: { buttonText: 'day' }
                },
                defaultView: 'timeGridWeek',
                defaultDate: TODAY,
                selectable: true,
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                navLinks: true,
                eventSources: [
                    {
                        url: rvlocalize.ajaxurl,
                        method: 'POST',
                        extraParams: {
                            action:"fetch_timecard",
                        },
                        // success: function(response) {
                        //   console.log(response);
                        // },
                        error: function(  ) {
                          //alert('there was an error while fetching events!');
                          toastr.error("Error please reload the page!");
                        }
                    }
                ],
                select: function(info) {
                  console.log( info );

                  editmodal.find('#rvstart').val(moment(info.start).format());
                  editmodal.find('#rvend').val(moment(info.end).format());
                  editmodal.find('#rvtitle').val('');
                  editmodal.find('#rvcid').val('');
                  editmodal.find('#rvmode').val('add');
                  editmodal.find('#rvmoddel').fadeOut();
                  editmodal.find('#project').val('').trigger("change");
                  editmodal.find('#smart_working').prop('checked',false);
                  editmodal.find('#cig').prop('checked',false);

                  currentedit = null;

                  editmodal.modal({
                      backdrop: 'static'
                  });
                },
                eventResize: function(info) {
                // eventResize: function(event, delta, revertFunc) {
                  //console.log('eventResize');
                  // console.log( info );
                  // console.log( moment(info.event.start).format() );
                  $.ajax({
                     type : "POST",
                     url : rvlocalize.ajaxurl,
                     data : {
                       action:"edit_timecard",
                       cid: info.event.id,
                       title: info.event.title,
                       start:moment(info.event.start).format(),
                       end: moment(info.event.end).format(),
                       mode: 'edit',
                       project: info.event.extendedProps.project,
                       type: info.event.extendedProps.type,
                       smart_working: info.event.extendedProps.smart_working,
                       cig: info.event.extendedProps.cig,
                    },
                    success: function(response) {
                      toastr.success("Successfully inserted record!");
                    },
                    error: function( debu ) {
                      toastr.error("Error please reload the page!");
                      revertFunc();
                    }
                  });
                },
                eventDrop: function(info) {
                // eventResize: function(event, delta, revertFunc) {
                  //console.log('eventResize');
                  // console.log( info );
                  // console.log( moment(info.event.start).format() );
                  $.ajax({
                     type : "POST",
                     url : rvlocalize.ajaxurl,
                     data : {
                       action:"edit_timecard",
                       cid: info.event.id,
                       title: info.event.title,
                       start:moment(info.event.start).format(),
                       end: moment(info.event.end).format(),
                       mode: 'edit',
                       project: info.event.extendedProps.project,
                       type: info.event.extendedProps.type,
                       smart_working: info.event.extendedProps.smart_working,
                       cig: info.event.extendedProps.cig,

                    },
                    success: function(response) {
                      toastr.success("Successfully inserted record!");
                    },
                    error: function( debu ) {
                      toastr.error("Error please reload the page!");
                      revertFunc();
                    }
                  });
                },
                eventClick: function(info) {
                  // console.log('eventClick');
                  console.log(info);

                  editmodal.find('#rvstart').val(moment(info.event.start).format());
                  editmodal.find('#rvend').val(moment(info.event.end).format());
                  editmodal.find('#rvtitle').val(info.event.title);
                  editmodal.find('#rvcid').val(info.event.id);
                  editmodal.find('#rvmode').val('edit');

                  if (info.event.extendedProps.project) {
                    editmodal.find('#project').val(info.event.extendedProps.project).trigger("change");
                  }else{
                    editmodal.find('#project').val('').trigger("change");
                  }

                  if (info.event.extendedProps.type) {
                    editmodal.find('#type').val(info.event.extendedProps.type).trigger("change");
                  }else{
                    editmodal.find('#type').val('').trigger("change");
                  }

                  if (info.event.extendedProps.smart_working ) {
                    if (info.event.extendedProps.smart_working == 0 ) {
                      editmodal.find('#smart_working').prop('checked',false);
                    }else{
                      editmodal.find('#smart_working').prop('checked',true);
                    }
                  }else{
                    editmodal.find('#smart_working').prop('checked',false);
                  }

                  if (info.event.extendedProps.cig ) {
                    if (info.event.extendedProps.cig == 0 ) {
                      editmodal.find('#cig').prop('checked',false);
                    }else{
                      editmodal.find('#cig').prop('checked',true);
                    }
                  }else{
                    editmodal.find('#cig').prop('checked',false);
                  }


                  editmodal.find('#rvmoddel').fadeIn();
                  currentedit = info.event;

                  editmodal.modal({
                      backdrop: 'static'
                  });
                },
            });

            calendar.render();

            $('#rvmoddel').on('click', function(e){
              e.preventDefault();
              var cid = $('#rvcid').val();

              if ( cid !== null && cid.length != 0 ) {
                $.ajax({
                   type : "POST",
                   url : rvlocalize.ajaxurl,
                   data : {
                      action:"del_timecard",
                      cid: cid,
                  },
                  success: function(response) {
                      if(response.success) {
                        var eve = calendar.getEventById( cid );
                        eve.remove()

                        toastr.success("Successfully deleted!");

                        setTimeout(function(){
                          editmodal.modal('hide');
                        },300);

                      }
                    }
                });

              }

            });

            $('#rvmodsave').on('click', function(e){
              // We don't want this to act as a link so cancel the link action
              e.preventDefault();
              modalEdit();
            });

            function modalEdit(){

              let title = $('#rvtitle').val();
              let start = $('#rvstart').val();
              let end = $('#rvend').val();
              let cid = $('#rvcid').val();
              let mode = $('#rvmode').val();
              let project = $('#project').val();
              let type = $('#type').val();
              let smart_working = $("#smart_working").prop("checked") ? 1 : 0;
              let cig = $("#cig").prop("checked") ? 1 : 0;

              if ( title == null || title.length == 0 ) {
                alert('Please insert a title!');
                return false;
              }

              $.ajax({
                 type : "POST",
                 url : rvlocalize.ajaxurl,
                 data : {
                    action:"edit_timecard",
                    cid: cid,
                    title: title,
                    start:start,
                    end: end,
                    project: project,
                    type: type,
                    smart_working: smart_working,
                    cig:cig,
                    mode: mode,
                },
                success: function(response) {

                    console.log(response);
                    if(response.success) {

                      if ( response.data.mode == 'add' ) {
                        if (!project) {
                          project = response.data.project;
                        }

                        calendar.addEvent( {
                            id: response.data.id,
                            title: response.data.title,
                            start:start,
                            end: end,
                            extendedProps: {
                              project: project,
                              type: type,
                              smart_working: smart_working,
                              cig:cig,
                            }
                        });

                        toastr.success("Successfully added!");

                      }else if ( response.data.mode == 'edit' ) {

                        currentedit.setProp( 'title', title )
                        currentedit.setStart(start);
                        currentedit.setEnd(end);
                        currentedit.setExtendedProp('tag',project);
                        currentedit.setExtendedProp('type',type);
                        currentedit.setExtendedProp('smart_working',smart_working);
                        currentedit.setExtendedProp('cig',cig);

                        toastr.success("Successfully updated!");
                      }
                      setTimeout(function(){
                        editmodal.modal('hide');
                      },300);

                    }
                  }
              });

            }

            editmodal.on('shown.bs.modal',function(){
              $('#rvtitle').focus();
            });



            $('#project2').select2({
              placeholder: 'Please Select',
              data: projects
            });




            $('#rv_date').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom left",
            });

            $('#project2').on('change',function(){
              //let date = new Date();
              let titulo = $('#project2').select2('data')[0].text + ' - '+  $('#rv_date').val();
              //titulo += ' '+date.toLocaleDateString("it-IT");
              //let datestring = d.getDate()  + "-" + (d.getMonth()+1) + "-" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes();
              $('#rvtitle2').val( titulo );

            });

            $('#rv_date').on('change',function(){
              let titulo = $('#project2').select2('data')[0].text + ' - '+  $('#rv_date').val();
              $('#rvtitle2').val( titulo );

            });

            let rvtimecard = $("#rvtimecard");

            $('#addtimecard').on('click',function(e) {
              e.preventDefault();
              rvtimecard.modal({
                backdrop: 'static'
             });
            });

            // $('#rvmodsave2').on('click', function(e){
            //   // We don't want this to act as a link so cancel the link action
            //   e.preventDefault();
            //   timecardAdd();
            // });

            $('#rvcform2').on('submit', function(e){
              // We don't want this to act as a link so cancel the link action
              e.preventDefault();
              timecardAdd();
              document.getElementById('rvcform2').reset();
            });

            function timecardAdd(){

              let title = $('#rvtitle2').val();
              let cid = $('#rvcid2').val();
              let project = $('#project2').val();
              let type = $('#type2').val();
              let smart_working = $("#smart_working2").prop("checked") ? 1 : 0;
              let cig = $("#cig2").prop("checked") ? 1 : 0;
              let rv_date = $("#rv_date").val();
              let rvhour = $("#rvhour").val();


              if ( title == null || title.length == 0 ) {
                alert('Inserire una descrizione!');
                return false;
              }

              $.ajax({
                 type : "POST",
                 url : rvlocalize.ajaxurl,
                 data : {
                    action:"add_timecard_only",
                    cid: cid,
                    title: title,
                    date:rv_date,
                    hour: rvhour,
                    project: project,
                    type: type,
                    smart_working: smart_working,
                    cig:cig,
                },
                success: function(response) {

                    console.log(response);
                    if(response.success) {

                      calendar.addEvent( {
                          id: response.data.id,
                          title: response.data.title,
                          start: response.data.start,
                          end: response.data.end,
                          extendedProps: {
                            project: project,
                            type: type,
                            smart_working: smart_working,
                            cig:cig,
                          }
                      });

                      toastr.success("Successfully added!");


                      setTimeout(function(){
                        rvtimecard.modal('hide');
                      },300);

                    }
                  }
              });

            }


        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#rv_calendar').length > 0 ) RVCalendar.init();
});
