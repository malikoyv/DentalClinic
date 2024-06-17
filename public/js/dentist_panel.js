// Wait for the entire document to load
document.addEventListener('DOMContentLoaded', async function () {
    await updateAppointmentsStatus(); // Update appointment statuses
    loadAppointments('scheduled', true, 'scheduled'); // Load appointments
});

// Function to cancel an appointment
function cancelAppointment(appointmentId) {
    Swal.fire({
        // Using SweetAlert2 library to display a confirmation dialog
        // Configuring the dialog window
        title: 'Are you sure you want to cancel this appointment?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, cancel',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                // AJAX call to cancel the appointment
                url: '../../app/controllers/dentist_cancel_appointment.php',
                type: 'POST',
                data: { appointment_id: appointmentId },
                success: function (response) {
                    const data = JSON.parse(response);
                    Swal.fire(
                        'Cancelled!',
                        data.message,
                        'success'
                    );
                    loadAppointments();
                },
                error: function (error) {
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

// Function to change appointment status
function changeAppointmentStatus(appointmentId, newStatus) {
    // Using SweetAlert2 library to display a confirmation dialog
    Swal.fire({
        title: 'Change appointment status',
        text: `Confirm that the patient did not show up for the appointment`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, patient did not show up',
        cancelButtonText: 'No'
    }).then((result) => {
        // If the user confirmed the status change
        if (result.isConfirmed) {
            $.ajax({
                // AJAX request to change appointment status
                url: '../../app/controllers/appointment_change_status.php',
                type: 'POST',
                data: { appointment_id: appointmentId, new_status: newStatus },
                success: function (response) {
                    // If the request was successful, display a message
                    const data = JSON.parse(response);
                    Swal.fire(
                        'Changed!',
                        data.message,
                        'success'
                    );
                    loadAppointments(); // Reload appointments
                },
                // If there was an error, display an error message
                error: function (error) {
                    Swal.fire(
                        'Error!',
                        'Failed to change appointment status.',
                        'error'
                    );
                }
            });
        }
    });
}

// Declaration of a global variable to store all appointments
var globalAppointments = [];

// Function to load appointments
function loadAppointments(filterStatus = 'scheduled', isInitialLoad = false, filterText = 'scheduled:') {
    document.getElementById('appointmentsHeader').textContent = 'Appointments ' + filterText;
    $.ajax({
        // AJAX request to fetch dentist appointments
        url: '../../app/controllers/get_dentist_appointments.php',
        type: 'GET',
        success: function (response) {
            // If the request was successful, save appointments to the global variable
            globalAppointments = JSON.parse(response);
            var statusToFilter = isInitialLoad ? 'scheduled' : filterStatus;
            renderTable(globalAppointments, statusToFilter); // Call function to display appointments in table
        },
        error: function (error) {
            console.log('Error loading appointments', error);
        }
    });
}

// Function to sort appointments
function sortAppointments(sortKey) {
    var sortedAppointments = [...globalAppointments]; // Copy all appointments to a new array
    sortedAppointments.sort(function (a, b) {
        // Sort appointments by date or patient's last name
        if (sortKey === 'date') {
            return new Date(a.appointment_date) - new Date(b.appointment_date);
        } else if (sortKey === 'patient') {
            return a.first_name.localeCompare(b.first_name) || a.last_name.localeCompare(b.last_name);
        }
    });
    renderTable(sortedAppointments);
}

// Function to render appointments table based on status filter
function renderTable(appointments, filterStatus = 'scheduled') {
    var html = '';
    appointments.forEach(function (appointment) {
        // If filterStatus is empty or matches appointment status, add appointment to table
        if (filterStatus === '' || appointment.status === filterStatus) {
            html += '<tr id="appointment-row-' + appointment.appointment_id + '">';
            html += '<td>' + appointment.appointment_date + '</td>';
            html += '<td>' + appointment.first_name + ' ' + appointment.last_name + '</td>';
            html += '<td>' + formatAppointmentStatus(appointment.status) + '</td>';

            // If appointment is scheduled, add button to cancel appointment
            if (appointment.status === 'scheduled') {
                html += '<td><button class="btn btn-danger" onclick="cancelAppointment(' + appointment.appointment_id + ')">Cancel</button></td>';
            }
            // If appointment is completed, add button to change status to "Patient did not show up"
            else if (appointment.status === 'completed') {
                html += '<td><button class="btn btn-warning" onclick="changeAppointmentStatus(' + appointment.appointment_id + ', \'no_show\')">Patient did not show up</button></td>';
            } else {
                html += '<td></td>'; // Empty cell for other statuses
            }

            html += '</tr>';
        }
    });
    $('#appointments-table tbody').html(html);
}

// Helper function to format appointment status
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

// Asynchronous function to update appointment statuses
async function updateAppointmentsStatus() {
    try {
        // Send a request to the server
        const response = await fetch('../../app/controllers/update_appointments_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json(); // Assign response to a variable

        // If the request was successful, log the message to the console
        if (data.success) {
            console.log(data.message);
        } else {
            // If there was an error, log the error message to the console
            console.error(data.error || 'Unknown error');
        }
    } catch (error) {
        // If there was an error communicating with the server, log the message to the console
        console.error('Error communicating with the server:', error);
    }
}
