
window.onload = () => {
    let calendarElt = document.querySelector("#calendrier");
    let data = JSON.parse(calendarElt.getAttribute('data-events'));
    let calendar = new FullCalendar.Calendar(calendarElt,{

        initialView : 'dayGridMonth',
        locale: 'fr',
        firstDay : 1,
        dayHeaderFormat : {weekday : 'long'},
        timeZone: 'Europe/Paris',
        headerToolbar : {
            start: 'prev,next today',
            center:'title',
            end: 'dayGridMonth,timeGridWeek'
        },
        buttonText : {
            today : "Aujourd'hui",
            month : 'Mois' ,
            week : 'Semaine',
        },
        weekNumbers : true,
        weekText : "S",
        nowIndicator: true,
        selectable : true,

        events : data,

    })
    calendar.render()

}