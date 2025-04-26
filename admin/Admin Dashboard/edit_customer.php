<?php
  if ($role !== 'admin' AND $role !== 'store_manager') {
    echo "<script>window.open('admin_logout.php','_self');</script>";
    exit();
  }

  if (isset($_GET['edit_customer'])) {
    $userID = $_GET['edit_customer'];
    $get_user = "SELECT * FROM user_table WHERE user_id='$userID'";
    $result_user = mysqli_query($conn, $get_user);
    $row_user = mysqli_fetch_assoc($result_user);
  }
 ?>
<div class="header">
    <div class="left">
        <h1>Update Customer Status</h1>
        <ul class="breadcrumb">
          <?php
            if ($role == 'admin') { ?>
              <li><a href="admin_profile.php">
                    Dashboard
                  </a></li>
              /
              <li><a href="admin_profile.php?manage_customers">Customer MGT</a></li>
          /
          <li><a href="#" class="active"><?php echo '@'.$row_user['user_name'] ?></a></li>
        <?php } elseif ($role='store_manager') { ?>
              <li><a href="admin_profile.php?store_manager">
                    Dashboard
                  </a></li>
              /
              <li><a href="admin_profile.php?manage_customers">Customer MGT</a></li>
          /
          <li><a href="#" class="active"><?php echo '@'.$row_user['user_name'] ?></a></li>
        <?php } ?>
        </ul>
    </div>
</div>
<div class="bottom-data">
  <div class="orders">
    <form id="edit_account" method="post" enctype="multipart/form-data">
      <p>
        <label for="full_name">Full Name</label><br>
        <input type="text" name="full_name" id="full_name" value="<?php echo $row_user['full_name'] ?>" readonly>
      </p>
      <p>
        <label for="full_name">User Name</label><br>
        <input type="text" name="full_name" id="full_name" value="<?php echo $row_user['user_name'] ?>" readonly>
      </p>
      <p>
        <label for="email">Email Address</label><br>
        <input type="email" name="email" value="<?php echo $row_user['user_email'] ?>" id="email" readonly>
      </p>
      <p>
        <label for="contact_number">Contact Phone Number <span></span></label> <br>
        <input type="tel" value="<?php echo $row_user['user_mobile'] ?>" readonly>
      </p>
      <p>
        <label for="status">Status <span></span></label><br>
        <select name="status" id="status" required>
          <option value="<?php echo $row_user['status'] ?>"><?php echo $row_user['status'] ?></option>
          <?php
            echo ($row_user['status'] == 'active') ? "<option value='blocked'>block</option>" : "<option value='active'>unblock</option>";
          ?>
        </select>
      </p>

      <div class="primary-checkout" id="submitContainer">
       <input type="submit" name="update_cusomer_status" value="Update Customer Status" id="change_profile">
      </div>
    </form>
  </div>

</div>

<?php

  if (isset($_POST['update_cusomer_status'])) {
    $full_name = $row_user['full_name'];
    $status = $row_user['status'];
    $form_status = $_POST['status'];

    $deactivated = '';
    if ($status == 'blocked' AND $form_status == 'active') {
      $deactivated = 'yes';
    }elseif ($status == 'active' && $form_status == 'blocked') {
      $deactivated = 'no';
    }

    // Update the product details in the database
    $update_profile = "UPDATE `user_table`
                       SET status='$form_status' WHERE user_id='$userID'";
    $result_update = mysqli_query($conn, $update_profile);

    if ($result_update) {
      if ($deactivated == 'yes') {
        $action = "Activated Customer";
        $action_effect = "positive";
        $details = "Customer Name: $full_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }elseif ($deactivated == 'no') {
        $action = "Deactivated Customer";
        $action_effect = "negative";
        $details = "Customer Name: $full_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Status Updated Successfully",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('admin_profile.php?edit_customer=<?php echo $userID ?>','_self')
        });
      </script>
      <?php
    }
  }

?>
