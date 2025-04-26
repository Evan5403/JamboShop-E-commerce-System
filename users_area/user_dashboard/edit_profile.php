<!-- order details -->
<style media="screen">

.swal2-popup {
  width: 80%; /* Increase modal width */
}
</style>

<div class="details-myorder">
  <div class="recentOrders">
      <div class="cardHeader">
        <h2>Edit Profile</h2>
      </div>

      <form action="" method="post" id="edit_account" enctype="multipart/form-data">
        <p>
          <label for="profile_img">Profile Image</label><br>
          <input type="file" id="profile_img" name="profile_img">
        </p>
        <p>
          <label for="full_name">Full Name</label><br>
          <input type="text" name="full_name" value="<?php echo $full_name ?>" id="full_name" autocomplete="off" required>
        </p>
        <p>
          <label for="username">Username</label><br>
          <input type="text" name="username" value="<?php echo $user_name ?>" id="username" autocomplete="off" required>
        </p>
        <p>
          <label for="email">Email Address</label><br>
          <input type="email" name="email" value="<?php echo $user_email ?>" id="email" autocomplete="off" required>
        </p>
        <p>
          <label for="contact_number">Contact Phone Number <span></span></label> <br>
          <input
           type="tel"
           name="contact_number"
           value="<?php echo $user_mobile ?>"
           id="contact_number"
           pattern="[0-9]{10}"
           title="Please enter correct phone number"
           autocomplete="off"
           required>
        </p>
        <p>
          <label for="gender">Gender <span></span></label><br>
          <select name="gender" id="gender" required>
            <option value="<?php echo $gender ?>"><?php echo $gender ?></option>
            <?php
              echo ($gender == 'male') ? "<option value='female'>Female</option>" : "<option value='male'>Male</option>";
            ?>
          </select>
        </p>
        <p>
          <label for="d_o_b">Date of Birth</label><br>
          <input type="date" name="d_o_b" id="d_o_b" value="<?php echo  htmlspecialchars($date_of_birth, ENT_QUOTES, 'UTF-8') ?>" required>
        </p>

        <div class="primary-checkout" id="submitContainer">
         <input type="submit" class="primary-button" name="change_profile" value="Update Profile" id="change_profile">
        </div>
      </form>

  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

  if (isset($_POST['change_profile'])) {
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $d_o_b = $_POST['d_o_b'];
    $contact_number = $_POST['contact_number'];
    // var_dump($_FILES['profile_img']['name']);
    // die;

    $stmt = $conn->prepare("SELECT * FROM `user_table` WHERE (user_name = ? OR user_email = ? OR user_mobile = ?) AND user_id != ?");
    $stmt->bind_param("sssi", $username, $email, $contact_number, $user_id);
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
      $current_image = $row_user['user_image'];

      // Handle the uploaded image
      $profile_img = $_FILES['profile_img']['name'];
      $tmp_image = $_FILES['profile_img']['tmp_name'];

      // Check if a new image was uploaded
      if (!empty($profile_img)) {
          $file_ext = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
          if (in_array($_FILES['profile_img']['type'], $file_ext)) {
              move_uploaded_file($tmp_image, "../user_images/$profile_img");
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
      $update_profile = "UPDATE `user_table`
                         SET
                            full_name='$full_name',
                            user_name='$username',
                            user_email='$email',
                            gender='$gender',
                            date_of_birth='$d_o_b',
                            user_image='$profile_img',
                            user_mobile='$contact_number'
                         WHERE user_id='$user_id'";
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
              window.open('user_profile.php?edit_profile','_self')
            });
          </script>
          <?php
          $_SESSION['username'] = $username;
      }
    }

  }

 ?>
