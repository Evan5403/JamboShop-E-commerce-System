<h3 class="text-center text-success">All Categories</h3>
<div class="filter-options">
  <div class="item-sortir">
    <div class="label">
      <span class="mobile-hide">Filter By Department</span>
      <i class="ri-arrow-down-s-line"></i>
    </div>
    <ul>
      <!-- <li><input type="checkbox" name="date_range" class="filter-date" value="All" id="All"> <label for="All">Default</label></li> -->
      <?php
        $select_dpt = "SELECT * FROM `department`";
        $exe_dpt_query = mysqli_query($conn,$select_dpt);
        while ($row = mysqli_fetch_assoc($exe_dpt_query)) { ?>
          <li><input
                type="checkbox"
                name="checkbox"
                class="filter-status filter-dpt"
                value="<?php echo $row['department_title'] ?>"
                id="<?php echo $row['department_title'] ?>"
                > <label for="<?php echo $row['department_title'] ?>"><?php echo $row['department_title'] ?>
          </label></li>
      <?php  } ?>
    </ul>
  </div>
</div>

<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Category Title</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center" id="category-container-table">
    <?php
      $get_category = "SELECT * FROM `categories`";
      $result_category = mysqli_query($conn, $get_category);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($result_category)){
        $category_id = $row['category_id'];
        $category_title = $row['category_title'];
        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $category_title ?></td>
        <td> <a href='index.php?edit_category=<?php echo $category_id ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_category=<?php echo $category_id ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">

  $(document).ready(function () {
    // Function to fetch and display filtered products
    function fetchFilteredProducts() {
      let selectedDepartments = [];

      // Collect selected filters
      $('.filter-dpt:checked').each(function () {
        selectedDepartments.push($(this).val());
      });

      // Determine whether we are filtering by category or brand
      let filterType = 'admin';
      let filterValue = 1;

      // Send AJAX request
      $.ajax({
        url: '../functions/filter_categories.php',
        method: 'POST',
        data: {
          filterType: filterType,
          filterValue: filterValue,
          departments: selectedDepartments
        },
        success: function (response) {
          $('#category-container-table').html(response);
        },
        error: function () {
          alert('Failed to fetch products. Please try again.');
        }
      });
    }

    // Trigger filtering when a filter is changed
    $('.filter-dpt').on('change', function () {
      fetchFilteredProducts();
    });
  });
</script>
