<form class="" action="" method="post">
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="flashsale_name" class="form-label">Flashsale Name</label>
    <input type="text" name="flashsale_name" id="flashsale_name" class="form-control" placeholder="Enter Flashsale Name" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="discount_value" class="form-label">Discount Value (%)</label>
    <input type="number" name="discount_value" id="discount_value" class="form-control" placeholder="Discount Value" min="1" max="99" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="applicable_to" class="form-label">Applicable To</label>
    <select class="form-select " name="applicable_to" id="applicable_to">
      <option value="">Applicable To</option>
      <option value='product'>product</option>
    </select>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="applicable_id" class="form-label">Applicable ID</label>
    <select class="form-select " name="applicable_id" id="applicable_id">
      <option value="">Applicable ID</option>
    </select>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="end_date" class="form-label">End Date</label>
    <input type="datetime-local" name="end_date" id="end_date" class="form-control" placeholder="End Date" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="stock_limit" class="form-label">Stock Limit</label>
    <input type="number" name="stock_limit" id="stock_limit" class="form-control" placeholder="Stock Limit" autocomplete="off" min="1" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <input type="submit" name="insert_flashscale" class="btn btn-info mb-4" value="Submit">
  </div>
</form>

<script type="text/javascript">

  // Get the input field
  const endDateInput = document.getElementById('end_date');

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

  document.getElementById('applicable_to').addEventListener('change', function () {
    const applicableTo = this.value;
    const applicableIdSelect = document.getElementById('applicable_id');

    // Reset the Applicable ID dropdown
    applicableIdSelect.innerHTML = '<option value="">Applicable ID</option>';
    applicableIdSelect.disabled = true;

    if (applicableTo === 'cart') {
      // If 'cart' is selected, disable the Applicable ID dropdown
      applicableIdSelect.disabled = true;
    } else if (applicableTo) {
      // Fetch IDs dynamically
      fetch(`marketing/fetch_products_cats.php?applicable_to=${applicableTo}`)
      .then(response => response.json())
      .then(data => {
        applicableIdSelect.disabled = false;
        data.forEach(item => {
          const option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.name; // Display the name or ID
          applicableIdSelect.appendChild(option);
        });
      })
      .catch(error => console.error('Error fetching IDs:', error));
    }
  });
</script>

<?php

  if (isset($_POST['insert_flashscale'])) {
    $flashsale_name = $_POST['flashsale_name'];
    $discount_value = $_POST['discount_value'];
    $applicable_to = $_POST['applicable_to'];
    $applicable_id = $_POST['applicable_id'];
    $end_date = $_POST['end_date'];
    $stock_limit = $_POST['stock_limit'];

    // GET STOCK QUANTITY
    $get_stock_qty = "SELECT * FROM `products` WHERE `product_id`='$applicable_id'";
    $execute_sql = mysqli_query($conn, $get_stock_qty);
    $row_instock = mysqli_fetch_assoc($execute_sql);
    $instock = $row_instock['instock'];

    // CHECK FOR STOCK QTY AND SAME FLASHSALE
    $check_query = "SELECT * FROM `flash_sales` WHERE 1";
    $result_check_query = mysqli_query($conn, $check_query);
    while ($name_row = mysqli_fetch_assoc($result_check_query)) {
      $flash_sale_name = $name_row['flash_sale_name'];
      if ($flash_sale_name === $flashsale_name) {
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Oops!",
            text: "Flashsale Already Exists!",
            icon: "warning"
          });
        </script>
        <?php
        exit();
      }
    }
    if ($instock < $stock_limit) {
      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Oops!",
          text: "Product Instock is <?php echo $instock; ?>!",
          icon: "warning"
        });
      </script>
      <?php
      exit();
    }

    // ONLY ONE FLASHSALE CAN BE ACTIVE
    $select_query = "SELECT * FROM `flash_sales` WHERE `status` = 'active'";
    $exe_query = mysqli_query($conn, $select_query);
    $flashsale_id = '';
    if ($row_fetch = mysqli_fetch_assoc($exe_query)) {
      $flashsale_id = $row_fetch['flash_sale_id'];
      $update_status = "UPDATE `flash_sales` SET `status` = 'inactive' WHERE `flash_sale_id`='$flashsale_id'";
      $result_query = mysqli_query($conn, $update_status);
    }

    // INSERT FLASHSALE
    $insert_flashscale = "INSERT INTO `flash_sales`
                          (flash_sale_name,start_date,end_date,discount_value,applicable_to,applicable_id,stock_limit,status)
                        VALUES      ('$flashsale_name',NOW(),'$end_date','$discount_value','$applicable_to','$applicable_id',$stock_limit,'active')";
    $result = mysqli_query($conn, $insert_flashscale);
    if ($result) {
      $action = "Added A New Flashsale";
      $action_effect = "positive";
      $details = "Flashsale Name: $flashsale_name"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);

      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Success!",
          text: "Flashsale inserted successfully!",
          icon: "success"
        });
      </script>
      <?php
    } else {
      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Oops!",
          text: "Something Went Wrong!",
          icon: "warning"
        });
      </script>
      <?php
    }

  }

 ?>
