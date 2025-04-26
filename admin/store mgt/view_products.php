

<h1 class="text-center text-success">All Products</h1>
<div class="filter-options">
  <div class="item-sortir">
    <div class="label">
      <span class="mobile-hide">Filter By Brand</span>
      <i class="ri-arrow-down-s-line"></i>
    </div>
    <ul>
      <?php
        $select_brand = "SELECT * FROM `brands` ORDER BY `brand_title` ASC";
        $exe_brand_query = mysqli_query($conn,$select_brand);
        while ($row = mysqli_fetch_assoc($exe_brand_query)) { ?>
          <li><input
                type="checkbox"
                name="checkbox"
                class="filter-status filter-brand"
                value="<?php echo $row['brand_title'] ?>"
                id="<?php echo $row['brand_title'] ?>"
                > <label for="<?php echo $row['brand_title'] ?>"><?php echo $row['brand_title'] ?>
          </label></li>
      <?php  } ?>
    </ul>
  </div>
  <div class="item-sortir">
    <div class="label">
      <span class="mobile-hide">Filter By Category</span>
      <i class="ri-arrow-down-s-line"></i>
    </div>
    <ul>
      <!-- <li><input type="checkbox" name="date_range" class="filter-date" value="All" id="All"> <label for="All">Default</label></li> -->
      <?php
        $select_category = "SELECT * FROM `categories` ORDER BY `category_title` ASC";
        $exe_cat_query = mysqli_query($conn,$select_category);
        while ($row = mysqli_fetch_assoc($exe_cat_query)) { ?>
          <li><input
                type="checkbox"
                name="checkbox"
                class="filter-status filter-category"
                value="<?php echo $row['category_title'] ?>"
                id="<?php echo $row['category_title'] ?>"
                > <label for="<?php echo $row['category_title'] ?>"><?php echo $row['category_title'] ?>
          </label></li>
      <?php  } ?>
    </ul>
  </div>
</div>

<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>Product ID</th>
      <th>Product Image</th>
      <th>Product Title</th>
      <th>Product Description</th>
      <th>Price</th>
      <th>Instock</th>
      <th>Total Sold</th>
      <th>Available</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-light" id="product-container-table">
    <?php
    // exit();
      $get_products = "SELECT * FROM `products`";
      $exe_query = mysqli_query($conn,$get_products);
      while ($row = mysqli_fetch_assoc($exe_query)) {
        $product_id = $row['product_id'];
        $product_title = $row['product_title'];
        $product_description = $row['product_description'];
        $instock = $row['instock'];
        $instock_sold = $row['instock_sold'];
        $price = $row['price'];
        $status = $row['status'];
        $product_image1 = $row['product_image1'];
      ?>
        <tr class='text-center'>
          <td><?php echo $product_id ?></td>
          <td><img src='../product_imgs/<?php echo $product_image1 ?>' class='product_img' alt='product_img'></td>
          <td><?php echo $product_title ?></td>
          <td><?php echo $product_description ?></td>
          <td>Kshs. <?php echo $price ?></td>
          <td><?php echo $instock ?></td>
          <td><?php echo $instock_sold ?></td>
          <td><?php echo $status ?></td>
          <td> <a href='index.php?edit_product=<?php echo $product_id ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a> </td>
          <td> <a href='index.php?delete_product=<?php echo $product_id ?>' class='text-light'><i class='fa-solid fa-trash'></i></a> </td>
        </tr>
      <?php } ?>

  </tbody>
</table>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">

  $(document).ready(function () {
    // Function to fetch and display filtered products
    function fetchFilteredProducts() {
      let selectedBrands = [];
      let selectedCategories = [];

      // Collect selected filters
      $('.filter-brand:checked').each(function () {
        selectedBrands.push($(this).val());
      });

      $('.filter-category:checked').each(function () {
        selectedCategories.push($(this).val());
      });

      // Determine whether we are filtering by category or brand
      let filterType = 'admin';
      let filterValue = 1;

      // Send AJAX request
      $.ajax({
        url: '../functions/filter_products.php',
        method: 'POST',
        data: {
          filterType: filterType,
          filterValue: filterValue,
          categories: selectedCategories,
          brands: selectedBrands
        },
        success: function (response) {
          $('#product-container-table').html(response);
        },
        error: function () {
          alert('Failed to fetch products. Please try again.');
        }
      });
    }

    // Trigger filtering when a filter is changed
    $('.filter-brand, .filter-category').on('change', function () {
      fetchFilteredProducts();
    });
  });
</script>
