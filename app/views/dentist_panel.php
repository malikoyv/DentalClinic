<?php
session_start();

// Checking if the user is logged in and has the role of a dentist
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'dentist') {
    header("location: dentist_login.php");
    exit;
}

// Including database configuration file, dentist model, and availability model
require_once '../../config/database.php';
require_once '../models/dentist.php';
require_once '../models/availability.php';

// Creating a database object
$database = new Database();
$db = $database->getConnection();

// Creating Dentist object
$dentist = new Dentist($db);

// Creating Availability object
$availability = new Availability($db);

// Fetching availability information based on dentist ID
$availabilityData = $availability->getAllAvailability($_SESSION['user_id']);
// Fetching dentist data based on ID
$dentist_data = $dentist->getDentistById($_SESSION["user_id"]);

if ($dentist_data === false) {
    // Handling error if dentist data is not found
    echo "Error: Unable to find dentist data.";
    exit;
}

// Greeting the dentist
$firstName = htmlspecialchars($_SESSION["first_name"]);
$lastName = htmlspecialchars($_SESSION["last_name"]);
$lastChar = strtolower(substr($firstName, -1)); // Getting the last character of the first name

// Example logic to determine gender based on the last letter of the first name
if (in_array($lastChar, ['a', 'e', 'i', 'o', 'u', 'y'])) {
    // Probably female
    $greeting = "Good day, Dr. ";
} else {
    // Probably male
    $greeting = "Good day, Dr. ";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentist Panel</title>
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'shared_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center" id="profile-section">
                    <h2><?php echo $greeting; ?> <strong><?php echo $firstName . " " . $lastName; ?></strong></h2>
                </div>

                <div class="card">
                    <div class="row">
                        <div class="col-sm-6">
                            <h2 id="appointmentsHeader"></h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-5">
                                    <a href="../controllers/export_appointments_controller.php" class="btn btn-secondary w-100">CSV</a>
                                </div>
                                <div class="col-7">
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle w-100" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            Filter Appointments
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                            <li><a class="dropdown-item" href="#" onclick="loadAppointments('scheduled', false, 'scheduled:')">Scheduled</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="loadAppointments('cancelled_by_patient', false, 'cancelled by patient:')">Cancelled by Patient</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="loadAppointments('cancelled_by_dentist', false, 'cancelled by dentist:')">Cancelled by Dentist</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="loadAppointments('', false, 'all:')">All</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table with appointments, sortable by clicking buttons next to headers -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="appointments-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Appointment Date and Time <button class="btn btn-light btn-sm" onclick="sortAppointments('date')"><i class="bi bi-sort-down"></i></button></th>
                                    <th class="align-middle">Patient <button class="btn btn-light btn-sm" onclick="sortAppointments('patient')"><i class="bi bi-sort-alpha-down"></i></button></th>
                                    <th class="align-middle">Status</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table rows with appointments, dynamically generated using AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- Table displaying availability -->
                <div class="card">
                    <?php if (!empty($_SESSION['start_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['start_time_err']; ?></div>
                        <?php unset($_SESSION['start_time_err']); ?>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['end_time_err'])) : ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['end_time_err']; ?></div>
                        <?php unset($_SESSION['end_time_err']); ?>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success_message'])) : ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <div class="availability-section">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Availability:</h2>
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-5">
                                        <a href="../controllers/export_availability_controller.php" class="btn btn-secondary w-100">CSV</a>
                                    </div>
                                    <div class="col-7">
                                        <button onclick="toggleSection('add-availability-section', true)" class="btn btn-primary w-100">Add New</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Delete</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($availabilityData as $slot) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($slot['start_time']); ?></td>
                                            <td><?php echo htmlspecialchars($slot['end_time']); ?></td>
                                            <td>
                                                <a href="#" data-id="<?php echo $slot['availability_id']; ?>" class="btn btn-sm btn-danger delete-availability-btn"><i class="bi bi-trash"></i></a>
                                            </td>
                                            <td>
                                                <a href="#" data-id="<?php echo $slot['availability_id']; ?>" class="btn btn-sm btn-primary edit-availability-btn"><i class="bi bi-pen"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>


                <!-- Hidden section for editing availability -->
                <div class="card" id="edit-availability-section" style="display: none; padding-top:6rem">
                    <h3>Edit Availability</h3>
                    <form id="edit-availability-form" action="../controllers/dentist_availability_controller.php" method="post">
                        <input type="hidden" id="edit-availability-id" name="availability_id">
                        <div class="mb-3">
                            <label for="edit-start-time">Start Time:</label>
                            <input type="datetime-local" id="edit-start-time" name="start_time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit-end-time">End Time:</label>
                            <input type="datetime-local" id="edit-end-time" name="end_time" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleSection('edit-availability-section', false)">Cancel</button>
                    </form>
                </div>


                <!-- Hidden section for adding new availability -->
                <div class="card" id="add-availability-section" style="display: none; padding-top:6rem">
                    <h4>Add New Availability:</h4>
                    <form action="../controllers/dentist_availability_controller.php" method="post">
                        <input type="hidden" name="dentist_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <div class="mb-3">
                            <label for="start_time">Procedure Name:</label>
                            <input type="text" id="name" name="name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="start_time">Start Time:</label>
                            <input type="datetime-local" id="start_time" name="start_time" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="end_time">End Time:</label>
                            <input type="datetime-local" id="end_time" name="end_time" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="end_time">Price:</label>
                            <input type="number" id="price" name="price" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Add Availability</button>
                        <button type="button" class="btn btn-secondary" onclick="toggleSection('add-availability-section', false)">Cancel</button>
                    </form>
                </div>


                <!-- Section with personal data -->
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Personal Information:</h2>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <p><strong>First Name:</strong> <?php echo htmlspecialchars($_SESSION["first_name"]); ?></p>
                                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($_SESSION["last_name"]); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                            </div>
                            <div>
                                <p><strong>To change personal information, please contact the system administrator.</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js" integrity="sha256-IW9RTty6djbi3+dyypxajC14pE6ZrP53DLfY9w40Xn4=" crossorigin="anonymous"></script>
    <script>
        // JavaScript function for deleting availability
        document.querySelectorAll('.delete-availability-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const availabilityId = this.getAttribute('data-id');

                // Display confirmation message for deleting availability
                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete availability",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../controllers/delete_availability_controller.php?availability_id=' + availabilityId;
                    }
                });
            });
        });

        // JavaScript function for editing availability
        document.querySelectorAll('.edit-availability-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const availabilityId = this.getAttribute('data-id');

                // Load availability data
                var availabilityData = <?php echo json_encode($availabilityData); ?>;

                // Find data for selected ID
                var slotData = availabilityData.find(slot => slot.availability_id == availabilityId);

                if (slotData) {
                    document.getElementById('edit-availability-id').value = slotData.availability_id;
                    document.getElementById('edit-start-time').value = slotData.start_time;
                    document.getElementById('edit-end-time').value = slotData.end_time;

                    toggleSection('edit-availability-section', true);
                }
            });
        });

        // Function to show/hide section
        function toggleSection(sectionId, show) {
            var section = document.getElementById(sectionId);
            if (section) {
                section.style.display = show ? 'block' : 'none';

                if (show) {
                    section.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        }
    </script>
    <script src='public/js/dentist_panel.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
