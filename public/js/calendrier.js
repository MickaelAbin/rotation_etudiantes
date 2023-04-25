window.onload = () => {
    let calendarElt = document.querySelector("#calendrier")

    let calendar = new FullCalendar.Calendar(calendarElt,{
        initialView : 'dayGridMonth',
        locale: 'fr',
        default: 1 ,
        timeZone: 'Europe/Paris',
        headerToolbar : {
            start: 'prev,next today',
            center:'title',
            end: 'dayGridMonth,timeGridWeek'
        },

    })
    calendar.render()
}