<?php
  session_start();

  include('includes/connect.php');

  include('functions/common_functions.php');

  $user_id = NULL;
  $username = NULL;
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_userId = "SELECT * FROM `user_table` WHERE user_name='$username'";
    $result_query = mysqli_query($conn, $get_userId);
    $row = mysqli_fetch_assoc($result_query);
    $user_id = $row['user_id'];
  }


 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <!-- sweetalert js link -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="site page-single" id="page">

      <aside class="site-off desktop-hide">
        <div class="off-canvas">
          <div class="canvas-head flexitem">
            <div class="logo"><a href="#"><span class="circle"></span>.JamboShop</a></div>
            <a href="#" class="t-close flexcenter"><i class="ri-close-line"></i></a>
          </div>
          <div class="departments"></div>
          <nav></nav>
          <div class="thetop-nav"></div>
        </div>
      </aside>

      <!-- HEADER -->
      <?php include('includes/header_nav.php') ?>

      <!-- MARGIN -->
      <main>

        <div class="single-product">
          <div class="container">
            <div class="wrapper">
              <?php
                if (isset($_GET['product'])) {
                  $productID = $_GET['product'];
                  $get_products = "SELECT
                                    p.product_id,
                                    p.product_title,
                                    p.product_description,
                                    p.product_image1,
                                    p.instock,
                                    p.category_title,
                                    p.brand_title,
                                    p.demographic,
                                    p.product_size,
                                    p.average_rating,
                                    COALESCE(
                                      (p.price - (p.price * (fs.discount_value / 100))), -- Use flash sale price if active
                                      pp.display_price -- Else use promotion price
                                    ) AS final_price,
                                    -- (pp.original_price - (pp.original_price * (fs.discount_value / 100))) AS flash_price,
                                    pp.display_price,
                                    COALESCE(
                                      fs.discount_value, -- Use flash sale price if active
                                      pp.discount_value -- Else use promotion price
                                    ) AS final_discount_value,
                                    pp.original_price,
                                    pp.promotion_id,
                                    c.category_id,
                                    c.category_title,
                                    c.department_id,
                                    d.department_id,
                                    d.department_title,
                                    b.brand_id,
                                    b.brand_title
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
                                  LEFT JOIN
                                    categories c
                                  ON
                                      p.category_title = c.category_id
                                  LEFT JOIN
                                    brands b
                                  ON
                                      p.brand_title = b.brand_id
                                  LEFT JOIN
                                    department d
                                  ON
                                      c.department_id = d.department_id
                                  WHERE p.product_id='$productID'";
                  $result_products = mysqli_query($conn, $get_products);
                  $row = mysqli_fetch_assoc($result_products);
                  $product_title = $row['product_title'];
                  $product_description = $row['product_description'];
                  $product_size = $row['product_size'];
                  $final_price = $row['final_price'];
                  $original_price = $row['original_price'];
                  $discount_value = $row['final_discount_value'];
                  $instock = $row['instock'];
                  $product_image1 = $row['product_image1'];
                  $average_rating = $row['average_rating'];
                }
               ?>
              <!-- breadcrumb -->
              <div class="breadcrumb">
                <ul class="flexitem">
                  <li><a href="index.php">Home</a></li>
                  <li><a href="products.php?category=<?php echo $row['category_id'] ?>"><?php echo $row['category_title'] ?></a></li>
                  <li><a href=""><?php echo $row['product_title'] ?></a></li>
                </ul>
              </div>

              <div class="column">
                <div class="products one">
                  <div class="flexwrap">
                    <div class="row">
                      <div class="item is_sticky">
                        <div class="price">
                          <?php echo ($discount_value !== NULL) ? "<span class='discount'>$discount_value% <br>OFF</span>" : '' ?>
                        </div>

                        <div class="big-image">
                          <div class="big-image-wrapper swiper-wrapper">
                            <div class="image-show swiper-slide">
                              <a data-fslightbox href="product_imgs/<?php echo $product_image1 ?>"><img src="product_imgs/<?php echo $product_image1 ?>" alt=""></a>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="row">
                      <div class="item">
                        <h1><?php echo $product_title ?></h1>
                        <div class="content">
                          <div class="rating">
                            <?php
                              $width = ($average_rating * 80) / 5;
                             ?>
                            <div class="stars" style="width: <?php echo ($average_rating == 0) ? $average_rating : $width ?>px"></div>
                            <?php
                              $hasReviewed = false;
                              $select_reviews = "SELECT
                                                  r.review_id,
                                                  r.product_id,
                                                  r.user_id,
                                                  r.rating,
                                                  r.review_summary,
                                                  r.review_text,
                                                  r.created_at,
                                                  u.user_id,
                                                  u.user_name
                                                FROM reviews r
                                                LEFT JOIN
                                                  user_table u
                                                ON
                                                  r.user_id = u.user_id
                                                WHERE
                                                  r.product_id = '$productID'
                                                ORDER BY
                                                  CASE
                                                    WHEN r.user_id = '$user_id' THEN 0
                                                    ELSE 1
                                                END,
                                                  review_id DESC LIMIT 3";
                              $result_reviews = mysqli_query($conn, $select_reviews);
                              $num_of_reviews = mysqli_num_rows($result_reviews);
                             ?>
                            <a href="javascript:void(0)" class="mini-text"><?php echo $num_of_reviews ?> reviews</a>
                            <a href="#review-form" class="add-review mini-text">Add Your Review</a>
                          </div>
                          <div class="stock-sku">
                            <span class="available">In stock</span>
                            <span class="sku mini-text">SKU-<?php echo $instock ?></span>
                          </div>
                          <div class="price">
                            <span class="current">Kshs.<?php echo $final_price ?></span>
                            <span class="normal"><?php echo ($discount_value == NULL) ? '' : 'Kshs. '.$original_price ?></span>
                          </div>
                          <div class="actions">
                            <div class="button-cart">
                              <button
                                class='primary-button add-to-cart-btn'
                                data-product-id='<?php echo $row['product_id'] ?>'>
                                    Add to cart
                              </button>
                            </div>
                            <div class="wish-share">
                              <ul class="flexitem second-links">
                                <li class='active wishlist-icon' data-product-id='<?php echo $row['product_id'] ?>'><a href="javascript:void(0);">
                                  <span class="icon-large"><i class='ri-heart-fill'></i></span>
                                  <span>Wishlist</span>
                                </a></li>
                                <!-- <li><a href="#">
                                  <span class="icon-large"><i class="ri-share-line"></i></span>
                                  <span>Share</span>
                                </a></li> -->
                              </ul>
                            </div>
                          </div>
                          <div class="description collapse">
                            <ul>
                            <li class="has-child">
                                <a href="#0" class="icon-small">Product Info</a>
                                <ul class="content">
                                  <li><span>Department</span> <span><?php echo $row['department_title'] ?></span></li>
                                  <li><span>Category</span> <span><?php echo $row['category_title'] ?></span></li>
                                  <li><span>Brand</span> <span><?php echo $row['brand_title'] ?></span></li>
                                  <li><span>Size</span> <span><?php echo $product_size ?></span></li>
                                  <li><span>Gender</span> <span><?php echo $row['demographic'] ?></span></li>
                                </ul>
                              </li>
                              <li class="has-child">
                                <a href="#0" class="icon-small">Product Description</a>
                                <div class="content">
                                  <p><?php echo $product_description ?></p>
                                </div>
                              </li>
                              <li class="has-child expand">
                                <a href="#" class="icon-small">Reviews<span class="mini-text"><?php echo $num_of_reviews ?></span></a>
                                <div class="content">
                                  <div class="reviews">
                                    <h4>Customers' Reviews</h4>
                                    <div class="reviews-block">
                                      <div class="review-block-head">
                                        <div class="flexitem">
                                          <?php
                                            $get_total_ratings = "SELECT SUM(rating) AS total_rating
                                                                  FROM reviews
                                                                  WHERE product_id = '$productID';";
                                            $result_rating = mysqli_query($conn, $get_total_ratings);
                                            $row_rating = mysqli_fetch_assoc($result_rating);
                                           ?>
                                          <span class="rate-sum"><?php echo floatval($row_rating['total_rating']) ?></span>
                                          <span><?php echo $num_of_reviews ?> Review(s)</span>
                                        </div>
                                        <a href="#review-form" class="secondary-button">Write review</a>
                                      </div>
                                      <div class="review-block-body">
                                        <ul>
                                          <?php
                                            if ($num_of_reviews > 0) {
                                              $width = 0;
                                              while($row_review = mysqli_fetch_assoc($result_reviews)){
                                                $user_rating = $row_review['rating'];
                                                if ($row_review['user_id'] == $user_id) {
                                                  $hasReviewed = true;
                                                }
                                                // adjust rating width
                                                $width = ($user_rating * 80) / 5;
                                                 ?>
                                                <li class="item">
                                                  <div class="review-form">
                                                    <p class="person"><?php echo ($row_review['user_name'] == $username) ? 'Your Review' : '@' . $row_review['user_name'] ?></p>
                                                    <p class="mini-text">On <?php echo $row_review['created_at'] ?></p>
                                                  </div>
                                                  <div class="review-rating rating">
                                                    <div class="stars" style="width: <?php echo $width ?>px;"></div>
                                                  </div>
                                                  <div class="review-title">
                                                    <p><?php echo $row_review['review_summary'] ?></p>
                                                  </div>
                                                  <div class="review-text">
                                                    <p><?php echo $row_review['review_text'] ?></p>
                                                  </div>
                                                </li>
                                            <?php }} ?>
                                        </ul>
                                        <div class="second-links">
                                          <a href="#" class="view-all">View all reviews <i class="ri-arrow-right-line"></i></a>
                                        </div>
                                      </div>
                                      <div id="review-form" class="review-form">
                                        <form class="" action="" method="post">
                                          <h4><?php echo ($hasReviewed) ? "Update Review" : "Write a review"; ?></h4>
                                          <?php
                                            if ($hasReviewed AND isset($_SESSION['username'])) {
                                              $user_review = "SELECT * FROM reviews WHERE
                                                                product_id ='$productID'  AND user_id='$user_id'";
                                              $result_user_review = mysqli_query($conn, $user_review);
                                              $row_user_review = mysqli_fetch_assoc($result_user_review);
                                              $review_id = $row_user_review['review_id'];
                                              $rating = (int) $row_user_review['rating'];
                                              $review_summary = $row_user_review['review_summary'];
                                              $review_text = $row_user_review['review_text'];
                                              ?>
                                              <div class="rating">
                                                <p>Your Rate on this product</p>
                                                <div class="rate-this">
                                                  <?php
                                                    for ($i=5; $i >= 1 ; $i--) {
                                                      // Check if the current star should be checked based on the $rating value
                                                      $isChecked = $i == $rating ? 'checked' : '';
                                                      ?>
                                                      <input type="radio"
                                                        name="rating"
                                                        id="star<?php echo $i ?>"
                                                        value="<?php echo $i ?>"
                                                        <?php echo $isChecked ?>
                                                      >
                                                      <label for="star<?php echo $i ?>"><i class="ri-star-fill"></i></label>
                                                  <?php  } ?>
                                                </div>
                                              </div>
                                              <form action="" method="post">
                                                <p>
                                                  <label>Summary</label>
                                                  <input type="text" name="review_summary" value="<?php echo $review_summary ?>" autocomplete="off" required>
                                                </p>
                                                <p>
                                                  <label>Review</label>
                                                  <textarea name="review_body" cols="30" rows="10" id="" required><?php echo $review_text ?></textarea>
                                                </p>
                                                <p> <input type="submit" class="primary-button" name="update_review" value="Update Review"> </p>
                                              </form>
                                          <?php } elseif (!$hasReviewed) { ?>
                                            <div class="rating">
                                              <p>Rate this product</p>
                                              <div class="rate-this">
                                                <input type="radio" name="rating" id="star5" value="5">
                                                <label for="star5"><i class="ri-star-fill"></i></label>

                                                <input type="radio" name="rating" id="star4" value="4">
                                                <label for="star4"><i class="ri-star-fill"></i></label>

                                                <input type="radio" name="rating" id="star3" value="3">
                                                <label for="star3"><i class="ri-star-fill"></i></label>

                                                <input type="radio" name="rating" id="star2" value="2">
                                                <label for="star2"><i class="ri-star-fill"></i></label>

                                                <input type="radio" name="rating" id="star1" value="1">
                                                <label for="star1"><i class="ri-star-fill"></i></label>
                                              </div>
                                            </div>
                                            <form action="">
                                              <p>
                                                <label>Summary</label>
                                                <input type="text" name="review_summary" autocomplete="off" required>
                                              </p>
                                              <p>
                                                <label>Review</label>
                                                <textarea name="review_body" cols="30" rows="10" id="" required></textarea>
                                              </p>
                                              <p> <input type="submit" class="primary-button" name="submit_review" value="Submit Review"> </p>
                                            </form>
                                          <?php } ?>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Featured products -->


      </main>

      <!-- FOOTER & MENU-BUTTON & SEARCH-BUTTON -->
      <?php include('includes/footer-menu-button.php') ?>

      <!-- overlay -->
      <div class="overlay">

      </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.3.1/index.js"></script>
    <script src="script/script.js" charset="utf-8"></script>
  </body>
</html>

<?php
  if (isset($_POST['submit_review'])) {

    // logged in users gets to review the product
    if (!isset($_SESSION['username'])) {
      echo "<script>window.open('users_area/user_login.php','_self');</script>";
      exit();
    }

    $review_summary = mysqli_real_escape_string($conn, $_POST['review_summary']);
    $review_body = mysqli_real_escape_string($conn, $_POST['review_body']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 1;
    // var_dump($review_summary, $review_body, $rating);
    // die;

    $query = "INSERT INTO
                reviews (product_id, user_id, rating, review_summary, review_text, created_at)
              VALUES
                ('$productID', '$user_id', '$rating', '$review_summary', '$review_body', NOW())";
    $result = mysqli_query($conn, $query);
    if ($result) {
      $update_avg_rating = "UPDATE products
                            SET average_rating = (SELECT AVG(rating) FROM reviews WHERE product_id = '$productID')
                            WHERE product_id = '$productID'";
      mysqli_query($conn, $update_avg_rating);

      ?>
      <script type="text/javascript">
        Swal.fire({
          title: "Success!",
          text: "Your Feedback is greatly appreciated!",
          icon: "success"
        }).then(() => {
          window.open('product-details.php?product=<?php echo $productID ?>','_self');
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

  if (isset($_POST['update_review'])) {
    // logged in users gets to review the product
    if (!isset($_SESSION['username'])) {
      echo "<script>window.open('users_area/user_login.php','_self');</script>";
      exit();
    }

    $review_summary = mysqli_real_escape_string($conn, $_POST['review_summary']);
    $review_body = mysqli_real_escape_string($conn, $_POST['review_body']);
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 1;

    $update_query = "UPDATE `reviews`
                     SET
                      rating='$rating', review_summary='$review_summary', review_text='$review_body', created_at=NOW()
                     WHERE review_id=$review_id";
    $exe_query = mysqli_query($conn, $update_query);
    if ($exe_query) {
      $update_avg_rating = "UPDATE products
                            SET average_rating = (SELECT AVG(rating) FROM reviews WHERE product_id = '$productID')
                            WHERE product_id = '$productID'";
      mysqli_query($conn, $update_avg_rating);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Your Feedback is greatly appreciated!",
        }).then(() => {
          window.open('product-details.php?product=<?php echo $productID ?>','_self');
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
