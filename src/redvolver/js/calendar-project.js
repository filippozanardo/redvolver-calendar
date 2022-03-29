"use strict";

var RVCalendarPro = function() {

    return {
        //main function to initiate the module
        init: function() {

            let todayDate = moment().startOf('day');
            let YM = todayDate.format('YYYY-MM');
            let YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
            let TODAY = todayDate.format('YYYY-MM-DD');
            let TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

            let projcalendarEl = document.getElementById('rv_calendar_project');
            let projcalendar = new FullCalendar.Calendar(projcalendarEl, {
                //plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','listPlugin' ],
                plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                themeSystem: 'bootstrap',
                locale: 'it',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                allDaySlot: true,
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
                selectable: false,
                editable: false,
                eventLimit: false, // allow "more" link when too many events
                navLinks: true,
                minTime: '02:00:00',
                maxTime: '02:00:00',
                eventSources: [
                    {
                        url: rvlocalize.ajaxurl,
                        method: 'POST',
                        extraParams: {
                            action:"fetch_project_time",
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
            });

            projcalendar.render();

        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#rv_calendar_project').length > 0 ) RVCalendarPro.init();
});
