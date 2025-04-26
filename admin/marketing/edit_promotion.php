<?php
  if (isset($_GET['edit_promotion'])) {
    $promotion_id = $_GET['edit_promotion'];

    $get_promotion = "SELECT * FROM `promotions` WHERE promotion_id=$promotion_id";
    $result_promotion = mysqli_query($conn, $get_promotion);
    $row_promotion = mysqli_fetch_assoc($result_promotion);
    $promotion_name = $row_promotion['promotion_name'];
    $discount_value = $row_promotion['discount_value'];
    $start_date = date('Y-m-d H:i:s', strtotime($row_promotion['start_date']));
    $end_date = date('Y-m-d H:i', strtotime($row_promotion['end_date']));
    $end_datetime = date('Y-m-d\TH:i', strtotime($row_promotion['end_date']));
    $minimum_cart_value = $row_promotion['minimum_cart_value'];
    $status = $row_promotion['status'];
    date_default_timezone_set('Africa/Nairobi');
  }
 ?>

<div class="container mt-3">
 <h1 class="text-center">Edit Promotion</h1>
 <form class="text-center" action="" method="post">
   <div class="form-outline mb-4 w-50 m-auto">
     <label for="promotion_name" class="form-label">Promotion Name</label>
     <input type="text" name="promotion_name" value="<?php echo $promotion_name ?>" id="promotion_name" class="form-control" autocomplete="off" required>
   </div>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="discount_value" class="form-label">Discount Value (%)</label>
     <input type="number" name="discount_value" id="discount_value" class="form-control" value="<?php echo $discount_value ?>" autocomplete="off" required>
   </div>
   <?php
      if ($minimum_cart_value == 0) {
        ?>
        <div class="form-outline mb-3 w-50 m-auto" id="min_cart_value_wrapper" style="display: none;">
          <label for="min_cart_value" class="form-label">Minimum Cart Value</label>
          <input type="number" class="form-control" id="min_cart_value" name="min_cart_value" placeholder="Minimum Cart Value" />
        </div>
    <?php  } else { ?>
      <div class="form-outline mb-3 w-50 m-auto" id="min_cart_value_wrapper">
        <label for="min_cart_value" class="form-label">Minimum Cart Value</label>
        <input type="number" class="form-control" id="min_cart_value" name="min_cart_value" value="<?php echo $minimum_cart_value ?>" />
      </div>
    <?php } ?>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="end_datetime" class="form-label">End Date</label>
     <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" value="<?php echo  htmlspecialchars($end_datetime, ENT_QUOTES, 'UTF-8') ?>" required>
   </div>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="status" class="form-label">Status</label>
     <select class="form-select " name="status" id="status">
       <option value="<?php echo $status ?>"><?php echo $status ?></option>
       <?php
          if ($status == 'inactive') { ?>
            <option value="active">Activate</option>
          <?php } else { ?>
            <option value="inactive">Deactivate</option>
          <?php } ?>
     </select>
   <input type="submit" name="update_promotion" value="Update Promotion" class="btn btn-info px-3 mt-3">
 </form>
</div>

<script type="text/javascript">

  // Get the input field
  const endDateInput = document.getElementById('end_datetime');

  // Function to set the minimum date and time to tomorrow at 00:00
  function setMinEndDateTime() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(today.getDate() + 1); // Add 1 day
    tomorrow.setHours(0, 0, 0, 0); // Set time to 00:00

    // Format the date and time to 'YYYY-MM-DDTHH:MM' for datetime-local input
    const minDateTime = tomorrow.toISOString().slice(0, 16); // Get date and time

    // Set the min attribute to the input field
    endDateInput.setAttribute('min', minDateTime);
  }

  // Call the function when the page loads
  setMinEndDateTime();

</script>

<?php
  if (isset($_POST['update_promotion'])) {
    $promotion_name = $_POST['promotion_name'];
    $discount_value = $_POST['discount_value'];
    $min_cart_value = $_POST['min_cart_value'];
    $end_datetime = htmlspecialchars($_POST['end_datetime'], ENT_QUOTES, 'UTF-8');
    $form_status = $_POST['status'];

    $deactivated = 'nothing';
    if ($status == 'inactive' && $form_status == 'active') {
      $deactivated = 'yes';
    }elseif ($status == 'active' && $form_status == 'inactive') {
      $deactivated = 'no';
    }

    // var_dump($deactivated);
    // die;

    // only update start date when inactive promotion is activated
    if ($status == 'inactive' AND $form_status == 'active') {
      $start_date = date("Y-m-d H:i:s");
    }

    $update_promotion = "UPDATE `promotions`
                         SET
                            promotion_name='$promotion_name', discount_value='$discount_value', start_date='$start_date', end_date='$end_datetime', minimum_cart_value='$min_cart_value', status='$form_status'
                         WHERE promotion_id=$promotion_id";
    $result = mysqli_query($conn, $update_promotion);

    if ($result) {
      if ($deactivated == 'yes') {
        $action = "Activated Promotion";
        $action_effect = "positive";
        $details = "Promotion Name: $promotion_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }elseif ($deactivated == 'no') {
        $action = "Deactivated Promotion";
        $action_effect = "negative";
        $details = "Promotion Name: $promotion_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Promotion updated successfully!",
        }).then(() => {
          window.open('index.php?edit_promotion=<?php echo $promotion_id ?>','_self');
        });
        // window.location.reload();
      </script>
      <?php
    }else {
      ?>
      <script>
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "An error occured",
        });
      </script>
      <?php
    }
  }
 ?>
