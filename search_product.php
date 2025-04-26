<?php
  // Retrieve the search term from the URL query parameter
  $searchTerm = '';
  if (isset($_GET['query']) && !empty($_GET['query'])) {
    // Sanitize the search term for use in SQL
    $searchTerm = mysqli_real_escape_string($conn, $_GET['query']);
    // Append an asterisk for wildcard searching in BOOLEAN mode
    $searchTerm .= '*';
  }

  // Build a unified query to search across products, brands, categories, and departments
  if (!empty($searchTerm)) {
    $query = "
            SELECT DISTINCT
              p.product_id,
              p.product_title,
              p.product_description,
              p.product_image1,
              p.price AS product_price,
              p.average_rating,
              p.demographic,
              p.product_size,
              p.price,
              b.brand_title,
              c.category_title,
              c.department_id,
              d.department_title,
              COALESCE(
              (p.price - (p.price * (fs.discount_value / 100))),
              pp.display_price,
              p.price
              ) AS final_price,
              COALESCE(fs.discount_value, pp.discount_value, 0) AS final_discount_value
              FROM products p
              LEFT JOIN brands b ON p.brand_title = b.brand_id
              LEFT JOIN categories c ON p.category_title = c.category_id
              LEFT JOIN department d ON c.department_id = d.department_id
              LEFT JOIN product_promotions pp ON p.product_id = pp.product_id
              LEFT JOIN (
              SELECT applicable_id AS product_id, discount_value
              FROM flash_sales
              WHERE status = 'active'
              ) fs ON p.product_id = fs.product_id
            WHERE
            MATCH(p.product_title, p.product_description, p.demographic)
            AGAINST ('+$searchTerm' IN BOOLEAN MODE)
            OR MATCH(b.brand_title) AGAINST ('+$searchTerm' IN BOOLEAN MODE)
            OR MATCH(c.category_title) AGAINST ('+$searchTerm' IN BOOLEAN MODE)
            OR MATCH(d.department_title) AGAINST ('+$searchTerm' IN BOOLEAN MODE)
            ORDER BY p.product_title ASC;
          ";

    $result = mysqli_query($conn, $query);
    $searchResults = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $searchResults[] = $row;
        }
    }
  } else {
    // Optionally, you can load a default product listing if no query is provided.
    $searchResults = []; // or query your default product list here.
  }
  // var_dump($searchResults);
  // die;

  if (!empty($searchResults)) {
    foreach ($searchResults as $product) {
      // Extract variables from the current product result
      $product_id  = $product['product_id'];
      $product_title = $product['product_title'];
      $product_description = $product['product_description'];
      $product_image1 = $product['product_image1'];
      $original_price = $product['price'];
      $product_price = $product['final_price'];
      $discount_value = $product['final_discount_value'];
      $average_rating = $product['average_rating'];
      $rating_width = ($average_rating * 80) / 5;
      $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
      $result_reviews = mysqli_query($conn, $get_total_reviews);
      $total_reviews = mysqli_num_rows($result_reviews);
      // For products, you might fetch additional details from the products table,
      // but for now, we'll assume this query returns the necessary fields.
      // You can adjust based on your actual product details retrieval.
      // We'll assume $product_image1, $product_description, $product_price, etc. are available if the type is 'product'.
      // For demonstration, only output title and type:
      ?>
      <div class="item">
        <div class="media">
          <div class="thumbnail object-cover">
            <a href="javascript:void(0);">
              <img src="product_imgs/<?php echo $product_image1 ?>" alt="">
            </a>
          </div>
          <div class="hoverable">
            <ul>
              <li class='active wishlist-icon' data-product-id='<?php echo $product_id ?>'>
                <a href='javascript:void(0);'><i class='ri-heart-fill'></i></a>
              </li>
              <li><a href="product-details.php?product=<?php echo $product_id ?>#review-form"><i class="ri-eye-line"></i></a></li>
              <li><a href="javascript:void(0);"><i class="ri-shuffle-line"></i></a></li>
            </ul>
          </div>
          <div class="discount circle flexcenter"><span><?php echo ($discount_value == NULL) ? '' : $discount_value . '%' ?></span></div>
        </div>
        <div class="content">
          <div class="rating">
            <div class='stars' style="width: <?php echo $rating_width . 'px;' ?>"></div>
            <span class='mini-text'> <?php echo $total_reviews ?> review(s)</span>
          </div>
          <h3 class="main-links"><a href="product-details.php?product=<?php echo $product_id ?>"><?php echo $product_title ?></a></h3>
          <div class="price">
            <span class="current">Kshs. <?php echo $product_price ?></span>
            <span class="normal mini-text"><?php echo ($discount_value == NULL) ? '' : 'Kshs. '.$original_price ?></span>
          </div>
          <div class='add_to_cart price'>
            <input type='button'
              class='primary-button add-to-cart-btn'
              data-product-id='<?php echo $product_id ?>'
              value='Add to Cart'
            >
          </div>
          <!-- desc -->
          <div class="footer" style='margin-top: 40px;'>
            <ul class="mini-text">
              <li><?php echo $product_description ?></li>
            </ul>
          </div>
        </div>
      </div>
      <?php
    }
  } else {
      echo "<p>No products found matching your search criteria.</p>";
  }
 ?>
