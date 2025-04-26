<?php
session_start();
include('../includes/connect.php');

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  if ($mode === 'mini-wishlist') {
      echo "<h1>Please log in to view your wishlist.</h1>";
  } else {
      echo json_encode(['success' => false, 'message' => 'Please log in to view your wishlist.']);
  }
  exit;
}

// Get the user details
$username = $_SESSION['username'];
$get_userId = "SELECT user_id FROM `user_table` WHERE user_name = '$username'";
$result_query = mysqli_query($conn, $get_userId);
$row = mysqli_fetch_assoc($result_query);
$user_id = $row['user_id'];

if ($mode == 'mini-wishlist') {
  // Fetch wishlist items
  $get_wishlist = "SELECT * FROM `wishlist` WHERE user_id='$user_id'";
  $result_wishlist = mysqli_query($conn, $get_wishlist);
  $count_wishlisted_items = mysqli_num_rows($result_wishlist);
  ?>
  <a href="javascript:void(0);">
      <div class="icon-large"><i class="ri-heart-line"></i></div>
      <div class="fly-item"><span class="item-number"><?php echo $count_wishlisted_items; ?></span></div>
  </a>

  <div class="mini-wishlist">
      <div class="content">
          <div class="cart-head">
              <?php echo $count_wishlisted_items; ?> item(s) in wishlist
          </div>
          <div class="cart-body">
              <ul class="products mini">
                  <?php
                  if ($count_wishlisted_items == 0) {
                      echo "<h1>No item in wishlist</h1>";
                  } else {
                      while ($row = mysqli_fetch_array($result_wishlist)) {
                          $product_id = $row['product_id'];
                          $fetch_product_details = "SELECT
                              p.product_title,
                              p.product_image1,
                              p.price,
                              COALESCE((p.price - (p.price * (fs.discount_value / 100))), pp.display_price) AS final_price
                          FROM products p
                          LEFT JOIN product_promotions pp ON p.product_id = pp.product_id
                          LEFT JOIN (SELECT applicable_id AS product_id, discount_value FROM flash_sales WHERE status = 'active') fs
                          ON p.product_id = fs.product_id
                          WHERE p.product_id = '$product_id'";

                          $result_product = mysqli_query($conn, $fetch_product_details);
                          $row_product = mysqli_fetch_array($result_product);
                          $product_title = $row_product['product_title'];
                          $product_image1 = $row_product['product_image1'];
                          $final_price = $row_product['final_price'];
                          ?>
                          <li class="item wishlist-item-removed">
                              <div class="thumbnail object-cover">
                                  <a href="javascript:void(0);"><img src="product_imgs/<?php echo $product_image1 ?>" alt=""></a>
                              </div>
                              <div class="item-content">
                                  <p><a href="javascript:void(0);"><?php echo $product_title ?></a></p>
                                  <span class="price">
                                      <span>Kshs.<?php echo $final_price ?></span>
                                  </span>
                              </div>
                              <a href="javascript:void(0);" class="remove-wishlist" data-product-id="<?php echo $product_id; ?>">
                                  <i class="ri-close-line"></i>
                              </a>
                          </li>
                          <?php
                      }
                  }
                  ?>
              </ul>
          </div>
      </div>
  </div>
  <?php
  exit; // Ensure no JSON output is returned
}

// Continue with JSON response for other cases
$response = ['success' => false];

if ($user_id) {
  $query = "SELECT w.product_id, p.product_title, p.product_image1, p.price, p.average_rating
            FROM wishlist w
            INNER JOIN products p ON w.product_id = p.product_id
            WHERE w.user_id = '$user_id'";

  $result = mysqli_query($conn, $query);

  if ($result) {
      $wishlist_items = [];
      while ($row = mysqli_fetch_assoc($result)) {
          $wishlist_items[] = [
              'product_id' => $row['product_id'],
              'product_title' => $row['product_title'],
              'product_image' => $row['product_image1'],
              'price' => $row['price'],
              'average_rating' => $row['average_rating']
          ];
      }

      $response['success'] = true;
      $response['wishlist'] = $wishlist_items;
  } else {
      $response['message'] = 'Unable to fetch wishlist items.';
  }
} else {
  $response['message'] = 'User not found.';
}

echo json_encode($response);
?>
