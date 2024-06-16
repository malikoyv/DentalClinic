<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="btn btn-light btn-lg" href="index.php">Dental Clinic</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) : ?>
                    <li class="nav-item dropdown">
                        <a class="btn btn-light dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Log in
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="patient_login.php">Patient</a>
                            <a class="dropdown-item" href="dentist_login.php">Staff</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light" href="patient_register.php">Sign up</a>
                    </li>
                    
                <?php else : ?>
                    <li class="nav-item dropdown">
                        <a class="btn btn-light dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Your account
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <?php
                            if (isset($_SESSION['role'])) {
                                switch ($_SESSION['role']) {
                                    case 'administrator':
                                        echo '<a class="dropdown-item" href="admin_panel.php">Administrator Panel</a>';
                                        break;
                                    case 'patient':
                                        echo '<a class="dropdown-item" href="patient_panel.php">Patient Panel</a>';
                                        break;
                                    case 'dentist':
                                        echo '<a class="dropdown-item" href="dentist_panel.php">Dentist Panel</a>';
                                        break;
                                }
                            }
                            ?>
                        </div> 
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="chat.php">Chat</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="index.php#our-doctors">Our Doctors</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="index.php#news">News</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="pricelist.php">Price List</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
            </ul>
            <span class="nav-item">
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                    $firstName = $_SESSION['first_name'] ?? 'Guest';
                    $role = $_SESSION['role'] ?? 'undefined role';
                    switch ($role) {
                        case 'administrator':
                            $translatedRole = 'administrator';
                            break;
                        case 'patient':
                            $translatedRole = 'patient';
                            break;
                        case 'dentist':
                            $translatedRole = 'dentist';
                            break;
                        default:
                            $translatedRole = 'unnamed role';
                    }
                    echo "Welcome <strong>" . htmlspecialchars($firstName) . "</strong>! You're logged in as <strong>" . htmlspecialchars($translatedRole) . "</strong>.";
                }
                ?>
            </span>
            <span class="nav-item">
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                    echo '<a class="btn btn-light" href="../controllers/logout_controller.php">Log out</a>';
                }
                ?>
            </span>
        </div>
    </div>
</nav>
