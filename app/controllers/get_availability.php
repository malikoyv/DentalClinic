<?php
require_once '../../config/database.php';
require_once '../models/availability.php';
require_once '../models/appointment.php';

// Establish database connection
$database = new Database();
$db = $database->getConnection();

// Initialize model objects
$availability = new Availability($db);
$appointments = new Appointment($db);

// Get future available slots
$availableSlots = $availability->getFutureAvailability();

// Get future booked appointments
$bookedAppointments = $appointments->getFutureAppointments();

// Filter availability considering booked appointments
$filteredAvailability = [];
foreach ($availableSlots as $slot) {
    $startTime = new DateTime($slot['start_time']);
    $endTime = new DateTime($slot['end_time']);

    // Divide availability into 50-minute sessions with 5-minute breaks
    while ($startTime < $endTime) {
        $sessionEnd = clone $startTime;
        $sessionEnd->add(new DateInterval('PT55M')); // Session length

        $nextSessionStart = clone $sessionEnd;
        $nextSessionStart->add(new DateInterval('PT5M')); // Break between sessions

        // Check if the slot is booked
        $isBooked = false;
        foreach ($bookedAppointments as $appointment) {
            $appointmentTime = new DateTime($appointment['appointment_date']);

            // Check if appointment time overlaps with the session
            if (
                $slot['dentist_id'] == $appointment['dentist_id'] &&
                $appointmentTime >= $startTime && $appointmentTime < $sessionEnd &&
                $appointment['status'] === 'scheduled'
            ) {
                $isBooked = true;
                break;
            }
        }

        // If slot is not booked, add to filtered availability list
        if (!$isBooked) {
            $filteredAvailability[] = [
                'dentist_id' => $slot['dentist_id'],
                'start_time' => $startTime->format('Y-m-d H:i:s'),
                'end_time' => $sessionEnd->format('Y-m-d H:i:s'),
                'name' => $slot['name'],
                'price' => $slot['price'],
                'first_name' => $slot['first_name'],
                'last_name' => $slot['last_name']
            ];
        }

        $startTime = $nextSessionStart; // Move to next session
    }
}

// Return formatted availability data in JSON format
echo json_encode($filteredAvailability);
?>