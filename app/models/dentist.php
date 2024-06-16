<?php

require_once 'user.php'; // Include the base User class
require_once 'IDentistInterface.php'; // Include the Dentist Interface
require_once 'DentistTrait.php'; // Include the Dentist Trait for additional methods

class Dentist extends User implements IDentistInterface
{
    use DentistTrait; // Use the DentistTrait for additional methods

    private $db; // Private variable to hold the database connection
    private $table_name = "dentists"; // Table name in the database

    // Attributes corresponding to columns in 'dentists' table
    public $dentist_id;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $specialization;
    public $role;

    // Constructor with database connection
    public function __construct($db)
    {
        $this->db = $db;
    }

    // Function to create a new dentist
    public function create()
    {
        // SQL query to insert a new record
        $query = "INSERT INTO " . $this->table_name . "
              SET first_name=:first_name, last_name=:last_name, email=:email, password=:password, specialization=:specialization";

        $stmt = $this->db->prepare($query); // Prepare the statement

        // Sanitize and bind data
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Hash the password
        $this->specialization = htmlspecialchars(strip_tags($this->specialization));

        // Bind parameters
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":specialization", $this->specialization);

        // Execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Function to log in a dentist
    public function login($email, $password)
    {
        // SQL query to fetch dentist information based on email
        $query = "SELECT dentist_id, first_name, last_name, email, password, role FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $email = htmlspecialchars(strip_tags($email)); // Sanitize and escape email
        $stmt->bindParam(':email', $email); // Bind email to the query

        $stmt->execute(); // Execute the query

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch results

            // Assign data to variables
            $dentist_id = $row['dentist_id'];
            $hashed_password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $role = $row['role'];

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $dentist_id;
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["last_name"] = $last_name;
                $_SESSION["role"] = $role;

                return true;
            } else {
                return false; // Incorrect password
            }
        } else {
            return false; // Dentist with given email does not exist
        }
    }

    // Function to get dentist information by ID
    public function getDentistById($dentist_id)
    {
        // SQL query to fetch dentist information by ID
        $query = "SELECT * FROM dentists WHERE dentist_id = :dentist_id";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->bindParam(':dentist_id', $dentist_id); // Bind dentist ID to the query
        $stmt->execute(); // Execute the query

        if ($stmt->rowCount() == 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC); // Return dentist information
        } else {
            return false; // Dentist with given ID not found
        }
    }

    // Function to read all dentists
    public function readAll()
    {
        // SQL query to fetch all dentists
        $query = "SELECT dentist_id, first_name, last_name, email, role, specialization FROM " . $this->table_name . " ORDER BY last_name, first_name";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->execute(); // Execute the query

        return $stmt; // Return query results
    }

    // Function to update dentist profile
    public function updateProfile($dentistId, $firstName, $lastName, $email, $specialization)
    {
        // Check if email is already used by another dentist
        if ($this->isEmailUsedByAnotherDentist($dentistId, $email)) {
            return false; // If yes, return false
        }

        // SQL query to update dentist profile
        $query = "UPDATE " . $this->table_name . " 
          SET first_name = :first_name, 
              last_name = :last_name, 
              email = :email,
              specialization = :specialization
          WHERE dentist_id = :dentist_id";

        $stmt = $this->db->prepare($query); // Prepare the statement

        // Sanitize and bind data
        $firstName = htmlspecialchars(strip_tags($firstName));
        $lastName = htmlspecialchars(strip_tags($lastName));
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':specialization', $specialization);
        $stmt->bindParam(':dentist_id', $dentistId);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Update successful
        } else {
            return false; // Update failed
        }
    }

    // Function to delete a dentist from the database
    public function delete($dentistId)
    {
        // SQL query to delete a dentist
        $query = "DELETE FROM " . $this->table_name . " WHERE dentist_id = :dentist_id";

        $stmt = $this->db->prepare($query); // Prepare the statement

        // Sanitize and bind data
        $this->dentist_id = htmlspecialchars(strip_tags($dentistId));
        $stmt->bindParam(':dentist_id', $this->dentist_id);

        // Execute the query
        if ($stmt->execute()) {
            return true; // Delete successful
        }

        return false; // Delete failed
    }

    // Function to check if a dentist has administrator role
    public function isAdministrator($dentist_id)
    {
        $query = "SELECT role FROM " . $this->table_name . " WHERE dentist_id = :dentist_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':dentist_id', $dentist_id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['role'] === 'administrator') {
                return true;
            }
        }
        return false;
    }

    // Function to check if email exists in the database
    public function isEmailExists($email)
    {
        // SQL query to check if email exists
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->bindParam(":email", $email); // Bind email parameter
        $stmt->execute(); // Execute the query

        // Check if email exists
        if ($stmt->fetchColumn() > 0) {
            return true; // Email exists
        } else {
            return false; // Email does not exist
        }
    }

    // Function to check if email is used by another dentist
    public function isEmailUsedByAnotherDentist($dentistId, $email)
    {
        // SQL query to check if email is used by another dentist
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " 
                  WHERE email = :email AND dentist_id != :dentist_id";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->bindParam(':email', $email); // Bind email parameter
        $stmt->bindParam(':dentist_id', $dentistId); // Bind dentist ID parameter
        $stmt->execute(); // Execute the query

        // Check if email is used by another dentist
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    // Function to send a message
    public function sendMessage($conversationId, $message)
    {
        // SQL query to insert a message
        $query = "INSERT INTO messages (conversation_id, sender_id, sender_role, message_text) VALUES (:conversation_id, :sender_id, 'doctor', :message_text)";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->bindParam(':conversation_id', $conversationId); // Bind conversation ID parameter
        $stmt->bindParam(':sender_id', $_SESSION['user_id']); // Bind sender ID (logged-in user ID)
        $stmt->bindParam(':message_text', $message); // Bind message text parameter

        return $stmt->execute(); // Execute the query and return true/false
    }

    // Function to get messages from a conversation
    public function getMessages($conversationId)
    {
        // SQL query to fetch messages by conversation ID
        $query = "SELECT * FROM messages WHERE conversation_id = :conversationId ORDER BY created_at ASC";

        $stmt = $this->db->prepare($query); // Prepare the statement
        $stmt->bindParam(':conversationId', $conversationId); // Bind conversation ID parameter
        $stmt->execute(); // Execute the query

        $messages = []; // Initialize an empty array for messages
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $row; // Add each message to the array
        }

        return $messages; // Return array of messages
    }
}