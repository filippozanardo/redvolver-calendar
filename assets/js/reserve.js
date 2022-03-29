"use strict";

var RVReserve = function() {

    return {
        //main function to initiate the module
        init: function() {
          var calendarEl = document.getElementById('rv_reserve');

          var calendar = new FullCalendar.Calendar(calendarEl, {
            // plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','listPlugin', 'resourceTimeGrid','googleCalendar'  ],
            plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list','listPlugin','googleCalendar' ],
            // plugins: [],
            timeZone: 'UTC',
            // defaultView: 'resourceBlossom',
            weekends: false,
            height: 800,
            contentHeight: 780,
            aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio
            nowIndicator: true,
            selectable: true,
            editable: true,
            header: {
              left: 'prev,next',
              center: 'title',
              right: 'resourceTimeGridDay,resourceBlossom'
            },
            views: {
              resourceBlossom: {
                type: 'resourceTimeGrid',
                duration: { weeks:1 },
                buttonText: 'week'
              }
            },
            googleCalendarApiKey: 'AIzaSyB2ofFpcIt9U5AbxhfYDfc67k38klWQFUU',
            events: {
              googleCalendarId: 'filippo@blossoming.it'
            },
            // resources: [
            //   { id: 'a', title: 'SALA RIUNIONI' },
            //   { id: 'b', title: 'CUCINA' },
            //   { id: 'c', title: 'SALA MADONNA' }
            // ],
            // events: 'https://fullcalendar.io/demo-events.json?with-resources=3'
          });

          calendar.render();



        }
    };
}();

jQuery(document).ready(function() {
    if ( $('#rv_reserve').length > 0 ) RVReserve.init();
});
