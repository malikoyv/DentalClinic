<!-- Dentist list, which is a shared file -->

<?php

// Loading database configuration file and model class
require_once '../../config/database.php';
require_once '../models/dentist.php';

// Initializing database object and establishing connection
$database = new Database();
$db = $database->getConnection();

// Initializing dentist object
$dentist = new Dentist($db);

// Retrieving the list of dentists
$stmt = $dentist->readAll();

// Starting the responsive container
echo "<div class='table-responsive'>";
echo "<table class='table table-striped'>";
echo "<thead class='thead-dark'>";
echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Specialization</th><th>Actions</th></tr>";
echo "</thead>";
echo "<tbody>";

// Iterating through results and displaying each dentist
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    echo "<tr>";
    echo "<td>{$dentist_id}</td>";
    echo "<td>{$first_name}</td>";
    echo "<td>{$last_name}</td>";
    echo "<td>{$email}</td>";
    echo "<td>{$specialization}</td>";
    echo "<td>";
    echo "<a href='dentist_edit.php?dentist_id={$dentist_id}' class='btn btn-primary'>Edit</a>";
    echo " <a href='#' data-id='{$dentist_id}' class='btn btn-danger delete-btn'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>

<script>
    // Confirmation dialog for deleting a dentist
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const dentistId = this.getAttribute('data-id');

            // Displaying a confirmation dialog for deleting
            Swal.fire({
                title: "Are you sure?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete dentist",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../controllers/delete_dentist_controller.php?dentist_id=' + dentistId;
                }
            });
        });
    });
</script>
