<?php
session_start(); // Start session

// Checking if the user has administrative permissions
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    header("location: dentist_login.php");
    exit;
}

// Error message for data update
$update_err = "";
if (isset($_SESSION['update_err'])) {
    $update_err = $_SESSION['update_err'];
    unset($_SESSION['update_err']); // Clearing the error message from session
}

// Fetching dentist data by ID
if (isset($_GET['dentist_id'])) {
    $dentist_id = $_GET['dentist_id'];

    // Including database configuration and dentist model files
    require_once '../../config/database.php';
    require_once '../models/dentist.php';

    // Initializing database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Initializing dentist object
    $dentist = new Dentist($db);

    // Fetching dentist data by ID
    $dentist_data = $dentist->getDentistById($dentist_id);

    // Checking if dentist with provided ID exists
    if ($dentist_data === false) {
        exit('Dentist with provided ID was not found.');
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/admin_panel.css">
    <title>Edit Dentist Data</title>
</head>

<body>
    <div class="container">
        <?php include 'shared_navbar.php'; ?>

        <!-- Dentist data edit form -->
        <div class="card mt-4 col-md-6" id="add-dentist">
            <div class="card-header">
                Dentist Data Edit Form
                <!-- Error message for data edit -->
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
            </div>
            <div class="card-body">
                <form action="../controllers/dentist_update_controller.php" method="post">
                    <input type="hidden" name="dentist_id" value="<?php echo htmlspecialchars($dentist_id); ?>">
                    <!-- Personal data -->
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required value="<?php echo htmlspecialchars($dentist_data['first_name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required value="<?php echo htmlspecialchars($dentist_data['last_name']); ?>">
                    </div>
                    <!-- Contact and login data -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($dentist_data['email']); ?>">
                    </div>
                    <!-- Specialization -->
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo htmlspecialchars($dentist_data['specialization']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary m-1">Confirm</button>
                    <a href="admin_panel.php" class="btn btn-secondary m-1">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
