<?php
  session_start();
  include('../../includes/connect.php');
  include('../../functions/common_functions.php');

  if (!isset($_SESSION['username'])) {
    echo "<script>window.open('../user_login.php','_self');</script>";
    exit();
  }
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM `user_table` WHERE user_name='$username'";
  $result = mysqli_query($conn, $sql);
  $row_user = mysqli_fetch_array($result);
  $user_id = $row_user['user_id'];
  $user_image = $row_user['user_image'];
  $full_name = $row_user['full_name'];
  $user_name = $row_user['user_name'];
  $user_email = $row_user['user_email'];
  $gender = $row_user['gender'];
  $date_of_birth = $row_user['date_of_birth'];
  $user_password = $row_user['user_password'];
  $user_mobile = $row_user['user_mobile'];
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- remix icons -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css"> -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<style media="screen">
  .imgBx2{
    position: relative;
    width: 80px;
    height: 85px;
    border-radius: 50px;
    overflow: hidden;
  }
  .imgBx2 img{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  #wishlist-container .true{
    background-color: #8de02c;
    color: #fff;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
  }
  #wishlist-container .false{
    background-color: #8de02c;
    color: #f00;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
  }
  #wishlist-container .wishlist-details-btn{
    position: relative;
    padding: 5px 10px;
    background: #f00;
    text-decoration: none;
    color: var(--white);
    border-radius: 6px;
  }
  .status[data-tooltip]:hover::after {
    content: attr(data-tooltip);
    position: fixed;
    top: 10px; /* Position above the span element */
    left: 50%; /* Center the tooltip horizontally */
    transform: translateX(-50%);
    background-color: #333;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    white-space: nowrap;
    z-index: 10;
    font-size: 12px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }

  .status[data-tooltip]:hover::before {
    content: '';
    position: fixed;
    top: 35px; /* Adjust the arrow position */
    left: 50%; /* Center the arrow horizontally */
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-bottom-color: #333; /* Tooltip background color */
    z-index: 10;
  }
  @media (max-width: 768px) {
    .modal-table{
      min-width: 953px;
    }
  }

  /* Responsive adjustments for mobile devices */
  @media (max-width: 480px) {
    .modal-table{
      min-width: 953px;
    }

  }

</style>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="at-outline"></ion-icon>
                        </span>
                        <span class="title"><?php echo $_SESSION['username'] ?></span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Account Overview</span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php?my_orders">
                        <span class="icon">
                            <ion-icon name="briefcase-outline"></ion-icon>
                        </span>
                        <span class="title">My Orders</span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php?analytics">
                        <span class="icon">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                        </span>
                        <span class="title">Analytics</span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php?my_wishlist">
                        <span class="icon">
                            <ion-icon name="heart-outline"></ion-icon>
                        </span>
                        <span class="title">Wishlist</span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php?edit_profile">
                        <span class="icon">
                            <ion-icon name="create-outline"></ion-icon>
                        </span>
                        <span class="title">Edit Account</span>
                    </a>
                </li>

                <li>
                    <a href="user_profile.php?change_password">
                        <span class="icon">
                            <ion-icon name="lock-closed-outline"></ion-icon>
                        </span>
                        <span class="title">Change Password</span>
                    </a>
                </li>

                <li>
                    <a href="../../index.php">
                        <span class="icon">
                            <ion-icon name="cart-outline"></ion-icon>
                        </span>
                        <span class="title">Go Shopping</span>
                    </a>
                </li>

                <li>
                    <a href="../user_logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>

                <!-- <div class="">
                  <input type="checkbox" id="theme-toggle" hidden>
                  <label for="theme-toggle" class="theme-toggle"></label>
                </div> -->

                <div class="user">
                  <img src="../user_images/<?php echo $user_image ?>" alt="">
                </div>
            </div>

            <?php
              if (!isset($_GET['edit_account']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account']) && !isset($_GET['edit_profile']) && !isset($_GET['change_password'])  && !isset($_GET['my_wishlist']) && !isset($_GET['analytics'])) {
                include('account_overview.php');
              }
              if (isset($_GET['my_orders'])) {
                include('my_orders.php');
              }
              if (isset($_GET['edit_profile'])) {
                include('edit_profile.php');
              }
              if (isset($_GET['change_password'])) {
                include('change_password.php');
              }
              if (isset($_GET['my_wishlist'])) {
                include('my_wishlist.php');
              }
              if (isset($_GET['analytics'])) {
                include('analytics.php');
              }
             ?>

        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>
