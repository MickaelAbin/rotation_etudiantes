



window.onload = () => {
    let calendarElt = document.querySelector("#calendrier");
    let data = JSON.parse(calendarElt.getAttribute('data-events'));
    let calendar = new FullCalendar.Calendar(calendarElt,{

        initialView : 'dayGridMonth',
        locale: 'fr',
        firstDay : 1,
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

        events : data,
    })
    calendar.render()
}