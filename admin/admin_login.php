<?php
  @session_start(); // @ means that the session only starts if this particular page is active
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if (isset($_SESSION['admin'])) {
    echo "<script>window.open('Admin Dashboard/admin_profile.php','_self');</script>";
  }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="../users_area/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body id="user-login-signup">
    <div id="page" class="sitetwo login-show">
      <div class="container">
        <div class="wrapper">
          <!-- LOGIN FORM -->
          <div class="login">
            <div class="content-heading">
              <div class="y-style">
                <div class="logo"><a href="#"><span class="circle">.Jambo<span>Shop</span></a></div>
                <div class="welcome">
                  <h2>Admin Portal</h2>
                  <p>Manage the system with <br> ease!</p>
                </div>
              </div>
            </div>
            <div class="content-form">
              <div class="y-style">
                <form action="" method="post">
                  <p>
                    <label>Username/Email/Phone Number</label>
                    <input type="text" name="user_name" placeholder="Enter Username/Email/Phone Number" autocomplete="off" required>
                  </p>
                  <p>
                    <label>Password</label>
                    <input type="password" name="password" class="show_password" placeholder="Enter Password" required>
                  </p>
                  <p class="check">
                    <input type="checkbox" id="passwordvisibility" onclick="togglePasswordVisibility()" class="showpassword">
                    <label for="passwordvisibility">Show Password</label>
                  </p>
                  <p class="forgot"><a href="#">Forgot Password</a></p>
                  <p><input type="submit" name="admin_login" value="Login"></p>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- <script src="../script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script> -->
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
  </body>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</html>
<?php

  /* --------
    SIGN IN
  ------------- */
  if (isset($_POST['admin_login'])) {
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `admin_table` WHERE user_name = ? OR email = ? OR contact_number = ?");
    $stmt->bind_param("sss", $username, $username, $username); // "sss" indicates three string parameters
    $stmt->execute();
    $result = $stmt->get_result();
    $row_data = $result->fetch_assoc();

    if ($result->num_rows > 0) {
      if (password_verify($password, $row_data['password'])) {
        if ($row_data['status'] == 'blocked') {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "error",
              html: "Your Account is Blocked <br> Please reach out to the admin",
              showConfirmButton: false,
              timer: 2000
            })
          </script>
          <?php
          exit();
        }
        $select_query = "SELECT * FROM `admin_table` WHERE user_name='$username' OR email='$username' OR contact_number='$username'";
        $result = mysqli_query($conn, $select_query);
        $row_data = mysqli_fetch_assoc($result);
        $_SESSION['admin'] = $row_data['user_name'];

        if ($row_data['role'] == 'admin') {
          ?>
          <script>
          Swal.fire({
            position: "top",
            icon: "success",
            html: "<span>Welcome! <b><?php echo '@' . $row_data['user_name']; ?></b></span>",
            showConfirmButton: false,
            timer: 2300
          }).then(() => {
              window.open('Admin Dashboard/admin_profile.php','_self');
            });
          </script>
          <?php
        }elseif ($row_data['role'] == 'marketer') {
          ?>
          <script>
          Swal.fire({
            position: "top",
            icon: "success",
            html: "<span>Welcome! <b><?php echo '@' . $row_data['user_name']; ?></b></span>",
            showConfirmButton: false,
            timer: 2300
          }).then(() => {
              window.open('Admin Dashboard/admin_profile.php?analytics','_self');
            });
          </script>
          <?php
        }else {
          ?>
          <script>
          Swal.fire({
            position: "top",
            icon: "success",
            html: "<span>Welcome! <b><?php echo '@' . $row_data['user_name']; ?></b></span>",
            showConfirmButton: false,
            timer: 2300
          }).then(() => {
              window.open('Admin Dashboard/admin_profile.php?store_manager','_self');
            });
          </script>
          <?php
        }
      }else {
        ?>
        <script>
          Swal.fire({
            position: "top",
            icon: "error",
            title: "oops",
            text: "Invalid password",
            showConfirmButton: false,
            timer: 2000
          })
        </script>
        <?php
      }
    }else {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "oops",
          text: "User Credentials Doesn't Exist!",
          showConfirmButton: false,
          timer: 2000
        })
      </script>
      <?php
    }
  }
 ?>
