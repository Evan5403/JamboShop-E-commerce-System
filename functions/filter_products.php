<?php
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filterType = $_POST['filterType']; // 'category' or 'brand'
    $filterValue = intval($_POST['filterValue']);
    $brands = isset($_POST['brands']) && is_array($_POST['brands']) ? $_POST['brands'] : [];
    $categories = isset($_POST['categories']) && is_array($_POST['categories']) ? $_POST['categories'] : [];
    $genders = isset($_POST['genders']) && is_array($_POST['genders']) ? $_POST['genders'] : [];
    $sizes = isset($_POST['sizes']) && is_array($_POST['sizes']) ? $_POST['sizes'] : [];

    // Build query with filters
    $query = "SELECT
                p.product_id,
                p.product_title,
                p.product_image1,
                p.product_description,
                p.instock,
                p.price,
                p.instock_sold,
                p.status,
                p.average_rating,
                COALESCE(
                  (p.price - (p.price * (fs.discount_value / 100))),
                  pp.display_price
                ) AS final_price,
                COALESCE(
                  fs.discount_value, -- Use flash sale price if active
                  pp.discount_value -- Else use promotion price
                ) AS final_discount_value,
                pp.promotion_id,
                pp.original_price
              FROM
                products p
              LEFT JOIN
                product_promotions pp
              ON
                p.product_id = pp.product_id
              LEFT JOIN
                (
                  SELECT
                    applicable_id AS product_id,
                    discount_value
                  FROM
                    flash_sales
                  WHERE
                    status = 'active'
                ) fs
              ON
                  p.product_id = fs.product_id
              WHERE ";

    if ($filterType === 'category') {
      $query .= "category_title = $filterValue";
    } elseif ($filterType === 'brand') {
      $query .= "brand_title = $filterValue";
    } elseif ($filterType === 'admin') {
      $query .= "$filterValue";
    }

    if (!empty($categories)) {
      $category_placeholders = implode("','", array_map(function ($category) use ($conn) {
        return mysqli_real_escape_string($conn, $category);
      }, $categories));
      $query .= " AND category_title IN (SELECT category_id FROM categories WHERE category_title IN ('$category_placeholders'))";
    }

    if (!empty($brands)) {
      $brand_placeholders = implode("','", array_map(function ($brand) use ($conn) {
      return mysqli_real_escape_string($conn, $brand);
      }, $brands));
      $query .= " AND brand_title IN (SELECT brand_id FROM brands WHERE brand_title IN ('$brand_placeholders'))";
    }

    if (!empty($genders)) {
      $gender_placeholders = implode("','", array_map(function ($genders) use ($conn) {
      return mysqli_real_escape_string($conn, $genders);
      }, $genders));
      $query .= " AND demographic IN ('$gender_placeholders')";
    }

    if (!empty($sizes)) {
      $size_placeholders = implode("','", array_map(function ($sizes) use ($conn) {
      return mysqli_real_escape_string($conn, $sizes);
      }, $sizes));
      $query .= " AND product_size IN ('$size_placeholders')";
    }
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0 AND $filterType == 'admin') {
      while ($product = mysqli_fetch_assoc($result)){ ?>
        <tr class='text-center'>
          <td><?php echo $product['product_id'] ?></td>
          <td><img src='../product_imgs/<?php echo $product['product_image1'] ?>' class='product_img' alt='product_img'></td>
          <td><?php echo $product['product_title'] ?></td>
          <td><?php echo $product['product_description'] ?></td>
          <td>Kshs. <?php echo $product['price'] ?></td>
          <td><?php echo $product['instock'] ?></td>
          <td><?php echo $product['instock_sold'] ?></td>
          <td><?php echo $product['status'] ?></td>
          <td> <a href='index.php?edit_product=<?php echo $product['product_id'] ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a> </td>
          <td> <a href='index.php?delete_product=<?php echo $product['product_id'] ?>' class='text-light'><i class='fa-solid fa-trash'></i></a> </td>
        </tr>
    <?php  }
    } elseif (mysqli_num_rows($result) > 0) {
      while ($product = mysqli_fetch_assoc($result)) {
        $product_id = $product['product_id'];
        $original_price = $product['original_price'];
        $product_price = $product['final_price'];
        $discount_value = $product['final_discount_value'];
        $average_rating = $product['average_rating'];
        $rating_width = ($average_rating * 80) / 5;
        $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
        $result_reviews = mysqli_query($conn, $get_total_reviews);
        $total_reviews = mysqli_num_rows($result_reviews);
        ?>
        <div class="item">
          <div class="media">
            <div class="thumbnail object-cover">
              <a href="javascript:void(0);">
                <img src="product_imgs/<?php echo $product['product_image1'] ?>" alt="">
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
            <h3 class="main-links">
              <a href="product-details.php?product=<?php echo $product_id ?>"><?php echo $product['product_title'] ?></a>
            </h3>
            <div class="price">
              <span class="current">Kshs. <?php echo $product_price ?></span>
              <span class="normal mini-text"><?php echo ($discount_value == NULL) ? '' : 'Kshs. '.$original_price ?></span>
            </div>
            <div class='add_to_cart price'>
              <input type='button'
                class='primary-button add-to-cart-btn'
                data-product-id='<?php echo $product['product_id'] ?>'
                value='Add to Cart'
              >
            </div>
            <!-- desc -->
            <div class="footer" style='margin-top: 40px;'>
              <ul class="mini-text">
                <li><?php echo $product['product_description'] ?></li>
              </ul>
            </div>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<td class='text-light'>No products found for the selected filters.</td>";
    }

  }
?>
