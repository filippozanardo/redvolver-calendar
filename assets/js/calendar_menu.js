"use strict";

var RVMenuCalendar = function() {

    return {
        //main function to initiate the module
        init: function() {
            moment.locale('it');

            $('#kt_calendar_external_events .fc-draggable-handle').each(function() {
                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true, // maintain when user navigates (see docs on the renderEvent method)
                    classNames: [$(this).data('color')],
                    description: 'Lorem ipsum dolor eius mod tempor labore'
                });
            });



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

            var menumodal = $("#rvmenumodal");
            var calendarMenu = document.getElementById('kt_calendar');
            var containerEl = document.getElementById('kt_calendar_external_events');
            var currentmenu = '';

            var Draggable = FullCalendarInteraction.Draggable;

            new Draggable(containerEl, {
                itemSelector: '.fc-draggable-handle',
                eventData: function(eventEl) {
                    return $(eventEl).data('event');
                }
            });

            var calendar_menu = new FullCalendar.Calendar(calendarMenu, {
                plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                locale: 'it',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
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
                defaultView: 'dayGridMonth',
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
                            action:"rv_fetch_daily_menu",
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



                  menumodal.find('#rvstart2').val(moment(info.start).format());
                  menumodal.find('#rvend2').val(moment(info.end).format());
                  menumodal.find('#rvtitle2').val(info.title);
                  menumodal.find('#rvcid2').val(info.id);
                  menumodal.find('#rvmode2').val('add');
                  editmodal.find('#rvmoddel2').fadeIn();

                  currentmenu = info;

                  menumodal.modal({
                      backdrop: 'static'
                  });

                },
                drop: function(dropInfo) {

                    $.ajax({
                       type : "POST",
                       url : rvlocalize.ajaxurl,
                       data : {
                          action:"rv_edit_menu",
                          title: dropInfo.draggedEl.innerText,
                          start:dropInfo.dateStr,
                          end: dropInfo.dateStr,
                          mode: 'add',
                      },
                      success: function(response) {

                          if(response.success) {

                            if ( response.data.mode == 'add' ) {

                              toastr.success("Successfully added!");
                            }

                          }
                        }
                    });
                },
                eventClick: function(info) {

                  menumodal.find('#rvstart2').val(moment(info.event.start).format());
                  menumodal.find('#rvend2').val(moment(info.event.end).format());
                  menumodal.find('#rvtitle2').val(info.event.title);
                  menumodal.find('#rvcid2').val(info.event.id);
                  menumodal.find('#rvmode2').val('edit');
                  editmodal.find('#rvmoddel2').fadeIn();

                  currentmenu = info.event;

                  menumodal.modal({
                      backdrop: 'static'
                  });
                },
            });

            calendar_menu.render();


            $('#spaccotutto').on('click', function(e){
              e.preventDefault();
              $.ajax({
                 type : "POST",
                 url : rvlocalize.ajaxurl,
                 data : {
                    action:"rv_spacca_tutto",
                },
                success: function(response) {
                  console.log(response);
                  toastr.success("MESSAGGIO MANDATO!");

                }
              });
            });

            $('#rvmoddel2').on('click', function(e){
              e.preventDefault();
              var cid = $('#rvcid2').val();

              if ( cid !== null && cid.length != 0 ) {
                $.ajax({
                   type : "POST",
                   url : rvlocalize.ajaxurl,
                   data : {
                      action:"rv_del_menu",
                      cid: cid,
                  },
                  success: function(response) {
                      if(response.success) {
                        var eve = calendar_menu.getEventById( cid );
                        eve.remove();
                        toastr.success("Successfully deleted!");

                        setTimeout(function(){
                          menumodal.modal('hide');
                        },300);

                      }
                    }
                });

              }

            });

            $('#rvmodsave2').on('click', function(e){
              // We don't want this to act as a link so cancel the link action
              e.preventDefault();
              console.log('sotto il segno');
              modalMEdit();
            });

            function modalMEdit(){
                var title = $('#rvtitle2').val();
                var start = $('#rvstart2').val();
                var end = $('#rvend2').val();
                var mode = $('#rvmode2').val();
                var cid = $('#rvcid2').val();

                if ( title == null || title.length == 0 ) {
                  alert('Please insert a title!');
                  return false;
                }

                //if ( (title !== null && title.length != 0) || (asana != '') ) {
                    $.ajax({
                       type : "POST",
                       url : rvlocalize.ajaxurl,
                       data : {
                          action:"rv_edit_menu",
                          cid: cid,
                          title: title,
                          start:start,
                          end: end,
                          mode: mode,
                      },
                      success: function(response) {


                          if(response.success) {

                            if ( response.data.mode == 'add' ) {


                              calendar_menu.addEvent( {
                                  id: response.data.id,
                                  title: response.data.title,
                                  start:start,
                                  end: end,
                              });

                              toastr.success("Successfully added!");

                            }else if ( response.data.mode == 'edit' ) {
                              currentedit.title = title;
                              currentedit.start = start;
                              currentedit.end = end;
                              $('#m_calendar').fullCalendar('updateEvent', currentedit );

                              toastr.success("Successfully updated!");
                            }
                            setTimeout(function(){
                              menumodal.modal('hide');
                            },300);

                          }
                        }
                    });

                // }else{
                //
                // }
              }

        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#kt_calendar').length > 0 ) RVMenuCalendar.init();
});
