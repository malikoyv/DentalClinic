<?php

session_start(); // Start session

// Checking if the user has administrative permissions
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'administrator') {
    // Redirect to the login page if the user is not logged in or lacks administrator permissions
    header("location: dentist_login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" integrity="sha256-ZCK10swXv9CN059AmZf9UzWpJS34XvilDMJ79K+WOgc=" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/admin_panel.css">
</head>

<body>
    <div class="container mt-4">
        <?php include 'shared_navbar.php'; ?>
        
        <h1 class="text-center">Admin Panel</h1>
        
        <button onclick="toggleSection('add-dentist', true);" class="btn btn-primary m-1">Add New Dentist</button>
        
        <!-- Form to add a new dentist -->
        <div class="card mt-4 col-md-6" id="add-dentist" style="display:none;">
            <div class="card-header">
                Dentist Addition Form
            </div>
            <div class="card-body">
                <form action="../controllers/dentist_add_controller.php" method="post">
                    <!-- Personal information -->
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <!-- Contact and login information -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <!-- Specialization -->
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Dentist</button>
                    <button type="button" onclick="toggleSection('add-dentist', false);" class="btn btn-secondary m-1">Cancel</button>
                </form>
            </div>
        </div>
        
        <!-- Dentist List -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Dentist List</h3>
                <?php
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                        // Removing the message after displaying it
                        unset($_SESSION['error_message']);
                    }
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                        // Removing the message after displaying it
                        unset($_SESSION['success_message']);
                    }
                    ?>
                    <?php if (isset($_SESSION['update_success'])) : ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['update_success']; ?>
                            <?php unset($_SESSION['update_success']); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['update_err'])) : ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['update_err']; ?>
                                <?php unset($_SESSION['update_err']); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <!-- Table displaying dentists -->
                            <?php
                include 'shared_dentist_list.php';
                ?>
            </div>
        </div>
    </div>
    
    
    <script>
        // Function to toggle sections
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
