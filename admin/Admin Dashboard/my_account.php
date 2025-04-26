<div class="header">
    <div class="left">
        <h1>My Account</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                      Dashboard
                  </a></li>
              /
          <?php } else { ?>
                  <li><a href="admin_profile.php?store_manager">
                        Dashboard
                      </a></li>
                  /
          <?php } ?>
          <li><a href="#" class="active">@<?php echo $_SESSION['admin'] ?></a></li>
        </ul>
    </div>
</div>
<div class="bottom-data">
  <div class="orders">
    <form id="edit_account" method="post" enctype="multipart/form-data">
      <?php
        if ($role == 'admin') { ?>
         <p>
           <label for="profile_img">Profile Image</label><br>
           <input type="file" id="profile_img" name="profile_img">
         </p>
         <p>
           <label for="full_name">Full Name</label><br>
           <input type="text" name="full_name" value="<?php echo $row_admin['full_name'] ?>" id="full_name" autocomplete="off" required>
         </p>
        <p>
          <label for="username">Username</label><br>
          <input type="text" name="username" value="<?php echo $row_admin['user_name'] ?>" id="username" autocomplete="off" required>
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
           value="<?php echo $row_admin['contact_number'] ?>"
           id="contact_number"
           pattern="[0-9]{10}"
           title="Please enter correct phone number"
           autocomplete="off"
           required>
        </p>
       <?php } else { ?>
         <p style="display: none">
           <label for="profile_img">Profile Image</label><br>
           <input type="file" id="profile_img" name="profile_img">
         </p>
         <p>
           <label for="full_name">Full Name</label><br>
           <input type="text" name="full_name" value="<?php echo $row_admin['full_name'] ?>" id="full_name" autocomplete="off" readonly>
         </p>
        <p>
          <label for="username">Username</label><br>
          <input type="text" name="username" value="<?php echo $row_admin['user_name'] ?>" id="username" autocomplete="off" required>
        </p>
        <p>
          <label for="email">Email Address</label><br>
          <input type="email" name="email" value="<?php echo $row_admin['email'] ?>" id="email" autocomplete="off" readonly>
        </p>
        <p>
          <label for="contact_number">Contact Phone Number <span></span></label> <br>
          <input
           type="tel"
           name="contact_number"
           value="<?php echo $row_admin['contact_number'] ?>"
           id="contact_number"
           pattern="[0-9]{10}"
           title="Please enter correct phone number"
           autocomplete="off"
           required>
        </p>
       <?php } ?>

      <div class="primary-checkout" id="submitContainer">
       <input type="submit" name="change_profile" value="Update Profile" id="change_profile">
      </div>
    </form>
  </div>

</div>

<?php

  if (isset($_POST['change_profile'])) {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];

    $stmt = $conn->prepare("SELECT * FROM `admin_table` WHERE (user_name = ? OR email = ? OR contact_number = ?) AND admin_id != ?");
    $stmt->bind_param("sssi", $username, $email, $contact_number, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows_count = $result->num_rows;
    if ($rows_count > 0) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Username Or Email Or Phone Number Already Exists",
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
                            user_name='$username',
                            email='$email',
                            profile_image='$profile_img',
                            contact_number='$contact_number'
                         WHERE admin_id='$admin_id'";
      $result_update = mysqli_query($conn, $update_profile);

      if ($result_update) {
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
              window.open('admin_profile.php?my_account','_self')
            });
          </script>
          <?php
          $_SESSION['admin'] = $username;
      }
    }

  }

 ?>
