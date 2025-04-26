<?php
  session_start();

  include('../../includes/connect.php');
  include('../../functions/common_functions.php');

  // FUNCTION to FORMAT DATE
  function formatDateTime($date) {
    $dateTime = new DateTime($date);
    return $dateTime->format('d-M-y H:i:s');
  }

  if (!isset($_SESSION['admin'])) {
    echo "<script>window.open('../admin_login.php','_self');</script>";
  } else {
    $admin = $_SESSION['admin'];
    $select_details = "SELECT * FROM admin_table WHERE user_name='$admin'";
    $exe_query = mysqli_query($conn, $select_details);
    $row_admin = mysqli_fetch_assoc($exe_query);
    $admin_id = $row_admin['admin_id'];
    $role = $row_admin['role'];
    $password = $row_admin['password'];
  }

  // GET NOTIFICATIONS
  // Delayed Orders Query
  $delayed_orders_query = "
                            SELECT
                                uo.order_id,
                                uo.user_id,
                                uo.invoice_number,
                                uo.order_date,
                                uo.expected_date,
                                uo.order_status
                            FROM
                                user_orders uo
                            WHERE
                                uo.expected_date <= NOW()
                                AND uo.order_status != 'delivered'
                                AND uo.order_status != 'complete'
                                AND uo.order_status != 'cancelled'
                        ";
  $delayed_orders_result = mysqli_query($conn, $delayed_orders_query);
  $notification_rows = mysqli_num_rows($delayed_orders_result);

  // Low/Out-of-Stock Products Query
  $low_stock_query = "
      SELECT
          product_id,
          product_title,
          instock
      FROM
          products
      WHERE
          instock <= 5
  ";

  $low_stock_result = mysqli_query($conn, $low_stock_query);
  $low_stock_rows = mysqli_num_rows($low_stock_result);

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Adm Dashboard</title>
</head>
<style media="screen">
  .myChart, .myChart2 {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Space between canvases */
    justify-content: center; /* Center canvases */
    /* height: 400px; */
    padding: 10px; /* Optional padding for inner spacing */
    border: 1px solid #ddd; /* Optional: for better visibility */
    overflow: hidden; /* Ensures no content overflows outside */
  }
  .myChart{height: 300px;}
  .myChart2{height: 600px;}

  .chart-wrapper {
    flex: 1 1 calc(33.333% - 20px); /* Responsive sizing */
    max-width: calc(33.333% - 20px); /* Prevents overflow */
    display: flex;
    justify-content: center;
  }

  canvas {
    width: 100%; /* Responsive canvas size */
    height: auto !important; /* Maintain aspect ratio */
  }
  /* Responsive adjustments for tablets */
  @media (max-width: 960px) {
    .chart-wrapper {
      flex: 1 1 calc(50% - 20px); /* 2 canvases per row on tablets */
      max-width: calc(50% - 20px);
    }
  }

  @media (max-width: 768px) {
    .chart-wrapper {
      flex: 1 1 calc(50% - 20px); /* 2 canvases per row on tablets */
      max-width: calc(50% - 20px);
    }
    .swal2-modal table{
      min-width: 734px;
    }
  }

  /* Responsive adjustments for mobile devices */
  @media (max-width: 480px) {
    .chart-wrapper {
      flex: 1 1 100%; /* 1 canvas per row on smaller screens */
      max-width: 100%;
    }
    .swal2-modal table{
      min-width: 734px;
    }
  }

</style>

<body class="<?= isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] === 'dark' ? 'dark' : '' ?>">

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#" class="logo">
            <!-- <i class='bx bx-code-alt'></i> -->
            <div class="logo-name"><span>Jambo</span>Shop</div>
        </a>
        <ul class="side-menu">
          <?php
            if ($role == 'store_manager') { ?>
              <li><a href="admin_profile.php?store_manager"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
              <li><a href="../index.php"><i class='bx bx-store-alt'></i>Manage Shop</a></li>
              <li><a href="admin_profile.php?reviews_ratings"><i class='bx bxs-star-half'></i> Reviews & Ratings</a></li>
              <li>
                <a href="admin_profile.php?order_feedbacks"><i class='bx bx-message-square-dots'></i>Order Feedbacks</a>
              </li>
              <li><a href="admin_profile.php?my_account"><i class='bx bxs-user-detail'></i>My Account</a></li>
              <li><a href="admin_profile.php?manage_users"><i class='bx bx-group'></i>View Users</a></li>
              <li><a href="admin_profile.php?manage_customers"><i class='bx bx-group'></i>Customer MGT</a></li>
              <li><a href="admin_profile.php?adm_logs"><i class='bx bx-receipt'></i></i>Admin Logs</a></li>
          <?php }elseif ($role == 'marketer') { ?>
              <li><a href="admin_profile.php?analytics"><i class='bx bx-analyse'></i>Analytics</a></li>
              <li><a href="../index.php"><i class='bx bx-store-alt'></i>Manage Shop</a></li>
              <li><a href="admin_profile.php?reviews_ratings"><i class='bx bxs-star-half'></i> Reviews & Ratings</a></li>
              <li><a href="admin_profile.php?my_account"><i class='bx bxs-user-detail'></i>My Account</a></li>
              <li><a href="admin_profile.php?change_password"><i class='bx bxs-lock'></i>Change Password</a></li>
              <li><a href="admin_profile.php?manage_users"><i class='bx bx-group'></i>View Users</a></li>
              <li><a href="admin_profile.php?adm_logs"><i class='bx bx-receipt'></i></i>Admin Logs</a></li>
          <?php }else { ?>
              <li><a href="admin_profile.php"><i class='bx bxs-dashboard'></i>Dashboard</a></li>
              <li><a href="../index.php"><i class='bx bx-store-alt'></i>Manage Shop</a></li>
              <li><a href="admin_profile.php?analytics"><i class='bx bx-analyse'></i>Analytics</a></li>
              <li><a href="admin_profile.php?reviews_ratings"><i class='bx bxs-star-half'></i> Reviews & Ratings</a></li>
              <li><a href="admin_profile.php?order_feedbacks"><i class='bx bx-message-square-dots'></i>Order Feedbacks</a></li>
              <li><a href="admin_profile.php?my_account"><i class='bx bxs-user-detail'></i>My Account</a></li>
              <li><a href="admin_profile.php?change_password"><i class='bx bxs-lock'></i>Change Password</a></li>
              <li><a href="admin_profile.php?manage_users"><i class='bx bx-group'></i>User Management</a></li>
              <li><a href="admin_profile.php?manage_customers"><i class='bx bx-group'></i>Customer MGT</a></li>
              <li><a href="admin_profile.php?adm_logs"><i class='bx bx-receipt'></i></i>Admin Logs</a></li>
          <?php } ?>

        </ul>
        <ul class="side-menu">
            <li>
                <a href="admin_logout.php" class="logout">
                    <i class='bx bx-log-out-circle'></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <!-- End of Sidebar -->

    <!-- Main Content -->
    <div class="content">
        <!-- Navbar -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
            </form>
            <input type="checkbox" id="theme-toggle" hidden <?= isset($_SESSION['dark_mode']) ? 'checked' : '' ?>>
            <label for="theme-toggle" class="theme-toggle"></label>
            <a href="#" class="notif">
                <i class='bx bx-bell'></i>
                <span class="count"><?php echo $notification_rows + $low_stock_rows ?></span>
            </a>
            <a href="#" class="profile">
                <img src="admin_imgs/<?php echo $row_admin['profile_image'] ?>">
            </a>
        </nav>

        <!-- End of Navbar -->

        <main>
          <?php
            if (!isset($_GET['analytics']) && !isset($_GET['reviews_ratings']) && !isset($_GET['order_feedbacks']) && !isset($_GET['my_account']) && !isset($_GET['change_password']) && !isset($_GET['add_user']) && !isset($_GET['manage_users']) && !isset($_GET['edit_admin']) && !isset($_GET['store_manager']) && !isset($_GET['del_admin']) && !isset($_GET['adm_logs']) && !isset($_GET['manage_customers']) && !isset($_GET['order_history']) && !isset($_GET['edit_customer'])) {
              include('overview.php');
            }
            if (isset($_GET['analytics'])) {
              include('analytics.php');
            }
            if (isset($_GET['reviews_ratings'])) {
              include('reviews_ratings.php');
            }
            if (isset($_GET['order_feedbacks'])) {
              include('order_feedbacks.php');
            }
            if (isset($_GET['my_account'])) {
              include('my_account.php');
            }
            if (isset($_GET['change_password'])) {
              include('change_password.php');
            }
            if (isset($_GET['add_user'])) {
              include('add_user.php');
            }
            if (isset($_GET['manage_users'])) {
              include('manage_users.php');
            }
            if (isset($_GET['edit_admin'])) {
              include('edit_admin.php');
            }
            if (isset($_GET['store_manager'])) {
              include('store_manager.php');
            }
            if (isset($_GET['del_admin'])) {
              include('del_admin.php');
            }
            if (isset($_GET['adm_logs'])) {
              include('adm_logs.php');
            }
            if (isset($_GET['manage_customers'])) {
              include('manage_customers.php');
            }
            if (isset($_GET['order_history'])) {
              include('order_history.php');
            }
            if (isset($_GET['edit_customer'])) {
              include('edit_customer.php');
            }
           ?>

        </main>

    </div>

    <script src="index.js"></script>
    <script type="text/javascript">
      const themeToggle = document.getElementById('theme-toggle');

      themeToggle.addEventListener('change', () => {
        const isDarkMode = themeToggle.checked;

        // Send the state to the server
        fetch('../../functions/theme-toggle.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `dark_mode=${isDarkMode ? 'dark' : ''}`
        }).then(response => {
            if (response.ok) {
                console.log('Dark mode state updated.');
            } else {
                console.error('Failed to update dark mode state.');
            }
        }).catch(error => console.error('Error:', error));
      });

    </script>
</body>

</html>
