
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
            today : 'Aujourd\'hui',
            month : 'Mois' ,
            week : 'Semaine',
        },

        weekNumbers : true,
        weekText : "S",
        nowIndicator: true,
        selectable : true,

        events : data,

        eventDidMount: function(info) {
            info.el.setAttribute('data-bs-toggle', 'modal')
            info.el.setAttribute('data-bs-target', '#enrolmentModal')
        },

        eventClick: function(info) {
            console.log(info.event.date)
            let modalTitle = document.getElementById('enrolmentModalTitle')
            let modalDate = document.getElementById('enrolmentDate')
            let modalCategory = document.getElementById('enrolmentCategory')
            let modalTime = document.getElementById('enrolmentTime')

            modalTitle.innerText = info.event.extendedProps.student + ' (' + info.event.extendedProps.academicLevel + ')'
            modalDate.innerText = 'Date : ' + info.event.start.toLocaleDateString('fr-FR', {day: 'numeric', month: 'long', year: 'numeric'})
            modalCategory.innerText = 'Service : ' + info.event.extendedProps.clinicalRotationCategory
            modalTime.innerText = 'De ' + info.event.extendedProps.startTime + 'h à ' + info.event.extendedProps.endTime + 'h'
        }

    })



    calendar.render()

}