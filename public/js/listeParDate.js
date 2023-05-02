


window.onload = () => {
    let calendarElt = document.querySelector("#calendrier");
    let data = JSON.parse(calendarElt.getAttribute('data-events'));
    let calendar = new FullCalendar.Calendar(calendarElt,{
        initialView: 'listMonth',
        allDaySlot: false,
        locale: 'fr',
        firstDay : 1,
        dayHeaderFormat : {weekday : 'long'},
        timeZone: 'Europe/Paris',
        headerToolbar : {
            start: 'prev,next today',
            center:'title',
            end: 'today'
        },
        buttonText : {
            today : "Mois en cours",
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