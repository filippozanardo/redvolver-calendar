"use strict";

var RVCalendarAll = function() {

    return {
        //main function to initiate the module
        init: function() {
            moment.locale('it');

            let calendarAll = document.getElementById('rv_calendar_all');
            let calendar_all = new FullCalendar.Calendar(calendarAll, {
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
            });

            calendar_all.render();

            let currentsource = [];

            $(".user_calendar").on('change',function(e){
              console.log($(this).is(':checked'));
              if ( $(this).is(':checked') ) {
                calendar_all.addEventSource( {
                    id: $(this).data('id'),
                    color: $(this).data('color'),
                    url: rvlocalize.siteurl+'/wp-json/redvolver/v1/fetch_user_event?uid='+$(this).data('id'),
                } );
              }else{
                calendar_all.getEventSourceById( $(this).data('id') ).remove();
              }
            });


        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#rv_calendar_all').length > 0 ) RVCalendarAll.init();
});
