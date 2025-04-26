<?php

if (isset($_GET['del_admin'])){
  $admin_id = intval($_GET['del_admin']); // Sanitize the input
  $query = "DELETE FROM admin_table WHERE admin_id = $admin_id";
  if (mysqli_query($conn, $query)) { ?>
     <script>
        Swal.fire({
          title: 'Deleted!',
          text: 'The user has been deleted.',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          window.open('admin_profile.php?manage_users');
        });
    </script>
    <?php
  } else { ?>
    <script>
      Swal.fire({
        title: 'Error!',
        text: 'Something went wrong. Please try again.',
        icon: 'error',
        confirmButtonText: 'OK'
      }).then(() => {
        window.open('admin_profile?manage_users.php');
      });
      </script>
    <?php
  }
}

 ?>
