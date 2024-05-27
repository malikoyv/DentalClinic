<?php
require_once 'user.php';
require_once 'IPatientInterface.php';
require_once 'PatientTraits.php';

// Klasa 'Patient' odpowiada za obsługę pacjentów
class Patient extends User implements IPatientInterface
{
    use PatientTrait;
    private $db; // Prywatna zmienna do przechowywania połączenia z bazą danych
    private $table_name = "patients"; // Nazwa tabeli w bazie danych

    // Konsktruktor klasy
    public function __construct($db)
    {
        $this->db = $db; // Przypisanie połączenia do zmiennej
    }

    // Funkcja do dodawania nowego pacjenta
    public function addNewPatient($firstName, $lastName, $email, $password)
    {
        if ($this->isEmailExists($email)) {
            error_log("Email " . $email . " już istnieje w bazie danych.");
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)";
        $stmt = $this->db->prepare($query);

        $firstName = htmlspecialchars(strip_tags($firstName));
        $lastName = htmlspecialchars(strip_tags($lastName));
        $email = htmlspecialchars(strip_tags($email));
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHashed);

        if ($stmt->execute()) {
            error_log("Pacjent " . $firstName . " " . $lastName . " został dodany do bazy danych.");
            return true;
        } else {
            error_log("Błąd dodawania pacjenta: " . implode(";", $stmt->errorInfo()));
            return false;
        }
    }

    // Funkcja, która loguje pacjenta
    public function login($email, $password)
    {
        $query = "SELECT patient_id, first_name, last_name, email, password, role FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->db->prepare($query);

        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $row['password'])) {
                $_SESSION["loggedin"] = true;
                $_SESSION["user_id"] = $row['patient_id'];
                $_SESSION["email"] = $row['email'];
                $_SESSION["first_name"] = $row['first_name'];
                $_SESSION["last_name"] = $row['last_name'];
                $_SESSION["role"] = $row['role'];

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Funkcja, która sprawdza czy email jest już używany
    public function isEmailExists($email)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Funkcja, która sprawdza czy email jest już używany przez innego pacjenta
    public function isEmailUsedByAnotherPatient($patientId, $email)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE email = :email AND patient_id != :patient_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Funkcja, która aktualizuje dane osobowe pacjenta
    public function updateProfile($patientId, $firstName, $lastName, $email)
    {
        if ($this->isEmailUsedByAnotherPatient($patientId, $email)) {
            error_log("Podany adres email jest używany.");
            return false;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET first_name = :first_name, 
                      last_name = :last_name, 
                      email = :email 
                  WHERE patient_id = :patient_id";
        $stmt = $this->db->prepare($query);

        $firstName = htmlspecialchars(strip_tags($firstName));
        $lastName = htmlspecialchars(strip_tags($lastName));
        $email = htmlspecialchars(strip_tags($email));
        $patientId = htmlspecialchars(strip_tags($patientId));

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':patient_id', $patientId);

        if ($stmt->execute()) {
            error_log("Pacjent " . $_SESSION['first_name'] . " " . $_SESSION['last_name'] . " zaktualizował dane osobowe.");
            return true;
        } else {
            error_log("Błąd aktualizacji danych: " . implode(";", $stmt->errorInfo()));
            return false;
        }
    }

    // Funkcja, która aktualizuje hasło pacjenta
    public function changePassword($patientId, $currentPassword, $newPassword)
    {
        $query = "SELECT password FROM " . $this->table_name . " WHERE patient_id = :patient_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $patientId);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($currentPassword, $row['password'])) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE " . $this->table_name . " SET password = :new_password WHERE patient_id = :patient_id";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->bindParam(':new_password', $newHashedPassword);
                $updateStmt->bindParam(':patient_id', $patientId);

                if ($updateStmt->execute()) {
                    error_log("Hasło zostało pomyślnie zaktualizowane.");
                    return true;
                } else {
                    error_log("Błąd aktualizacji hasła: " . implode(";", $updateStmt->errorInfo()));
                }
            }
        }
        return false;
    }

    // Walidacja emaila
    public function validateEmail($email)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        return preg_match($regex, $email) === 1;
    }

    // Rozpoczęcie nowej rozmowy
    public function startConversation($doctorId)
    {
        $query = "SELECT conversation_id FROM conversations WHERE patient_id = :patient_id AND doctor_id = :doctor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':patient_id', $_SESSION['user_id']);
        $stmt->bindParam(':doctor_id', $doctorId);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $query = "INSERT INTO conversations (patient_id, doctor_id) VALUES (:patient_id, :doctor_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':patient_id', $_SESSION['user_id']);
            $stmt->bindParam(':doctor_id', $doctorId);
            $stmt->execute();
            return $this->db->lastInsertId();
        } else {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['conversation_id'];
        }
    }

    // Wysyłanie wiadomości
    public function sendMessage($conversationId, $message)
    {
        $sender_id = 1;
        $query = "INSERT INTO messages (conversation_id, sender_id, sender_role, message_text) VALUES (:conversation_id, :sender_id, 'patient', :message_text)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':conversation_id', $conversationId);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':message_text', $message);

        if ($stmt->execute()) {
            error_log("Wiadomość została wysłana.");
            return true;
        } else {
            error_log("Błąd wysyłania wiadomości: " . implode(";", $stmt->errorInfo()));
            return false;
        }
    }

    public function getMessages($conversationId) {
        $query = "SELECT * FROM messages WHERE conversation_id = :conversationId ORDER BY created_at ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':conversationId', $conversationId);
        $stmt->execute();
    
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = $row;
        }
        return $messages;
    }
    
}
?>