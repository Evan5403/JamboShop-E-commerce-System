<?php
  if ($role !== 'admin') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
    exit();
  }

  if (isset($_GET['edit_admin'])) {
    $adminId = $_GET['edit_admin'];
  }
  $get_admin = "SELECT * FROM admin_table WHERE admin_id = '$adminId'";
  $exe_query = mysqli_query($conn, $get_admin);
  $row_admin = mysqli_fetch_assoc($exe_query);
  $admin_username = $row_admin['user_name'];
  $status = $row_admin['status'];
 ?>
<div class="header">
    <div class="left">
        <h1>Edit User</h1>
        <ul class="breadcrumb">
            <li><a href="admin_profile.php">
                    Dashboard
                </a></li>
            /
            <li><a href="admin_profile.php?manage_users">
                    User Mgt
                </a></li>
            /
            <li><a href="#" class="active"><?php echo '@'.$row_admin['user_name'] ?></a></li>
        </ul>
    </div>
</div>
<div class="bottom-data">
  <div class="orders">
    <form id="edit_account" method="post" enctype="multipart/form-data">
      <p>
        <label for="profile_img">Profile Image</label><br>
        <input type="file" id="profile_img" name="profile_img">
      </p>
      <p>
        <label for="full_name">Full Name</label><br>
        <input type="text" name="full_name" id="full_name" value="<?php echo $row_admin['full_name'] ?>" autocomplete="off" required>
      </p>
      <p>
        <label for="d_o_b">Date of Birth</label><br>
        <input type="date" name="d_o_b" value="<?php echo  htmlspecialchars($row_admin['d_o_b'], ENT_QUOTES, 'UTF-8') ?>" id="d_o_b" required>
      </p>
      <p>
        <label for="email">Email Address</label><br>
        <input type="email" name="email" value="<?php echo $row_admin['email'] ?>" id="email" autocomplete="off" required>
      </p>
      <p>
        <label for="contact_number">Contact Phone Number <span></span></label> <br>
        <input
         type="tel"
         name="contact_number"
         id="contact_number"
         pattern="[0-9]{10}"
         title="Please enter correct phone number"
         value="<?php echo $row_admin['contact_number'] ?>"
         autocomplete="off"
         required>
      </p>
      <p>
        <label for="gender">Gender <span></span></label><br>
        <select name="gender" id="gender" required>
          <option value="<?php echo $row_admin['gender'] ?>"><?php echo $row_admin['gender'] ?></option>
          <?php
            echo ($row_admin['gender'] == 'male') ? "<option value='female'>Female</option>" : "<option value='male'>Male</option>";
          ?>
        </select>
      </p>
      <p>
        <label for="role">User Role <span></span></label><br>
        <select name="user_role" id="role" required>
          <option value="<?php echo $row_admin['role'] ?>"><?php echo $row_admin['role'] ?></option>
          <?php
            switch ($row_admin['role']) {
              case 'marketer':
                echo "<option value='store_manager'>store manager</option>";
                break;
              default:
                echo "<option value='marketer'>marketer</option>";
                break;
            }
          ?>
        </select>
      </p>
      <p>
        <label for="status">Status <span></span></label><br>
        <select name="status" id="status" required>
          <option value="<?php echo $row_admin['status'] ?>"><?php echo $row_admin['status'] ?></option>
          <?php
            echo ($row_admin['status'] == 'active') ? "<option value='blocked'>block</option>" : "<option value='active'>unblock</option>";
          ?>
        </select>
      </p>

      <div class="primary-checkout" id="submitContainer">
       <input type="submit" name="edit_admin" value="Update Profile" id="change_profile">
      </div>
    </form>
  </div>

</div>
<script type="text/javascript">
  // Get the current date
  let today = new Date();

  // Calculate the date 18 years ago from today
  let eighteenYearsAgo = new Date(today);
  eighteenYearsAgo.setFullYear(today.getFullYear() - 18);

  // Format the date to YYYY-MM-DD format
  let year = eighteenYearsAgo.getFullYear();
  let month = (eighteenYearsAgo.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
  let day = eighteenYearsAgo.getDate().toString().padStart(2, '0'); // Ensure two digits for the day

  // Combine them into a string in YYYY-MM-DD format
  let eighteenYearsAgoDate = `${year}-${month}-${day}`;

  // Set the max attribute of the date input to the date when the user turns 18
  document.getElementById('d_o_b').setAttribute('max', eighteenYearsAgoDate);
</script>

<?php

  if (isset($_POST['edit_admin'])) {
    $full_name = $_POST['full_name'];
    $d_o_b = $_POST['d_o_b'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $user_role = $_POST['user_role'];
    $form_status = $_POST['status'];

    $deactivated = '';
    if ($status == 'blocked' AND $form_status == 'active') {
      $deactivated = 'yes';
    }elseif ($status == 'active' && $form_status == 'blocked') {
      $deactivated = 'no';
    }

    $stmt = $conn->prepare("SELECT * FROM `admin_table` WHERE (email = ? OR contact_number = ?) AND admin_id != ?");
    $stmt->bind_param("ssi", $email, $contact_number, $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows_count = $result->num_rows;
    if ($rows_count > 0) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Error...",
          text: "Email Or Phone Number Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      // die;
    }else {
      $current_image = $row_admin['profile_image'];

      // Handle the uploaded image
      $profile_img = $_FILES['profile_img']['name'];
      $tmp_image = $_FILES['profile_img']['tmp_name'];

      // Check if a new image was uploaded
      if (!empty($profile_img)) {
          $file_ext = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/avif'];
          if (in_array($_FILES['profile_img']['type'], $file_ext)) {
              move_uploaded_file($tmp_image, "admin_imgs/$profile_img");
          } else {
            ?>
            <script>
              Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Please insert a valid image",
              });
            </script>
            <?php
            exit; // Stop further execution if the image is invalid
          }
      } else {
          $profile_img = $current_image; // Retain the current image
      }

      // Update the product details in the database
      $update_profile = "UPDATE `admin_table`
                         SET
                            full_name='$full_name',
                            gender='$gender',
                            d_o_b='$d_o_b',
                            email='$email',
                            contact_number='$contact_number',
                            role='$user_role',
                            updated_at='NOW()',
                            profile_image='$profile_img',
                            status='$form_status'
                         WHERE admin_id='$adminId'";
      $result_update = mysqli_query($conn, $update_profile);

      if ($result_update) {
        if ($deactivated == 'yes') {
          $action = "Activated Admin";
          $action_effect = "positive";
          $details = "Admin Name: $full_name"; // Custom details
          logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        }elseif ($deactivated == 'no') {
          $action = "Deactivated Admin";
          $action_effect = "negative";
          $details = "Admin Name: $full_name"; // Custom details
          logAdminAction($conn, $admin_id, $action, $action_effect, $details);
        }
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              title: "Updated!",
              text: "Profile Updated Successfully",
              showConfirmButton: false,
              timer: 2300
            }).then(() => {
              window.open('admin_profile.php?edit_admin=<?php echo $adminId ?>','_self')
            });
          </script>
          <?php
      }
    }

  }

?>
