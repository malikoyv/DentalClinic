var calendar; // Global variable holding the calendar

// Function to toggle section visibility
function toggleSection(sectionId, show) {
    var section = document.getElementById(sectionId);
    if (section) {
        section.style.display = show ? 'block' : 'none';

        if (show) {
            // If section is to be shown, scroll to it and initialize the calendar with a delay of 500ms
            setTimeout(() => {
                initializeCalendar();
            }, 500);
            section.scrollIntoView({
                behavior: 'smooth'
            });
        }
    }
}

// Function to initialize the calendar
function initializeCalendar() {
    var calendarEl = document.getElementById('calendar');
    var patientId = calendarEl.getAttribute('data-patient-id');
    if (!calendar) {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            // Function to fetch events from the server
            events: function (fetchInfo, successCallback, failureCallback) {
                fetch('../../app/controllers/get_availability.php')
                    .then(response => response.json())
                    .then(data => {
                        const events = data.map(item => { // Mapping data from server to event format
                            return {
                                title: item.first_name + ' ' + item.last_name,
                                start: item.start_time,
                                end: item.end_time,
                                name: item.name,
                                price: item.price,
                                color: 'green',
                                extendedProps: {
                                    dentist_id: item.dentist_id
                                }
                            };
                        });
                        successCallback(events); // Calling successCallback function with events as parameter
                    })
                    .catch(error => failureCallback(error));
            },
            // Function to display a message when an event is clicked
            eventClick: function (info) {
                const prettyDate = new Date(info.event.start).toLocaleString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                // Display confirmation message for appointment reservation
                Swal.fire({
                    title: 'Confirm Reservation',
                    text: 'Do you want to book an appointment with ' + info.event.title + ' on ' + prettyDate + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, book',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const appointmentData = { // Appointment data
                            dentist_id: info.event.extendedProps.dentist_id,
                            appointment_date: info.event.startStr,
                            patient_id: patientId
                        };
                        // If user confirms booking, send request to the server
                        fetch('../../app/controllers/create_appointment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(appointmentData) // Appointment data in JSON format
                        }).then(response => response.json()).then(data => {
                            // If request is successful, display success message
                            Swal.fire({
                                title: 'Reservation Confirmed',
                                text: 'Your appointment has been booked.',
                                icon: 'success'
                            }).then(() => {
                                refreshCalendar(); // Refresh calendar
                                loadAppointments(); // Refresh appointments table
                            });
                        }).catch(error => {
                            // If request fails, display error message
                            Swal.fire({
                                title: 'Booking Error',
                                text: 'Failed to book appointment.',
                                icon: 'error'
                            });
                        });
                    }
                });
            }
        });
    }
    calendar.render(); // Render the calendar
}

// Load appointments on page load
document.addEventListener('DOMContentLoaded', function () {
    loadAppointments(undefined, true);
});

// Function to cancel an appointment
function cancelAppointment(appointmentId) {
    // Using SweetAlert2 library to display a confirmation message
    Swal.fire({
        title: 'Are you sure you want to cancel this appointment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            // If user confirms cancellation, send request to the server
            $.ajax({
                url: '../../app/controllers/patient_cancel_appointment.php',
                type: 'POST',
                data: { appointment_id: appointmentId },
                success: function (response) {
                    // If request is successful, display success message
                    const data = JSON.parse(response);
                    Swal.fire(
                        'Cancelled!',
                        data.message,
                        'success'
                    );
                    loadAppointments(); // Refresh appointments table
                    refreshCalendar(); // Refresh calendar
                },
                error: function (error) {
                    // If request fails, display error message
                    Swal.fire(
                        'Error!',
                        'Failed to cancel the appointment.',
                        'error'
                    );
                }
            });
        }
    });
}

// Global variable to store appointments
var globalAppointments = [];

// Function to fetch appointments from the server
function loadAppointments(filterStatus = 'scheduled', isInitialLoad = false, filterText = 'scheduled:') {
    document.getElementById('appointmentsHeader').textContent = 'Appointments ' + filterText;
    $.ajax({
        // AJAX request to fetch patient appointments
        url: '../../app/controllers/get_patient_appointments.php',
        type: 'GET',
        success: function (response) {
            globalAppointments = JSON.parse(response);
            // If initial load, display appointments table with 'scheduled' status
            var statusToFilter = isInitialLoad ? 'scheduled' : filterStatus;
            renderTable(globalAppointments, statusToFilter);
        },
        error: function (error) {
            console.log('Error loading appointments', error);
        }
    });
}

// Function to sort appointments
function sortAppointments(sortKey) {
    var sortedAppointments = [...globalAppointments];
    sortedAppointments.sort(function (a, b) {
        if (sortKey === 'date') {
            return new Date(a.appointment_date) - new Date(b.appointment_date);
        } else if (sortKey === 'dentist') {
            return a.first_name.localeCompare(b.first_name) || a.last_name.localeCompare(b.last_name);
        }
    });
    renderTable(sortedAppointments);
}

// Function to create appointments table
function renderTable(appointments, filterStatus = 'scheduled') {
    var html = '';
    appointments.forEach(function (appointment) {
        // If filterStatus is empty or matches appointment status, add appointment to table
        if (filterStatus === '' || appointment.status === filterStatus) {
            html += '<tr id="appointment-row-' + appointment.appointment_id + '">';
            html += '<td>' + appointment.appointment_date + '</td>';
            html += '<td>' + appointment.first_name + ' ' + appointment.last_name + '</td>';
            html += '<td>' + formatAppointmentStatus(appointment.status) + '</td>';
            if (appointment.status === 'scheduled') {
                html += '<td><button class="btn btn-danger" onclick="cancelAppointment(' + appointment.appointment_id + ')">Cancel</button></td>';
            } else {
                html += '<td></td>';
            }
            html += '</tr>';
        }
    });
    $('#appointments-table tbody').html(html);
}

// Helper function to format appointment status into Polish
function formatAppointmentStatus(status) {
    switch (status) {
        case 'scheduled':
            return 'Scheduled';
        case 'completed':
            return 'Completed';
        case 'no_show':
            return 'Patient did not show up';
        case 'cancelled_by_patient':
            return 'Cancelled by patient';
        case 'cancelled_by_dentist':
            return 'Cancelled by dentist';
        default:
            return 'Other status';
    }
}

// Function to refresh the calendar
function refreshCalendar() {
    if (calendar) {
        calendar.refetchEvents();
    }
}
