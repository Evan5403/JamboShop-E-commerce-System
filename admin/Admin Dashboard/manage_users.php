<div class="header">
    <div class="left">
        <?php echo $role == 'admin' ? '<h1>User Management</h1>' : '<h1>Users</h1>'?>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="#" class="active">User Management</a></li>
          <?php }  else { ?>
                  <li><a href="admin_profile.php?store_manager">
                        Dashboard
                      </a></li>
                  /
          <?php } ?>
        </ul>
    </div>
</div>

<!-- End of Insights -->

<div class="bottom-data">
    <div class="orders">
        <div class="header">
            <i class='bx bxs-user'></i>
            <h3>Users</h3>
            <?php
              if ($role == 'admin') { ?>
                <a href="admin_profile.php?add_user"><i class='bx bx-user-plus'></i>Add User</a>
            <?php } ?>

        </div>
        <table>
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <?php
                      if ($role == 'admin') { ?>
                        <th>Edit</th>
                        <th>Delete</th>
                    <?php } ?>

                </tr>
            </thead>
            <tbody>
              <?php
                $get_admins = "SELECT * FROM admin_table WHERE 1";
                $result_admins = mysqli_query($conn, $get_admins);
                if ($result_admins) {
                  while ($row = mysqli_fetch_assoc($result_admins)) {
                    $class = ($row['status'] == 'active') ? 'complete' : 'cancelled';
                    $user_role = $row['role'];
                     ?>
                    <tr>
                        <td>
                            <img src="admin_imgs/<?php echo $row['profile_image'] ?>">
                            <p><?php echo $row['user_name'] ?></p>
                        </td>
                        <td><?php echo $row['contact_number'] ?></td>
                        <td><?php echo $row['role'] ?></td>
                        <td><?php echo formatDateTime($row['created_at']) ?></td>
                        <td><?php echo formatDateTime($row['last_login']) ?></td>
                        <td><span class="status <?php echo $class ?>"><?php echo $row['status'] ?></span></td>
                        <?php
                          if ($role == 'admin') { // Display edit and delete if $_SESSION['admin'] role is admin
                            if ($user_role == 'admin') { //do not display edit/delete is table admin role is admin ?>
                              <td>
                              </td>
                              <td>
                              </td>
                        <?php } else { ?>
                              <td>
                                <a href="admin_profile.php?edit_admin=<?php echo $row['admin_id'] ?>">
                                  <i class='bx bxs-edit'></i>
                                </a>
                              </td>
                              <td>
                                <a href="javascript:void(0)" onclick="confirmDelete(<?php echo $row['admin_id']; ?>)">
                                  <i class='bx bx-user-x' ></i>
                                </a>
                              </td>
                        <?php }} ?>
                    </tr>
                <?php }} ?>
            </tbody>
        </table>
    </div>

</div>

<script>
  function confirmDelete(adminId) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'proceed!'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirect to delete admin with the admin_id parameter
        window.location.href = `admin_profile.php?del_admin=${adminId}`;
      }
    });
  }
</script>
