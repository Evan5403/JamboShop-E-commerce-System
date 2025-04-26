

<?php


  session_start();
  include('../../includes/connect.php');

  // Ensure the session is still active before accessing session variables
  if (isset($_SESSION['admin'])) {
    $admin = $_SESSION['admin'];

    // Fetch admin details
    $select_details = "SELECT * FROM admin_table WHERE user_name='$admin'";
    $exe_query = mysqli_query($conn, $select_details);

    if ($exe_query && mysqli_num_rows($exe_query) > 0) {
        $row_admin = mysqli_fetch_assoc($exe_query);
        $admin_id = $row_admin['admin_id'];

        // Update last login time
        $update_last_seen = "UPDATE `admin_table`
                             SET last_login=NOW()
                             WHERE admin_id='$admin_id'";
        $exe_update = mysqli_query($conn, $update_last_seen);

        if ($exe_update) {
            // Destroy the session after updating last login
            unset($_SESSION['admin']);

            // Redirect to the login page
            echo "<script>window.open('../admin_login.php','_self');</script>";
        } else {
            // Handle potential errors in updating last login
            echo "Error updating last login time.";
        }
    } else {
        // Handle the case where admin details are not found
        echo "Admin details not found.";
    }
  } else {
    // Handle cases where session is already destroyed or not set
    echo "<script>alert('Session expired. Please log in again.');</script>";
    echo "<script>window.open('../admin_login.php','_self');</script>";
  }

 ?>
