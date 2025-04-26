<div class="header">
    <div class="left">
        <h1>Admin Logs</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="#" class="active">Admin Logs</a></li>
          <?php }  elseif ($role == 'store_manager') { ?>
                  <li><a href="admin_profile.php?store_manager">
                        Dashboard
                      </a></li>
                  /
                  <li><a href="#" class="active">Admin Logs</a></li>
          <?php } else { ?>
                    <li><a href="admin_profile.php?analytics">
                          Analytics
                        </a></li>
                    /
                    <li><a href="#" class="active">Admin Logs</a></li>
          <?php } ?>
        </ul>
    </div>
</div>

<!-- End of Insights -->

<div class="bottom-data">
    <div class="orders">
        <div class="header">
            <i class='bx bxs-user'></i>
            <h3>Admin</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Admin</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Date/Time</th>
                </tr>
            </thead>
            <tbody>
              <?php
              // Determine admin role
              $role_condition = '';
              switch ($role) {
                case 'store_manager':
                  $role_condition = "adm.role = 'store_manager'";
                  break;
                case 'marketer':
                  $role_condition = "adm.role = 'marketer'";
                  break;
                default:
                  $role_condition = "adm.role IN ('store_manager', 'marketer', 'admin')";
              }
              $admin_logs = "SELECT
                              admin_logs.*,
                              adm.admin_id,
                              adm.user_name,
                              adm.profile_image,
                              adm.role,
                              adm.status
                            FROM
                              admin_logs
                            INNER JOIN
                              admin_table adm
                            ON
                              admin_logs.adm_user = adm.admin_id
                            WHERE
                              $role_condition
                            ORDER BY
                              timestamp DESC";
              $execute_query = mysqli_query($conn, $admin_logs);
                if ($execute_query) {
                  while ($row = mysqli_fetch_assoc($execute_query)) {
                    $class = ($row['status'] == 'active') ? 'complete' : 'cancelled';
                    $user_role = $row['role'];
                    $action_effect = $row['action_effect'];
                     ?>
                    <tr>
                        <td>
                            <img src="admin_imgs/<?php echo $row['profile_image'] ?>">
                            <p><?php echo $row['user_name'] ?></p>
                        </td>
                        <td><?php echo $row['role'] ?></td>
                        <td><span class="status <?php echo $class ?>"><?php echo $row['status'] ?></span></td>
                        <td class="status <?php echo ($action_effect == 'positive') ? 'complete' : 'cancelled' ?>">
                          <span class="status <?php echo ($action_effect == 'positive') ? 'complete' : 'cancelled' ?>"><?php echo $row['action'] ?></span>
                        </td>
                        <td><?php echo $row['details'] ?></td>
                        <td><?php echo formatDateTime($row['timestamp']) ?></td>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>

</div>
