<?php
session_start(); // Initialize session

// Check if the user is logged in and has the role of 'patient'
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'patient') {
    header("location: patient_login.php"); // Redirect to patient login page
    exit;
}

// Include file with database connection configuration and Appointment class
require_once '../../config/database.php';
require_once '../models/appointment.php';

// Create a database object
$database = new Database();
$db = $database->getConnection();

// Create an instance of the Appointment class
$appointment = new Appointment($db);

// Get patient appointments
$patientAppointments = $appointment->getPatientAppointments($_SESSION['user_id']);

// Initialize variable with an empty string for update errors
$update_err = "";

// Check if there are update data errors
if (isset($_SESSION['update_err'])) {
    $update_err = $_SESSION['update_err'];
    unset($_SESSION['update_err']); // Clear error from session
}

// Initialize variable for password change errors
$password_err = "";
if (isset($_SESSION['$password_err'])) {
    $password_err = $_SESSION['$password_err'];
    unset($_SESSION['$password_err']); // Clear error from session
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Clinic - Patient panel</title>
    <link rel="stylesheet" href="../../public/css/patient_panel.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'shared_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card text-center" id="profile-section">
                    <h2>Hello <strong><?php echo htmlspecialchars($_SESSION["first_name"]); ?></strong>, this is your patient panel!</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            Here you can view your appointments, history of past appointments, and also update your personal information and password.
                        </div>
                        <div class="col-md-4">
                            <button onclick="toggleSection('new-appointment', true);" class="btn btn-primary m-1 w-100">Book a new appointment</button>
                        </div>
                    </div>
                </div>

                <!-- Table of appointments with sorting and filtering options -->
                <div class="card">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 id="appointmentsHeader"></h2>
                        </div>
                        <div class="col-md-4">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle w-100" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter appointments
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><a class="dropdown-item" href="#" onclick="loadAppointments('scheduled', false, 'scheduled:')">Scheduled</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="loadAppointments('cancelled_by_patient', false, 'cancelled by me:')">Cancelled by me</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="loadAppointments('cancelled_by_dentist', false, 'cancelled by dentist:')">Cancelled by dentist</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="loadAppointments('', false, 'all:')">All</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="appointments-table">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Date and Time <button class="btn btn-light btn-sm" onclick="sortAppointments('date')"><i class="bi bi-sort-down"></i></button></th>
                                    <th class="align-middle">Dentist <button class="btn btn-light btn-sm" onclick="sortAppointments('dentist')"><i class="bi bi-sort-alpha-down"></i></button></th>
                                    <th class="align-middle">Status</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic data generated using AJAX will appear here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Error message for data update -->
                        <?php if (!empty($update_err)) : ?>
                            <div class="alert alert-danger">
                                <?php echo $update_err; ?></div>
                        <?php endif; ?>

                        <!-- Error message for password change -->
                        <?php if (isset($_SESSION['password_err'])) : ?>
                            <div class="alert alert-danger">
                                <?php
                                echo $_SESSION['password_err'];
                                unset($_SESSION['password_err']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Success message -->
                        <?php if (isset($_SESSION['update_success'])) : ?>
                            <div class="alert alert-success">
                                <?php
                                echo $_SESSION['update_success'];
                                unset($_SESSION['update_success']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <!-- Personal information -->
                        <h2 class="card-title">Details:</h2>
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <p><strong>First Name:</strong> <?php echo htmlspecialchars($_SESSION["first_name"]); ?></p>
                                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($_SESSION["last_name"]); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["email"]); ?></p>
                            </div>
                            <div class="col-md-8 text-md-end">
                                <button onclick="toggleSection('edit-profile', true);" class="btn btn-info m-1">Edit profile</button>
                                <button onclick="toggleSection('change-password', true);" class="btn btn-warning m-1">Change password</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form for editing data -->
                <div class="card" id="edit-profile" style="display:none;">
                    <form action="../controllers/patient_update_controller.php" method="post" class="row g-3">
                        <div class="col-lg-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION["first_name"]); ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_SESSION["last_name"]); ?>" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_SESSION["email"]); ?>" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success m-1">Save changes</button>
                            <button type="button" onclick="toggleSection('edit-profile', false);" class="btn btn-secondary m-1">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Form for changing password -->
                <div class="card" id="change-password" style="display:none;">
                    <form action="../controllers/patient_change_password_controller.php" method="post">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="confirm_new_password">Confirm New Password</label>
                            <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Save new password" class="btn btn-success" />
                            <button type="button" onclick="toggleSection('change-password', false);" class="btn btn-secondary m-2">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Calendar for doctors' availability -->
                <div class="card" id="new-appointment" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <h2 class="text-center">Doctors' availability calendar:</h2>
                        </div>
                        <div class="col-md-4">
                            <button type="button" onclick="toggleSection('new-appointment', false);" class="btn btn-secondary m-2 w-100">Hide</button>
                        </div>
                    </div>
                    <br>
                    <div id="calendar" data-patient-id="<?php echo $_SESSION['user_id']; ?>"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts section -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script src='../../public/js/patient_panel.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
