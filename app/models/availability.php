<?php

// Class 'Availability' handles availability operations
class Availability
{
    private $conn; // Private variable to hold the database connection
    private $table_name = "availability"; // Table name in the database

    // Public variables representing availability attributes
    public $availability_id;
    public $dentist_id;
    public $start_time;
    public $end_time;
    public $name;
    public $price;

    // Constructor of the class
    public function __construct($db)
    {
        $this->conn = $db; // Assigning the database connection to the variable
    }

    // Function to create new availability
    public function create()
    {
        // SQL query to insert new availability into the database
        $query = "INSERT INTO " . $this->table_name . " (dentist_id, start_time, end_time, name, price) VALUES (:dentist_id, :start_time, :end_time, :name, :price)";

        $stmt = $this->conn->prepare($query); // Prepare the statement

        // Sanitize and assign values
        $this->dentist_id = htmlspecialchars(strip_tags($this->dentist_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = strip_tags($this->price);

        // Bind parameters to the query
        $stmt->bindParam(':dentist_id', $this->dentist_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);

        // Execute the query and log the operation
        if ($stmt->execute()) {
            error_log("New availability added: Dentist ID " . $this->dentist_id . ", Procedure Name: " . $this->name . ", Start Time: " . $this->start_time . ", End Time: " . $this->end_time . ", Price: " . $this->price);
            return true;
        }

        return false;
    }

    // Function to update existing availability
    public function update()
    {
        // SQL query to update existing availability
        $query = "UPDATE " . $this->table_name . " SET start_time = :start_time, end_time = :end_time WHERE availability_id = :availability_id";

        $stmt = $this->conn->prepare($query); // Prepare the statement

        // Sanitize and assign values
        $this->availability_id = htmlspecialchars(strip_tags($this->availability_id));
        $this->start_time = htmlspecialchars(strip_tags($this->start_time));
        $this->end_time = htmlspecialchars(strip_tags($this->end_time));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = strip_tags($this->price);

        // Bind parameters to the query
        $stmt->bindParam(':availability_id', $this->availability_id);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);

        // Execute the query and log the operation
        if ($stmt->execute()) {
            error_log("Availability updated: Dentist ID " . $this->dentist_id . ", Procedure Name: " . $this->name . ", Start Time: " . $this->start_time . ", End Time: " . $this->end_time . ", Price: " . $this->price);
            return true;
        }

        return false;
    }

    // Function to get all availabilities for a specific dentist
    public function getAllAvailability($dentist_id)
    {
        // Get current date and time
        $currentDateTime = date('Y-m-d H:i:s');

        // SQL query to get all availabilities for a specific dentist
        $query = "SELECT * FROM " . $this->table_name . " WHERE dentist_id = :dentist_id AND end_time >= :currentDateTime ORDER BY start_time ASC";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(':dentist_id', $dentist_id); // Bind dentist ID
        $stmt->bindParam(':currentDateTime', $currentDateTime); // Bind current date and time
        $stmt->execute(); // Execute the query

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return results as an associative array
    }

    // Function to get future availabilities for all dentists
    public function getFutureAvailability()
    {
        // Get current date and time
        $currentDate = date('Y-m-d H:i:s');

        // SQL query to get future availability
        $query = "SELECT a.availability_id, a.dentist_id, a.name, a.price, a.start_time, a.end_time, d.first_name, d.last_name
          FROM " . $this->table_name . " a 
          JOIN dentists d ON a.dentist_id = d.dentist_id 
          WHERE a.start_time > :currentDate 
          ORDER BY a.start_time ASC";

        $stmt = $this->conn->prepare($query); // Prepare the statement
        $stmt->bindParam(':currentDate', $currentDate); // Bind current date
        $stmt->execute(); // Execute the query

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return results as an associative array
    }

    // Function to delete availability for a specific dentist
    public function delete()
    {
        // SQL query to delete availability
        $query = "DELETE FROM " . $this->table_name . " WHERE availability_id = :availability_id";

        $stmt = $this->conn->prepare($query); // Prepare the statement

        // Sanitize and assign availability ID
        $this->availability_id = htmlspecialchars(strip_tags($this->availability_id));
        $stmt->bindParam(':availability_id', $this->availability_id); // Bind availability ID

        // Execute the query and return the result
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
