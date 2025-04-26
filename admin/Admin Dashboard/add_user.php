<?php
  if ($role !== 'admin') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
  }
 ?>

<div class="header">
    <div class="left">
        <h1>Add User</h1>
        <ul class="breadcrumb">
            <li><a href="admin_profile.php">
                    Dashboard
                </a></li>
            /
            <li><a href="admin_profile.php?manage_users">
                    User Mgt
                </a></li>
            /
            <li><a href="#" class="active">Add User</a></li>
        </ul>
    </div>
</div>
<div class="bottom-data">
  <div class="orders">
    <form id="edit_account" method="post" enctype="multipart/form-data">
      <p>
        <label for="profile_img">Profile Image</label><br>
        <input type="file" id="profile_img" name="profile_img" required>
      </p>
      <p>
        <label for="full_name">Full Name</label><br>
        <input type="text" name="full_name" id="full_name" autocomplete="off" required>
      </p>
      <p>
        <label for="d_o_b">Date of Birth</label><br>
        <input type="date" name="d_o_b" id="d_o_b" required>
      </p>
      <p>
        <label for="email">Email Address</label><br>
        <input type="email" name="email" id="email" autocomplete="off" required>
      </p>
      <p>
        <label for="contact_number">Contact Phone Number <span></span></label> <br>
        <input
         type="tel"
         name="contact_number"
         id="contact_number"
         pattern="[0-9]{10}"
         title="Please enter correct phone number"
         autocomplete="off"
         required>
      </p>
      <p>
        <label for="gender">Gender <span></span></label><br>
        <select name="gender" id="gender" required>
          <option value="">select gender</option>
          <option value="male">male</option>
          <option value="female">female</option>
        </select>
      </p>
      <p>
        <label for="role">User Role <span></span></label><br>
        <select name="user_role" id="role" required>
          <option value="">select user role</option>
          <option value="admin">admin</option>
          <option value="marketer">marketer</option>
          <option value="store_manager">store_manager</option>
        </select>
      </p>

      <div class="primary-checkout" id="submitContainer">
       <input type="submit" name="add_user" value="Update Profile" id="change_profile">
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

  if (isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $gender = $_POST['gender'];
    $user_role = $_POST['user_role'];
    $d_o_b = $_POST['d_o_b'];

    $stmt = $conn->prepare("SELECT * FROM `admin_table` WHERE email = ? OR contact_number = ?");
    $stmt->bind_param("ss", $email, $contact_number);
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
          text: "Email Or Phone Number Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
    }else {

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
      }

      function generatePassword($length = 12) {
        return bin2hex(random_bytes($length / 2));
      }
      $password = generatePassword();
      $hash_password = password_hash($password, PASSWORD_DEFAULT);

      // Update the product details in the database
      $add_admin = "INSERT INTO
                        `admin_table` (full_name,user_name,gender,d_o_b,email,contact_number,password,role,profile_image,status)
                      VALUES
                        ('$full_name','$password','$gender','$d_o_b','$email','$contact_number','$hash_password','$user_role','$profile_img','active')";
      $result_query = mysqli_query($conn, $add_admin);

      if ($result_query) {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              html: `
                <p>User Added Successfully</p>
                <p>Their password is: <b><?php echo $password ?></b></p>
                <p>Please inform the user to log in and change their password immediately.</p>
                `,
              confirmButtonText: 'OK',
            }).then(() => {
              window.open('admin_profile.php?add_user','_self')
            });
          </script>
          <?php
      }
    }

  }

 ?>
