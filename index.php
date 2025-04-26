<?php
  session_start();

  include('includes/connect.php');

  include('functions/common_functions.php');


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
    <div class="site page-cart home-page" id="page">

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
        <!-- slider -->
        <div class="slider">
          <div class="container">
            <div class="wrapper">
              <div class="myslider swiper">
                <div class="swiper-wrapper">
                  <?php
                    $get_slides = "SELECT * FROM slides WHERE status = 'active' ORDER BY RAND()";
                    $result_slides = mysqli_query($conn, $get_slides);
                    if ($result_slides) {
                      while ($row = mysqli_fetch_assoc($result_slides)) { ?>
                        <div class="swiper-slide">
                          <div class="item">
                            <div class="image object-cover" id="dynamic-image">
                              <img src="product_imgs/<?php echo $row['image_cover'] ?>" alt="">
                            </div>
                            <div class="text-content flexcol" id="dynamic-text">
                              <h4><?php echo $row['header_title'] ?></h4>
                              <h2><span><?php echo $row['mini_title'] ?></span><br><span><?php echo $row['description'] ?></span></h2>
                              <a href="products.php?category=<?php echo $row['category_id'] ?>" class="primary-button">Shop Now</a>
                            </div>
                          </div>
                        </div>
                    <?php }} ?>
                </div>
                <div class="swiper-pagination"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- brands -->
        <!-- <div class="brands">
          <div class="container">
            <div class="wrapper flexitem">
              <div class="item"><a href="#">
                <img src="imgs/Adidas_Logo.svg" alt=""></a>
              </div>
              <div class="item"><a href="#">
                <img src="imgs/zara-logo.svg" alt=""></a>
              </div>
              <div class="item"><a href="#">
                <img src="imgs/nike-logo.webp" alt=""></a>
              </div>
              <div class="item"><a href="#">
                <img src="imgs/birkenstock-logo.png" alt=""></a>
              </div>
            </div>
          </div>
        </div> -->

        <!-- flashsales and promotions products -->
        <div class="trending" id="trending">
          <div class="container">
            <div class="wrapper">
              <div class="sectop flexitem">
                <h2><span class="circle"></span> <span>Offers & Promotions</span> </h2>
              </div>
              <div class="column">
                <div class="flexwrap">
                  <div class="row products big">
                    <?php
                      $get_flashsale = "SELECT
                                          flash_sales.*,
                                          products.product_id AS pid,
                                          products.product_title AS name,
                                          products.product_image1 AS image,
                                          products.price AS price,
                                          products.average_rating AS average_rating
                                        FROM
                                          flash_sales
                                        INNER JOIN
                                          products
                                        ON
                                          flash_sales.applicable_id = products.product_id
                                        ORDER BY
                                          flash_sales.status = 'active' DESC,
                                          flash_sales.end_date DESC
                                        LIMIT 1";
                      $execute_sql = mysqli_query($conn, $get_flashsale);

                      if ($row_flashsale = mysqli_fetch_assoc($execute_sql)) {
                        $product_id = $row_flashsale['pid'];
                        $status = $row_flashsale['status'];
                        $start_date = $row_flashsale['start_date'];
                        $end_date = $row_flashsale['end_date'];
                        $qty_remaining = $row_flashsale['qty_remaining'];
                        $flashsale_price = floor($row_flashsale['price'] - (($row_flashsale['discount_value'] * $row_flashsale['price']) / 100));
                        $width = ($row_flashsale['qty_sold'] /  $row_flashsale['stock_limit']) * 100;
                        $average_rating = $row_flashsale['average_rating'];
                        $rating_width = ($average_rating * 80) / 5;
                        $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
                        $result_reviews = mysqli_query($conn, $get_total_reviews);
                        $total_reviews = mysqli_num_rows($result_reviews);
                       ?>
                        <div class="item">
                          <div class="offer">
                            <?php
                              if ($status !== 'active') {
                                ?>
                                Flash Sale Ended!
                              <?php } else {?>
                                <p>Offer ends at</p>
                                <ul class="flexcenter" id="countdown">
                                  <li id="days">0</li>
                                  <li id="hours">0</li>
                                  <li id="minutes">0</li>
                                  <li id="seconds">0</li>
                                </ul>
                            <?php  } ?>
                          </div>
                          <div class="media">
                            <div class="image">
                              <a href="javascript:void(0)">
                                <img src="product_imgs/<?php echo $row_flashsale['image'] ?>" alt="">
                              </a>
                            </div>
                            <div class="hoverable">
                              <ul>
                                <li class='active wishlist-icon' data-product-id='<?php echo $product_id ?>'>
                                  <a href='javascript:void(0);'><i class='ri-heart-fill'></i></a>
                                </li>
                                <li><a href="product-details.php?product=<?php echo $row_flashsale['pid'] ?>#review-form"><i class="ri-eye-line"></i></a></li>
                                <li><a href=""><i class="ri-shuffle-line"></i></a></li>
                              </ul>
                            </div>
                            <div class="discount circle flexcenter"><span><?php echo $row_flashsale['discount_value'] ?>%</span></div>
                          </div>
                          <div class="content">
                            <div class="rating">
                              <div class="stars" style="width: <?php echo $rating_width . 'px;' ?>"></div>
                              <span class="mini-text"> <?php echo $total_reviews ?> review(s)</span>
                            </div>
                            <h3 class="main-links"><a href="javascript:void(0)"><?php echo $row_flashsale['flash_sale_name'] ?></a></h3>
                            <div class="price">
                              <span class="current">Kshs.<?php echo $flashsale_price ?></span>
                              <span class="normal mini-text">Kshs.<?php echo $row_flashsale['price'] ?></span>
                            </div>
                            <div class="stock mini-text">
                              <div class="qty">
                                <span>Stock Limit: <strong class="qty-available"><?php echo $row_flashsale['stock_limit'] ?></strong></span>
                                <span>Sold: <strong class="qty-sold"><?php echo $row_flashsale['qty_sold'] ?></strong></span>
                              </div>
                              <div class="bar">
                                <div class="available" style="width: <?php echo $width ?>%"></div>
                              </div>
                              <div class='add_to_cart price' style="margin-top: 20px;">
                                <?php
                                  if ($status !== 'active') {
                                    ?>
                                    Flash Sale Expired!
                                  <?php } else {?>
                                    <input type='button'
                                      class='primary-button add-to-cart-btn'
                                      data-product-id='<?php echo $row_flashsale['pid'] ?>'
                                      value='Add to Cart'
                                    >
                                <?php  } ?>
                              </div>

                            </div>
                          </div>
                        </div>
                      <?php } else {
                        echo "<h1>Flashsale Unavailable</h1>";
                      } ?>

                  </div>
                  <div class="row products mini">
                    <?php
                      $get_promotions_products = "SELECT
                                          promotions.*,
                                          products.product_id  AS pid,
                                          products.product_title AS name,
                                          products.product_image1 AS image,
                                          products.price AS price,
                                          products.instock AS instock,
                                          products.average_rating AS average_rating
                                        FROM
                                          promotions
                                        INNER JOIN
                                          products
                                        ON
                                          promotions.applicable_id = products.product_id
                                        WHERE
                                          promotions.applicable_to = 'product'
                                        AND
                                          promotions.status = 'active'
                                        AND products.product_id NOT IN (
                                          SELECT applicable_id
                                          FROM flash_sales
                                          WHERE status = 'active'
                                        )";
                      $exe_query = mysqli_query($conn, $get_promotions_products);

                      while ($row = mysqli_fetch_assoc($exe_query)) {
                        $product_id = $row['pid'];
                        $promotion_price = $row['price'] - (($row['discount_value'] * $row['price']) / 100);
                        $instock = $row['instock'];
                        $average_rating = $row['average_rating'];
                        $rating_width = ($average_rating * 80) / 5;
                        $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
                        $result_reviews = mysqli_query($conn, $get_total_reviews);
                        $total_reviews = mysqli_num_rows($result_reviews);
                        ?>
                        <div class="item">
                          <div class="media">
                            <div class="thumbnail object-cover">
                              <a href="javascript:void(0);">
                                <img src="product_imgs/<?php echo $row['image'] ?>" alt="">
                              </a>
                            </div>
                            <div class="hoverable">
                              <ul>
                                <li class='active wishlist-icon' data-product-id='<?php echo $product_id ?>'>
                                  <a href='javascript:void(0);'><i class='ri-heart-fill'></i></a>
                                </li>
                                <li><a href="product-details.php?product=<?php echo $row['pid'] ?>#review-form"><i class="ri-eye-line"></i></a></li>
                                <li><a href=""><i class="ri-shuffle-line"></i></a></li>
                              </ul>
                            </div>
                            <div class="discount circle flexcenter"><span><?php echo $row['discount_value'] ?>%</span></div>
                          </div>
                          <div class="content">
                            <h3 class="main-links"><a href="javascript:void(0)"><?php echo $row['promotion_name'] ?></a></h3>
                            <div class="rating">
                              <div class="stars" style="width: <?php echo $rating_width . 'px;' ?>"></div>
                              <span class="mini-text"> <?php echo $total_reviews ?> review(s)</span>
                            </div>
                            <div class="price">
                              <span class="current">Kshs.<?php echo $promotion_price ?></span>
                              <span class="normal mini-text">Kshs.<?php echo $row['price'] ?></span>
                            </div>
                            <div class="mini-text">
                              <p><?php echo $row['instock'] ?> to be sold</p>
                              <!-- <p>Free Shipping</p> -->
                            </div>
                            <?php
                              if ($instock == 0) {?>
                                <div class=''>
                                  <h4>Out Of Stock!</h4>
                                </div>
                            <?php  } else { ?>
                              <div class='add_to_cart price'>
                                <input type='button'
                                  class='primary-button add-to-cart-btn'
                                  data-product-id='<?php echo $row['pid'] ?>'
                                  value='Add to Cart'
                                  price = <?php echo $promotion_price ?>
                                >
                              </div>
                            <?php } ?>
                          </div>
                        </div>
                    <?php } ?>
                  </div>
                  <div class="row products mini">
                    <?php
                      $get_promotions_categories = "SELECT
                                                      promotions.*,
                                                      products.product_id AS pid,
                                                      products.product_title AS name,
                                                      products.product_image1 AS image,
                                                      products.price AS price,
                                                      products.average_rating AS average_rating,
                                                      products.instock AS instock
                                                    FROM
                                                      promotions
                                                    INNER JOIN
                                                      products
                                                    ON
                                                      promotions.applicable_id = products.category_title
                                                    WHERE
                                                      promotions.applicable_to = 'category'
                                                    AND promotions.status = 'active'
                                                    AND products.product_id NOT IN (
                                                      SELECT applicable_id
                                                      FROM promotions
                                                      WHERE applicable_to = 'product'
                                                      AND status = 'active'
                                                    )
                                                    AND products.product_id NOT IN (
                                                      SELECT applicable_id
                                                      FROM flash_sales
                                                      WHERE status = 'active'
                                                    )";
                      $result_query = mysqli_query($conn, $get_promotions_categories);

                      while ($row = mysqli_fetch_assoc($result_query)) {
                        $product_id = $row['pid'];
                        $promotion_price = $row['price'] - (($row['discount_value'] * $row['price']) / 100);
                        $instock = $row['instock'];
                        $average_rating = $row['average_rating'];
                        $rating_width = ($average_rating * 80) / 5;
                        $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
                        $result_reviews = mysqli_query($conn, $get_total_reviews);
                        $total_reviews = mysqli_num_rows($result_reviews);
                        ?>
                        <div class="item">
                          <div class="media">
                            <div class="thumbnail object-cover">
                              <a href="#">
                                <img src="product_imgs/<?php echo $row['image'] ?>" alt="">
                              </a>
                            </div>
                            <div class="hoverable">
                              <ul>
                                <li class='active wishlist-icon' data-product-id='<?php echo $product_id ?>'>
                                  <a href='javascript:void(0);'><i class='ri-heart-fill'></i></a>
                                </li>
                                <li><a href="product-details.php?product=<?php echo $row['pid'] ?>#review-form"><i class="ri-eye-line"></i></a></li>
                                <li><a href=""><i class="ri-shuffle-line"></i></a></li>
                              </ul>
                            </div>
                              <div class="discount circle flexcenter"><span><?php echo $row['discount_value'] ?>%</span></div>
                            </div>
                          <div class="content">
                            <h3 class="main-links"><a href="javascript:void(0)"><?php echo $row['promotion_name'] ?></a></h3>
                            <div class="rating">
                              <div class="stars" style="width: <?php echo $rating_width . 'px;' ?>"></div>
                              <span class="mini-text"> <?php echo $total_reviews ?> review(s)</span>
                            </div>
                            <div class="price">
                              <span class="current">Kshs.<?php echo $promotion_price ?></span>
                              <span class="normal mini-text">Kshs.<?php echo $row['price'] ?></span>
                            </div>
                            <div class="mini-text">
                              <p><?php echo $row['instock'] ?> to be sold</p>
                              <!-- <p>Free Shipping</p> -->
                            </div>
                            <?php
                              if ($instock == 0) {?>
                                <div class=''>
                                  <h3>Out Of Stock!</h3>
                                </div>
                            <?php  } else { ?>
                              <div class='add_to_cart price'>
                                <input type='button'
                                  class='primary-button add-to-cart-btn'
                                  data-product-id='<?php echo $row['pid'] ?>'
                                  value='Add to Cart'
                                  price = <?php echo $promotion_price ?>
                                >
                              </div>
                            <?php } ?>
                          </div>
                        </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Featured products -->
        <form id="addToCartForm" method="POST">
          <div class="features">
            <div class="container">
              <div class="wrapper">
                <div class="column">
                  <div class="sectop flexitem" id="featuredProducts">
                    <h2><span class="circle"></span><span>Featured Products</span></h2>
                    <div class="second-links">
                      <a href="products.php" class="view-all">View All<i class="ri-arrow-right-line"></i></a>
                    </div>
                  </div>
                  <div class="products main flexwrap">
                    <?php
                      $get_products = "SELECT
                                        p.product_id,
                                        p.product_title,
                                        p.product_description,
                                        p.product_image1,
                                        p.instock,
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
                                        pp.promotion_id
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
                                      ORDER BY
                                      RAND()";
                      $result_products = mysqli_query($conn, $get_products);

                      while ($row = mysqli_fetch_assoc($result_products)) {
                        $product_id  = $row['product_id'];
                        $product_title = $row['product_title'];
                        $product_description = $row['product_description'];
                        $product_image1 = $row['product_image1'];
                        $original_price = $row['original_price'];
                        $discount_value = $row['final_discount_value'];
                        $display_price = $row['final_price'];
                        $instock = $row['instock'];
                        $average_rating = $row['average_rating'];
                        $rating_width = ($average_rating * 80) / 5;
                        $promotion_id = $row['promotion_id'];
                        $get_total_reviews = "SELECT * FROM reviews WHERE product_id=$product_id";
                        $result_reviews = mysqli_query($conn, $get_total_reviews);
                        $total_reviews = mysqli_num_rows($result_reviews);

                        ?>
                        <div class='item'>
                          <div class='media'>
                            <div class='thumbnail object-cover'>
                              <a href='javascript:void(0);'>
                                <img src='product_imgs/<?php echo $product_image1 ?>' alt=''>
                              </a>
                            </div>
                            <div class='hoverable'>
                              <ul>
                                <li class='active wishlist-icon' data-product-id='<?php echo $product_id ?>'>
                                  <a href='javascript:void(0);'><i class='ri-heart-fill'></i></a>
                                </li>
                                <li><a href='product-details.php?product=<?php echo $product_id ?>#review-form'><i class='ri-eye-line'></i></a></li>
                                <li><a href='javascript:void(0);'><i class='ri-shuffle-line'></i></a></li>
                              </ul>
                            </div>
                            <div class='discount circle flexcenter'><span><?php echo ($discount_value == NULL) ? '' : $discount_value . '%' ?></span></div>
                          </div>
                          <div class='content'>
                            <div class='rating'>
                              <div class='stars' style="width: <?php echo $rating_width . 'px;' ?>"></div>
                              <span class='mini-text'> <?php echo $total_reviews ?> review(s)</span>
                            </div>
                            <h3 class='main-links'><a href='product-details.php?product=<?php echo $product_id ?>'><?php echo $product_title ?></a></h3>
                            <div class='price'>
                              <span class='current'>Kshs. <?php echo $display_price ?></span>
                              <span class='normal mini-text'><?php echo ($discount_value == NULL) ? '' : 'Kshs. '.$original_price ?></span>
                            </div>
                            <?php
                              if ($instock == 0) {?>
                                <div class=''>
                                  <h4>Out Of Stock!</h4>
                                </div>
                            <?php  } else { ?>
                              <div class='add_to_cart price'>
                                <input type='button'
                                  class='primary-button add-to-cart-btn'
                                  data-product-id='<?php echo $row['product_id'] ?>'
                                  value='Add to Cart'
                                >
                              </div>
                            <?php } ?>
                            <!-- desc -->
                            <div class='footer' style='margin-top: 40px;'>
                              <ul class='mini-text'>
                                <li><?php echo $product_description ?></li>
                              </ul>
                            </div>
                          </div>
                        </div>
                    <?php }?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>


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

    <script type="text/javascript">

      // Get the end date from PHP
      const endDate = new Date("<?php echo $end_date; ?>").getTime();

      // Update the countdown every second
      const countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const remainingTime = endDate - now;

        // If time is up, stop the countdown
        if (remainingTime <= 0) {
          clearInterval(countdownInterval);
          if (document.getElementById("countdown")) document.getElementById("countdown").innerHTML = "Flash Sale Ended!";
          // document.getElementById("countdown").innerHTML = "Flash Sale Ended!";
          return;
        }

        // Calculate days, hours, minutes, and seconds
        const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
        const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

        const daysElement = document.getElementById("days");
        const hoursElement = document.getElementById("hours");
        const minutesElement = document.getElementById("minutes");
        const secondsElement = document.getElementById("seconds");

        // Update the HTML
        if (daysElement) daysElement.textContent = days;
        if (hoursElement) hoursElement.textContent = hours;
        if (minutesElement) minutesElement.textContent = minutes;
        if (secondsElement) secondsElement.textContent = seconds;
      }, 1000);

      const image = document.getElementById('dynamic-image');
      const text = document.getElementById('dynamic-text');

      // Function to determine luminance and set text color
      function adjustTextColor(image, text) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = image.width;
        canvas.height = image.height;
        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

        // Get a small portion of the image (e.g., the center)
        const imageData = ctx.getImageData(canvas.width / 2, canvas.height / 2, 1, 1).data;
        const [r, g, b] = imageData;

        // Calculate brightness (luminance)
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

        // Set text color based on brightness
        text.style.color = luminance > 0.5 ? 'black' : 'white';
      }

      // Wait for image to load before processing
      image.onload = () => adjustTextColor(image, text);

    </script>
  </body>
</html>
