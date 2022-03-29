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

            $('#rvtag').select2({
              placeholder: 'Please Select',
              data: rvdata
            });

            if ( rvasanadata ) {
              $('#rvasana').select2({
                placeholder: 'Please Select',
                data: rvasanadata
              });
            }

            var calendarEl = document.getElementById('rv_calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','listPlugin' ],
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
                            action:"rv_fetch_event",
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
                  editmodal.find('#rvtag').val('').trigger("change");
                  editmodal.find('#rvasana').val('').trigger("change");

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
                        action:"rv_edit_event",
                        cid: info.event.id,
                        title: info.event.title,
                        start:moment(info.event.start).format(),
                        end: moment(info.event.end).format(),
                        mode: 'edit',
                        tag: info.event.extendedProps.tag,
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
                        action:"rv_edit_event",
                        cid: info.event.id,
                        title: info.event.title,
                        start:moment(info.event.start).format(),
                        end: moment(info.event.end).format(),
                        mode: 'edit',
                        tag: info.event.extendedProps.tag,
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
                  editmodal.find('#rvasana').val('').trigger("change");
                  if (info.event.extendedProps.tag) {
                    editmodal.find('#rvtag').val(info.event.extendedProps.tag).trigger("change");
                  }else{
                    editmodal.find('#rvtag').val('').trigger("change");
                  }
                  editmodal.find('#rvmoddel').fadeIn();

                  currentedit = info.event;
                  // alert('Event: ' + calEvent.title);
                  // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                  // alert('View: ' + view.name);

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
                      action:"rv_del_event",
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

              var title = $('#rvtitle').val();
              var start = $('#rvstart').val();
              var end = $('#rvend').val();
              var cid = $('#rvcid').val();
              var mode = $('#rvmode').val();
              var tag = $('#rvtag').val();
              var asana = $('#rvasana').val();

              if ( title == null || title.length == 0 ) {
                if ( asana == '' ) {
                  alert('Please insert a title!');
                  return false;
                }
              }

              //if ( (title !== null && title.length != 0) || (asana != '') ) {
                  $.ajax({
                     type : "POST",
                     url : rvlocalize.ajaxurl,
                     data : {
                        action:"rv_edit_event",
                        cid: cid,
                        title: title,
                        start:start,
                        end: end,
                        tag: tag,
                        mode: mode,
                        asana : asana
                    },
                    success: function(response) {


                        if(response.success) {

                          if ( response.data.mode == 'add' ) {
                            if (!tag) {
                              tag = response.data.tag;
                            }

                            calendar.addEvent( {
                                id: response.data.id,
                                title: response.data.title,
                                start:start,
                                end: end,
                                extendedProps: { tag: tag }
                            });

                            toastr.success("Successfully added!");

                          }else if ( response.data.mode == 'edit' ) {

                            currentedit.setProp( 'title', title )
                            currentedit.setStart(start);
                            currentedit.setEnd(end);
                            currentedit.setExtendedProp('tag',tag);

                            toastr.success("Successfully updated!");
                          }
                          setTimeout(function(){
                            editmodal.modal('hide');
                          },300);

                        }
                      }
                  });

              // }else{
              //
              // }
            }

            editmodal.on('shown.bs.modal',function(){
              $('#rvtitle').focus();
            });


        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#rv_calendar').length > 0 ) RVCalendar.init();
});
