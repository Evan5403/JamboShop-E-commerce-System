<form class="" action="" method="post">
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="promotion_name" class="form-label">Promotion Name</label>
    <input type="text" name="promotion_name" id="promotion_name" class="form-control" placeholder="Enter Promotion Name" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="discount_value" class="form-label">Discount Value (%)</label>
    <input type="number" name="discount_value" id="discount_value" class="form-control" placeholder="Discount Value" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="applicable_to" class="form-label">Applicable To</label>
    <select class="form-select " name="applicable_to" id="applicable_to">
      <option value="">Applicable To</option>
      <option value='product'>product</option>
      <option value='category'>category</option>
      <option value='cart'>cart</option>
    </select>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="applicable_id" class="form-label">Applicable ID</label>
    <select class="form-select " name="applicable_id" id="applicable_id">
      <option value="">Applicable ID</option>
    </select>
  </div>
  <div class="form-outline mb-3 w-50 m-auto" id="min_cart_value_wrapper" style="display: none;">
    <label for="min_cart_value" class="form-label">Minimum Cart Value</label>
    <input type="number" class="form-control" id="min_cart_value" name="min_cart_value" placeholder="Minimum Cart Value" />
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <label for="end_date" class="form-label">End Date</label>
    <input type="datetime-local" name="end_date" id="end_date" class="form-control" placeholder="End Date" autocomplete="off" required>
  </div>
  <div class="form-outline mb-3 w-50 m-auto">
    <input type="submit" name="insert_promotion" class="btn btn-info mb-4" value="Submit">
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
    const minCartValueInput = document.getElementById('min_cart_value'); // Get the Minimum Cart Value input

    // Reset the Applicable ID dropdown
    applicableIdSelect.innerHTML = '<option value="">Applicable ID</option>';
    applicableIdSelect.disabled = true;

    // Hide the Minimum Cart Value input by default
    minCartValueInput.parentElement.style.display = 'none';

    if (applicableTo === 'cart') {
      applicableIdSelect.parentElement.style.display = 'none';
      // applicableIdSelect.disabled = true; // If 'cart' is selected, disable the Applicable ID dropdown
      minCartValueInput.parentElement.style.display = 'block'; // Show the input field
    } else if (applicableTo) {
      applicableIdSelect.parentElement.style.display = 'block';
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

  if (isset($_POST['insert_promotion'])) {
    $promotion_name = $_POST['promotion_name'];
    $discount_value = $_POST['discount_value'];
    $applicable_to = $_POST['applicable_to'];
    $applicable_id = ($applicable_to == 'cart') ? NULL : $_POST['applicable_id'];
    $min_cart_value = ($applicable_to == 'cart') ? $_POST['min_cart_value'] : NULL;
    $end_date = $_POST['end_date'];

    $select_promotions = "SELECT * FROM `promotions`";
    $execute_query = mysqli_query($conn,$select_promotions);
    while ($row = mysqli_fetch_assoc($execute_query)) {
      if ($applicable_id == $row['applicable_id']) {
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Error!",
            text: "There is a promotion for this category/product!",
            icon: "warning"
          });
        </script>
        <?php
        exit();
      }elseif ($promotion_name == $row['promotion_name']) {
        ?>
        <script type="text/javascript">
          Swal.fire({
            title: "Error!",
            text: "Promotion already exists!",
            icon: "warning"
          });
        </script>
        <?php
        exit();
      } elseif ($min_cart_value !== NULL) {
        if ($min_cart_value == $row['minimum_cart_value'] && $row['status'] == 'active') {
          ?>
          <script type="text/javascript">
            Swal.fire({
              title: "Error!",
              text: "There is an active cart promotion with the specified minimum cart value!",
              icon: "warning"
            });
          </script>
          <?php
          exit();
        }
      }
    }

    $insert_promotions = "INSERT INTO `promotions`
                          (promotion_name,discount_value,start_date,end_date,applicable_to,applicable_id,minimum_cart_value,status)
                        VALUES
                          ('$promotion_name','$discount_value',NOW(),'$end_date','$applicable_to','$applicable_id','$min_cart_value','active')";
    $result = mysqli_query($conn, $insert_promotions);
    if ($result) {
      $action = "Added A New Promotion";
      $action_effect = "positive";
      $details = "Promotion Name: $promotion_name"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Success!",
          text: "Promotion inserted successfully!",
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
