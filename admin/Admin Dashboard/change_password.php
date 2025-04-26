<div class="header">
    <div class="left">
        <h1>Change Password</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
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
    <form action="" method="post" id="edit_account">
      <p>
        <label for="old_password">Old Password</label><br>
        <input type="password" id="old_password" placeholder="Enter Old Password" name="old_password">
      </p>
      <p>
        <label for="full_name">New Password</label><br>
        <input type="password" name="new_password" placeholder="Enter New Password" class="show_password" autocomplete="off" required>
      </p>
      <p>
        <label for="conf_new_password">Confirm New Password</label><br>
        <input type="password" name="conf_new_password" placeholder="Confirm New Password" class="show_password" autocomplete="off" required>
      </p>
      <p class="check">
        <input type="checkbox" id="showpasscode" onclick="togglePasswordVisibility()" class="showpassword">
        <label for="showpasscode">Show Password</label>
      </p>

      <div class="primary-checkout" id="submitContainer">
       <input type="submit" class="primary-button" name="change_password" value="Change Password" id="change_password">
      </div>
    </form>
  </div>

</div>

<script type="text/javascript">
  function togglePasswordVisibility(){
    // Select all elements with the class 'show_password'
   var passwordFields = document.querySelectorAll('.show_password');

   // Loop through each password field and toggle the type
   passwordFields.forEach(function(passwordField) {
       if (passwordField.type === "password") {
           passwordField.type = "text";
       } else {
           passwordField.type = "password";
       }
   });
  }

</script>
<?php
  if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['conf_new_password'];

    if (!password_verify($old_password, $password)) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Please Enter Your Correct Password!",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
    }elseif ($new_password !== $confirm_password) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Please Match Your Passwords!",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
    }else {
      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
      $change_password = "UPDATE `admin_table` SET password='$hashed_password' WHERE admin_id='$admin_id'";
      $result_update = mysqli_query($conn, $change_password);

      if ($result_update) {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              title: "Updated!",
              text: "Password Changed Successfully",
              showConfirmButton: false,
              timer: 2300
            }).then(() => {
              window.open('admin_profile.php?change_password','_self')
            });
          </script>
          <?php
      }
    }

  }
 ?>
