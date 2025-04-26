<?php
  if (isset($_GET['edit_flashsale'])) {
    $flash_sale_id = $_GET['edit_flashsale'];

    $get_flashsale = "SELECT
                        fs.flash_sale_id,
                        fs.flash_sale_name,
                        fs.discount_value,
                        fs.stock_limit,
                        fs.qty_sold,
                        fs.start_date,
                        fs.end_date,
                        fs.applicable_id,
                        fs.status,
                        p.applicable_id,
                        p.product_title,
                        p.instock
                      FROM
                          flash_sales fs
                      LEFT JOIN
                        (
                          SELECT
                          product_id AS applicable_id,
                          product_title,
                          instock
                        FROM
                          products
                        ) p
                      ON
                        fs.applicable_id = p.applicable_id
                    WHERE flash_sale_id=$flash_sale_id";
    $result_flashsale = mysqli_query($conn, $get_flashsale);
    $row_flashsale = mysqli_fetch_assoc($result_flashsale);
    $flash_sale_id = $row_flashsale['flash_sale_id'];
    $flash_sale_name = $row_flashsale['flash_sale_name'];
    $discount_value = $row_flashsale['discount_value'];
    $stock_limit = $row_flashsale['stock_limit'];
    $qty_sold = $row_flashsale['qty_sold'];
    $applicable_id = $row_flashsale['applicable_id'];
    $product_title = $row_flashsale['product_title'];
    $product_instock = $row_flashsale['instock'];
    $start_date = date('Y-m-d H:i:s', strtotime($row_flashsale['start_date']));
    $end_date = date('Y-m-d H:i', strtotime($row_flashsale['end_date']));
    $end_datetime = date('Y-m-d\TH:i', strtotime($row_flashsale['end_date']));
    $qty_sold = $row_flashsale['qty_sold'];
    $status = $row_flashsale['status'];
    date_default_timezone_set('Africa/Nairobi');
  }
 ?>

<div class="container mt-3">
 <h1 class="text-center">Edit Promotion</h1>
 <form class="text-center" action="" method="post">
   <div class="form-outline mb-4 w-50 m-auto">
     <label for="flash_sale_name" class="form-label">Flashsale Name</label>
     <input type="text" name="flash_sale_name" value="<?php echo $flash_sale_name ?>" id="flash_sale_name" class="form-control" autocomplete="off" required>
   </div>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="applicable_id" class="form-label">Applicable Product</label>
     <select class="form-select " name="applicable_id" id="applicable_id">
       <option value="<?php echo $applicable_id ?>"><?php echo $product_title ?></option>
       <?php
          $get_products = "SELECT * FROM `products` WHERE product_id != '$applicable_id'";
          $result_products = mysqli_query($conn,$get_products);
          while ($row = mysqli_fetch_assoc($result_products)) {
            $row_applicable_id = $row['product_id'];
            $row_product_title = $row['product_title'];
            echo "<option value='$row_applicable_id'>$row_product_title</option>";
          }
        ?>
     </select>
   </div>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="discount_value" class="form-label">Discount Value (%)</label>
     <input type="number" name="discount_value" id="discount_value" class="form-control" value="<?php echo $discount_value ?>" min="1" max="99" autocomplete="off" required>
   </div>
   <div class="form-outline mb-3 w-50 m-auto">
     <label for="stock_limit" class="form-label">Stock Limit</label>
     <input type="number" name="stock_limit" id="stock_limit" class="form-control" value="<?php echo $stock_limit ?>" autocomplete="off" min="1" required>
   </div>
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
   <input type="submit" name="update_flashsale" value="Update Flashsale" class="btn btn-info px-3 mt-3">
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
  if (isset($_POST['update_flashsale'])) {
    $flash_sale_name = $_POST['flash_sale_name'];
    $applicable_id = $_POST['applicable_id'];
    $discount_value = $_POST['discount_value'];
    $stock_limit = $_POST['stock_limit'];
    $end_datetime = htmlspecialchars($_POST['end_datetime'], ENT_QUOTES, 'UTF-8');
    $form_status = $_POST['status'];
    // only update start date when inactive promotion is activated
    if ($status == 'inactive' AND $form_status == 'active') {
      $start_date = date("Y-m-d H:i:s");
      $qty_sold = 0;
      $deactivated = 'yes';
    }elseif ($status == 'active' && $form_status == 'inactive') {
      $deactivated = 'no';
    }

    // CHECK FOR STOCK QTY OF THE PRODUCT
    $get_products_instock = "SELECT * FROM `products` WHERE product_id = '$applicable_id'";
    $result_instock = mysqli_query($conn,$get_products_instock);
    $row_instock = mysqli_fetch_assoc($result_instock);
    $instock = $row_instock['instock'];
    $product_title = $row_instock['product_title'];

    if ($stock_limit > $instock) {
      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Oops!",
          text: "<?php echo $product_title ?> has <?php echo $instock; ?> in-stock!",
          icon: "warning"
        });
      </script>
      <?php
      exit();
    }

    // CHECK SAME FLASHSALE NAME
    $check_query = "SELECT * FROM `flash_sales` WHERE flash_sale_id != '$flash_sale_id'";
    $result_check_query = mysqli_query($conn, $check_query);
    while ($name_row = mysqli_fetch_assoc($result_check_query)) {
      $flashsale_name = $name_row['flash_sale_name'];
      if ($flash_sale_name === $flashsale_name) {
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Oops!",
            text: "Flashsale Name Already Exists!",
            icon: "warning"
          });
        </script>
        <?php
        exit();
      }
    }

    // ONLY ONE FLASHSALE CAN BE ACTIVE
    if ($form_status == 'active') {
      $select_query = "SELECT * FROM `flash_sales` WHERE flash_sale_id != '$flash_sale_id' AND `status` = 'active'";
      $exe_query = mysqli_query($conn, $select_query);
      $flashsale_id = '';
      if ($row_fetch = mysqli_fetch_assoc($exe_query)) {
        $flashsale_id = $row_fetch['flash_sale_id'];
        $update_status = "UPDATE `flash_sales` SET `status` = 'inactive' WHERE `flash_sale_id`='$flashsale_id'";
        $result_query = mysqli_query($conn, $update_status);
      }
    }

    // UPDATE FLASHSALE -- MAIN CODE
    $update_flashsale = "UPDATE `flash_sales`
                         SET
                            flash_sale_name='$flash_sale_name', discount_value='$discount_value', start_date='$start_date', end_date='$end_datetime', applicable_id='$applicable_id', stock_limit=$stock_limit, qty_sold=$qty_sold, status='$form_status'
                         WHERE flash_sale_id=$flash_sale_id";
    $result = mysqli_query($conn, $update_flashsale);

    if ($result) {
      if ($deactivated == 'yes') {
        $action = "Activated Flashsale";
        $action_effect = "positive";
        $details = "Flashsale Name: $flash_sale_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }elseif ($deactivated == 'no') {
        $action = "Deactivated Flashsale";
        $action_effect = "negative";
        $details = "Flashsale Name: $flash_sale_name"; // Custom details
        logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      }
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Flashsale updated successfully!",
        }).then(() => {
          window.open('index.php?edit_flashsale=<?php echo $flash_sale_id ?>','_self');
        });
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
