<?php

// Class 'Appointment' handles appointment operations
class Appointment
{
    private $conn; // Private variable to hold the database connection
    private $table_name = "appointments"; // Table name in the database

    // Public variables representing appointment attributes
    public $appointment_id;
    public $patient_id;
    public $dentist_id;
    public $appointment_date;
    public $status;
    public $name;
    public $price;
    public $notes;

    // Constructor of the class
    public function __construct($db)
    {
        $this->conn = $db; // Assigning the database connection to the variable
    }

    // Function to cancel appointment by patient
    public function cancelByPatient()
    {
        // SQL query to update appointment status
        $query = "UPDATE " . $this->table_name . " SET status = 'cancelled_by_patient' WHERE appointment_id = :appointment_id";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(":appointment_id", $this->appointment_id); // Bind appointment ID to the query parameter

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Function to cancel appointment by dentist
    public function cancelByDentist()
    {
        // SQL query to update appointment status
        $query = "UPDATE " . $this->table_name . " SET status = 'cancelled_by_dentist' WHERE appointment_id = :appointment_id";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(":appointment_id", $this->appointment_id); // Bind appointment ID to the query parameter

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Function to create a new appointment
    public function create()
    {
        // SQL query to insert a new appointment into the database
        $query = "INSERT INTO " . $this->table_name . " (patient_id, dentist_id, appointment_date, status, name, price) VALUES (:patient_id, :dentist_id, :appointment_date, :status, :name, :price)";

        $stmt = $this->conn->prepare($query); // Prepare the statement

        // Sanitize and assign values
        $this->patient_id = htmlspecialchars(strip_tags($this->patient_id));
        $this->dentist_id = htmlspecialchars(strip_tags($this->dentist_id));
        $this->appointment_date = htmlspecialchars(strip_tags($this->appointment_date));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));

        // Bind parameters to the query
        $stmt->bindParam(":patient_id", $this->patient_id);
        $stmt->bindParam(":dentist_id", $this->dentist_id);
        $stmt->bindParam(":appointment_date", $this->appointment_date);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);

        // Execute the query and log the operation
        if ($stmt->execute()) {
            error_log("Appointment created: Patient ID " . $this->patient_id . ", Procedure Name: " . $this->name . ", Dentist ID " . $this->dentist_id . ", Date: " . $this->appointment_date . ", Price: " . $this->price);
            return true;
        }

        return false;
    }

    // Function to get future appointments
    public function getFutureAppointments()
    {
        $currentDate = date('Y-m-d H:i:s'); // Get current date and time

        // SQL query to get future appointments
        $query = "SELECT * FROM " . $this->table_name . " WHERE appointment_date >= :currentDate ORDER BY appointment_date ASC";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(":currentDate", $currentDate); // Bind current date to the query

        $stmt->execute(); // Execute the query
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return results as an associative array
    }

    // Function to get appointments of a specific patient
    public function getPatientAppointments($patient_id)
    {
        // SQL query to get patient appointments
        $query = "SELECT a.appointment_id, a.appointment_date, a.status, d.first_name, d.last_name 
          FROM appointments a 
          JOIN dentists d ON a.dentist_id = d.dentist_id 
          WHERE a.patient_id = :patient_id
          ORDER BY a.appointment_date ASC";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(':patient_id', $patient_id); // Bind patient ID to the query
        $stmt->execute(); // Execute the query

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return results as an associative array
    }

    // Function to get appointments of a specific dentist
    public function getAppointmentsByDentist($dentistId)
    {
        // SQL query to get appointments of a specific dentist
        $query = "SELECT a.appointment_id, a.appointment_date, a.status, p.first_name, p.last_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.patient_id 
          WHERE a.dentist_id = :dentistId
          ORDER BY a.appointment_date ASC";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(':dentistId', $dentistId); // Bind dentist ID to the query
        $stmt->execute(); // Execute the query

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return results as an associative array
    }

    // Function to change appointment status
    public function changeStatus($appointmentId, $newStatus)
    {
        // SQL query to change appointment status
        $sql = "UPDATE appointments SET status = :newStatus WHERE appointment_id = :appointmentId";
        $stmt = $this->conn->prepare($sql); // Prepare the statement
        $stmt->bindParam(':newStatus', $newStatus); // Bind new status
        $stmt->bindParam(':appointmentId', $appointmentId); // Bind appointment ID
        $stmt->execute(); // Execute the query

        return $stmt->rowCount() > 0; // Return true if rows were affected
    }

    // Function to update appointments from 'scheduled' to 'completed' status
    public function updateStatusToCompleted()
    {
        // Set time to mark appointments completed one hour ago
        $currentTime = new DateTime();
        $currentTime->modify('-1 hour');
        $formattedCurrentTime = $currentTime->format('Y-m-d H:i:s');

        // SQL query to update appointments to 'completed' status
        $sql = "UPDATE appointments 
            SET status = 'completed' 
            WHERE status = 'scheduled' AND appointment_date <= :currentTime";

        $stmt = $this->conn->prepare($sql); // Prepare the statement
        $stmt->bindParam(':currentTime', $formattedCurrentTime, PDO::PARAM_STR); // Bind formatted time
        $stmt->execute(); // Execute the query

        return $stmt->rowCount(); // Return number of updated records
    }
}
?>
