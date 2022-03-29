"use strict";

var RVCalendarAll = function() {

    return {
        //main function to initiate the module
        init: function() {
            moment.locale('it');
            var currentedit = null;

            var todayDate = moment().startOf('day');
            var YM = todayDate.format('YYYY-MM');
            var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
            var TODAY = todayDate.format('YYYY-MM-DD');
            var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

            var calendarAll = document.getElementById('rvcalendarall');
            var calendar_all = new FullCalendar.Calendar(calendarAll, {
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

            var currentsource = [];

            $(".satellite").on('change',function(e){
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
    if ( $('#rvcalendarall').length > 0 ) RVCalendarAll.init();
});
