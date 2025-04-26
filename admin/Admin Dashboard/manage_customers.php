<div class="header">
    <div class="left">
        <h1>Customer Management</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin' || $role='store_manager') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="#" class="active">Customer MGT</a></li>
          <?php } ?>
        </ul>
    </div>
</div>

<!-- End of Insights -->

<div class="bottom-data">
    <div class="orders">
        <div class="header">
            <i class='bx bxs-user'></i>
            <h3>Customers</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Date Of Birth</th>
                    <th>Order History</th>
                    <th>Status</th>
                    <th>Edit</th>

                </tr>
            </thead>
            <tbody>
              <?php
                $get_customers = "SELECT * FROM user_table WHERE 1";
                $result_customers = mysqli_query($conn, $get_customers);
                if ($result_customers) {
                  while ($row = mysqli_fetch_assoc($result_customers)) {
                    $class = ($row['status'] == 'active') ? 'complete' : 'cancelled';
                     ?>
                    <tr>
                        <td>
                            <img src="../../users_area/user_images/<?php echo $row['user_image'] ?>">
                            <p><?php echo $row['user_name'] ?></p>
                        </td>
                        <td><?php echo $row['full_name'] ?></td>
                        <td><?php echo $row['gender'] ?></td>
                        <td><?php echo $row['user_mobile'] ?></td>
                        <td><?php echo $row['user_email'] ?></td>
                        <td><?php echo formatDateTime($row['date_of_birth']) ?></td>
                        <td>
                          <button class="status"><a href="admin_profile.php?order_history=<?php echo $row['user_id'] ?>">Order History</a></button>
                        </td>
                        <td><span class="status <?php echo $class ?>"><?php echo $row['status'] ?></span></td>
                        <td>
                          <a href="admin_profile.php?edit_customer=<?php echo $row['user_id'] ?>">
                            <i class='bx bxs-edit'></i>
                          </a>
                        </td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>

</div>
