<?php

  if ($role !== 'admin') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
  }

  // Query to fetch dashboard metrics
  $query = "
            SELECT
              (SELECT COUNT(*) FROM user_orders) AS total_orders,
              (SELECT SUM(amount_due) FROM user_orders WHERE order_status = 'complete') AS total_revenue,
              (SELECT COUNT(*) FROM user_table) AS total_customers,
              (SELECT SUM(total_products) FROM user_orders WHERE order_status = 'complete') AS total_sales
            ";

  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
      $data = mysqli_fetch_assoc($result);
      $totalOrders = $data['total_orders'];
      $totalRevenue = $data['total_revenue'];
      $totalCustomers = $data['total_customers'];
      $totalSales = $data['total_sales'];
  } else {
      $totalOrders = $totalRevenue = $totalCustomers = $totalSales = 0;
  }

 ?>

 <div class="header">
     <div class="left">
         <h1>Dashboard</h1>
         <!-- <ul class="breadcrumb">
             <li><a href="#">
                     Analytics
                 </a></li>
             /
             <li><a href="#" class="active">Shop</a></li>
         </ul> -->
     </div>
 </div>

 <!-- Insights -->
 <ul class="insights">
     <li>
         <i class='bx bx-calendar-check'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalOrders, 0, '.', ',') ?>
             </h3>
             <p>Total Orders</p>
         </span>
     </li>
     <li><i class='bx bx-money'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalRevenue, 0, '.', ',') . '/-' ?>
             </h3>
             <p>Total Revenue</p>
         </span>
     </li>
     <li><i class='bx bxs-user' ></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalCustomers, 0, '.', ',') ?>
             </h3>
             <p>Customers</p>
         </span>
     </li>
     <li><i class='bx bx-line-chart'></i>
         <span class="info">
             <h3>
                 <?php echo number_format($totalSales, 0, '.', ',') ?>
             </h3>
             <p>Total Sales</p>
         </span>
     </li>
 </ul>
 <!-- End of Insights -->

 <div class="bottom-data">
     <div class="orders">
         <div class="header">
             <i class='bx bx-receipt'></i>
             <h3>Recent Admin Logs</h3>
         </div>
         <table>
             <thead>
                 <tr>
                     <th>Admin</th>
                     <th>Action</th>
                     <th>Date</th>
                 </tr>
             </thead>
             <tbody>
               <?php
                  $admin_logs = "SELECT
                                  admin_logs.*,
                                  adm.admin_id,
                                  adm.user_name,
                                  adm.profile_image
                                FROM
                                  admin_logs
                                INNER JOIN
                                  admin_table adm
                                ON
                                  admin_logs.adm_user = adm.admin_id
                                ORDER BY
                                  timestamp DESC
                                LIMIT 3";
                  $execute_query = mysqli_query($conn, $admin_logs);
                  if ($execute_query) {
                    while ($row = mysqli_fetch_assoc($execute_query)) {
                      $action_effect = $row['action_effect'];
                       ?>
                      <tr>
                          <td>
                              <img src="images/<?php echo $row['profile_image'] ?>">
                              <p><?php echo $row['user_name'] ?></p>
                          </td>
                          <td>
                            <span class="status <?php echo ($action_effect == 'positive') ? 'complete' : 'cancelled' ?>"><?php echo $row['action'] ?></span>
                          </td>
                          <td><?php echo formatDateTime($row['timestamp']) ?></td>
                      </tr>
                  <?php }} ?>
             </tbody>
         </table>
     </div>

     <!-- Reminders -->
     <div class="reminders">
         <div class="header">
             <i class='bx bx-bell'></i>
             <h3>Alerts</h3>
         </div>
         <ul class="task-list">
           <?php
            if ($notification_rows == 0 && $low_stock_rows == 0) {
              echo "<li><p>No Alerts</p></li>";
            }
             if ($delayed_orders_result && $notification_rows > 0) {
               while ($row = mysqli_fetch_assoc($delayed_orders_result)) {
                 $expectedDate = $row['expected_date']; // Assuming this is in a valid date format
                 $currentDate = new DateTime(); // Current date
                 $expectedDateObj = new DateTime($expectedDate); // Convert the expected date to a DateTime object

                 // Calculate the difference
                 $interval = $currentDate->diff($expectedDateObj);

                 // Get the difference in days
                 $daysDifference = $interval->days;
                 $hoursDifference  = ($daysDifference === 0) ? ($currentDate->getTimestamp() - $expectedDateObj->getTimestamp()) / 3600 : 'null';
                  ?>
                 <li class="not-completed">
                     <div class="task-title">
                         <i class='bx bx-x-circle'></i>
                         <p>Delayed Order <?php echo $row['invoice_number'] ?></p>
                         <p>By <?php echo ($daysDifference !== 0) ? $daysDifference . 'day(s)' : floor($hoursDifference) . 'hour(s)' ?></p>
                     </div>
                     <i class='bx bx-dots-vertical-rounded'></i>
                 </li>
              <?php }
             }
             if ($low_stock_result && $low_stock_rows > 0) {
               while ($row = mysqli_fetch_assoc($low_stock_result)) {
                 $trimmedTitle = (strlen($row['product_title']) > 13)
                                  ? substr($row['product_title'], 0, 13) . "..."
                                  : $row['product_title'];
                  if ($row['instock'] == 0) { ?>
                    <li class="not-completed">
                        <div class="task-title">
                            <i class='bx bx-x-circle'></i>
                            <p>
                             <?php echo $trimmedTitle . ' Out-Of-Stock' ?>
                            </p>
                        </div>
                        <i class='bx bx-dots-vertical-rounded'></i>
                    </li>
               <?php }else { ?>
                 <li class="not-warning">
                     <div class="task-title">
                         <i class='bx bx-info-circle'></i>
                         <p>
                          <?php echo $trimmedTitle . ' Low-On-Stock :' . $row['instock'] ?>
                         </p>
                     </div>
                     <i class='bx bx-dots-vertical-rounded'></i>
                 </li>
               <?php }}} ?>
         </ul>
     </div>

     <!-- End of Reminders-->

 </div>
