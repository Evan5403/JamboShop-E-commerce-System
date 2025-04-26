<?php
  @session_start(); // @ means that the session only starts if this particular page is active
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if (isset($_SESSION['username'])) {
    echo "<script>window.open('../index.php','_self');</script>";
  }
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login Page</title>
    <link rel="stylesheet" href="style.css">
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
                  <h2>Welcome <br> Back!</h2>
                  <p>enjoy shopping with <br> Us!</p>
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
                    <input type="checkbox" id="passcode" onclick="togglePasswordVisibility()" class="showpassword">
                    <label for="passcode">Show Password</label>
                  </p>
                  <p class="forgot"><a href="#">Forgot Password</a></p>
                  <p><input type="submit" name="user_login" value="Login"></p>
                </form>
                <div class="afterform">
                  <p>Don't have an account?</p>
                  <a href="javascript:void(0);" class="t-signup">Register</a>
                </div>
              </div>
            </div>
          </div>

          <!-- REGISTRATION FORM -->
          <div class="signup">
            <div class="content-heading">
              <div class="y-style">
                <div class="logo"><a href="#"><span class="circle">.Jambo<span>Shop</span></a></div>
                <div class="welcome">
                  <h2>Sign Up <br> Now!</h2>
                  <p>To get eco-friendly fashion products <br> At affordable price</p>
                </div>
              </div>
            </div>
            <div class="content-form">
              <div class="y-style">
                <form action="" method="post" enctype="multipart/form-data">
                  <p>
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter Your Full Name" autocomplete="off" required>
                  </p>
                  <p>
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter Your Username" autocomplete="off" required>
                  </p>
                  <p>
                    <label>Date Of Birth</label>
                    <input type="date" id="start-date" name="d_o_b" value="start-date" min="1900-01-01" required>
                  </p>
                  <p>
                    <label>Phone Number</label>
                    <input
                     type="tel"
                     name="phone_number"
                     placeholder="Enter Your Phone Number"
                     pattern="[0-9]{10}"
                     title="Please enter correct phone number"
                     autocomplete="off"
                     required>
                  </p>
                  <p>
                    <label id="email">E-mail</label>
                    <input type="email" name="email" placeholder="Enter Your E-mail(Optional)" autocomplete="off" id="email">
                  </p>
                  <p>
                    <label id="user_image">Profile Image</label>
                    <input type="file" id="user_image" name="user_image" required>
                  </p>
                  <p>
                    <label for="gender">Select Gender</label><br>
                    <select name="gender" id="gender" required>
                      <option value="">Select Gender</option>
                      <option value="male">Male</option>
                      <option value="female">Female</option>
                    </select>
                  </p>
                  <p>
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter Password" class="show_password" required>
                  </p>
                  <p>
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="show_password" required>
                  </p>
                  <p class="check">
                    <input type="checkbox" id="showpasscode" onclick="togglePasswordVisibility()" class="showpassword">
                    <label for="showpasscode">Show Password</label>
                  </p>
                  <p><input type="submit" value="Sign Up" name="sign_up"></p>
                </form>
                <!-- <div class="social">
                  <p><span>Or sign up with</span></p>
                  <ul>
                    <li><a href="#" class="google"><ion-icon name="logo-google"></ion-icon></a></li>
                    <li><a href="#" class="facebook"><ion-icon name="logo-facebook"></ion-icon></a></li>
                    <li><a href="#" class="twitter"><ion-icon name="logo-twitter"></ion-icon></a></li>
                  </ul>
                </div> -->
                <div class="afterform">
                  <p>Already have an account?</p>
                  <a href="javascript:void(0);" class="t-login">Login here</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="../script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script>
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
     document.getElementById('start-date').setAttribute('max', eighteenYearsAgoDate);

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
    SIGN UP
  ---------------*/
  if (isset($_POST['sign_up'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $d_o_b = $_POST['d_o_b'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $confirm_password = $_POST['confirm_password'];
    $user_ip = getIPAddress();
    // accessing img
    $user_image = $_FILES['user_image']['name'];
    $temp_image = $_FILES['user_image']['tmp_name'];
    // check if username exists
    $select_query = "SELECT * FROM `user_table` WHERE user_name='$username' OR user_email='$email' OR user_mobile='$phone_number'";
    $result = mysqli_query($conn,$select_query);
    $rows_count = mysqli_num_rows($result);
    // validate an image
    $file_ext = strtolower(pathinfo($user_image, PATHINFO_EXTENSION));
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
      die;
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Please insert a valid email address",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }elseif (empty($user_image) || !in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Please insert a valid image",
          showConfirmButton: false,
          timer: 7000
        });
      </script>
      <?php
      die;
    }elseif ($password != $confirm_password) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Oops...",
          text: "Password do not match!",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }else {
      // sign-up user
      move_uploaded_file($temp_image,"./user_images/$user_image");
      $insert_user = "INSERT INTO
                        `user_table` (full_name,user_name,user_email,gender,date_of_birth,user_password,user_image,user_mobile,created_at,status)
                      VALUES
                        ('$fullname','$username','$email','$gender','$d_o_b','$hash_password','$user_image','$phone_number',NOW(),'active')";
      $sql_execute = mysqli_query($conn,$insert_user);
      if ($sql_execute) {
        $_SESSION['username'] = $username;
        // selecting cart items
        $select_cart_items = "SELECT * FROM `cart_details` WHERE ip_address='$user_ip'";
        $result_cart = mysqli_query($conn,$select_cart_items);
        $rows_count = mysqli_num_rows($result_cart);

        if ($rows_count > 0) {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              title: "Hello..",
              text: "You have items in your cart",
              showConfirmButton: false,
              timer: 2500
            }).then(() => {
              window.open('checkout.php','_self')
            });
          </script>
          <?php
          exit();
        }else {
          ?>
          <script>
            Swal.fire({
              position: "top",
              icon: "success",
              text: "Successfully registered the user",
              showConfirmButton: false,
              timer: 2500
            }).then(() => {
              window.open('../index.php','_self')
            });
          </script>
          <?php
          exit();
        }
      }else {
        die(mysqli_error($conn));
      }
    }
  }

  /* --------
    SIGN IN
  ------------- */
  if (isset($_POST['user_login'])) {
    $user_ip = getIPAddress();
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM `user_table` WHERE user_name = ? OR user_email = ? OR user_mobile = ?");
    $stmt->bind_param("sss", $username, $username, $username); // "sss" indicates three string parameters
    $stmt->execute();
    $result = $stmt->get_result();
    $row_data = $result->fetch_assoc();
    $user_status = $row_data['status'];

    // direct to cart items
    $select_query_cart = "SELECT * FROM `cart_details` WHERE ip_address='$user_ip'";
    $select_cart = mysqli_query($conn,$select_query_cart);
    $row_count_cart = mysqli_num_rows($select_cart); // $row_count > 0
    if ($user_status == 'blocked') {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "oops",
          html: "Your Account Has Been Blocked <br> Please contact our customer service",
          showConfirmButton: true
        })
      </script>
      <?php
    }elseif ($result->num_rows > 0 AND $user_status == 'active') {
        if (password_verify($password,$row_data['user_password'])) {
          if ($result->num_rows == 1 and $row_count_cart == 0) {
            $select_query = "SELECT * FROM `user_table` WHERE user_name='$username' OR user_email='$username' OR user_mobile='$username'";
            $result = mysqli_query($conn, $select_query);
            $row_data = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row_data['user_name'];
            $row_data = $result->fetch_assoc();
            // $_SESSION['username'] = $row_data['user_name'];
            // var_dump($_SESSION['username']);
            // die;
            ?>
            <script>
              Swal.fire({
                position: "top",
                icon: "success",
                title: "Welcome!",
                text: "Enjoy shopping",
                showConfirmButton: false,
                timer: 2300
              }).then(() => {
                window.open('../index.php','_self');
              });
            </script>
            <?php
          }elseif ($row_count_cart > 0) {
            $select_query = "SELECT * FROM `user_table` WHERE user_name='$username' OR user_email='$username' OR user_mobile='$username'";
            $result = mysqli_query($conn, $select_query);
            $row_data = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row_data['user_name'];
            ?>
            <script>
              Swal.fire({
                position: "top",
                icon: "success",
                title: "Hello..",
                text: "You have items in your cart",
                showConfirmButton: false,
                timer: 2500
              }).then(() => {
                window.open('checkout.php','_self')
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
