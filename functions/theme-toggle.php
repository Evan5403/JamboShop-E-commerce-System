<?php
session_start();

// Check if 'dark_mode' is sent via POST
if (isset($_POST['dark_mode'])) {
    if ($_POST['dark_mode'] === 'dark') {
        // Set the session to dark
        $_SESSION['dark_mode'] = 'dark';
    } else {
        // Destroy the dark_mode session variable
        unset($_SESSION['dark_mode']);
    }
    echo 'success';
} else {
    echo 'error';
}
