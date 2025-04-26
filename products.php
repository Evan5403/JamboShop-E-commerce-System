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
    <div class="site page-category" id="page">

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

        <div class="single-category">
          <div class="container">
            <div class="wrapper">
              <div class="column">
                <div class="holder">
                  <?php
                    // GET UNIQUE CATEGORY
                    if (isset($_GET['category'])) {
                      $category_id = intval($_GET['category']);
                      $cat_query = "SELECT * FROM `categories` WHERE category_id =$category_id";
                      $cat_result = mysqli_query($conn, $cat_query);

                      $products_query = "SELECT
                                          p.product_id,
                                          p.product_title,
                                          p.product_image1,
                                          p.product_description,
                                          p.instock,
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
                                         WHERE category_title=$category_id";
                      $products_result = mysqli_query($conn, $products_query);
                      $num_of_rows = mysqli_num_rows($products_result);

                      // Fetch distinct brands with counts
                      $brands_query = "
                        SELECT b.brand_title, COUNT(*) AS brand_count
                        FROM products p
                        LEFT JOIN brands b ON p.brand_title = b.brand_id
                        WHERE p.category_title = $category_id
                        GROUP BY b.brand_title";
                      $brands_result = mysqli_query($conn, $brands_query);

                      // Fetch distinct genders with counts
                      $genders_query = "
                        SELECT p.demographic, COUNT(*) AS gender_count
                        FROM products p
                        WHERE p.category_title = $category_id
                        GROUP BY p.demographic";
                      $genders_result = mysqli_query($conn, $genders_query);

                      // Fetch distinct sizes with counts
                      $sizes_query = "
                        SELECT p.product_size, COUNT(*) AS size_count
                        FROM products p
                        WHERE p.category_title = $category_id
                        GROUP BY p.product_size";
                      $sizes_result = mysqli_query($conn, $sizes_query);
                    }

                    // GET UNIQUE BRANDS
                    if (isset($_GET['brand'])) {
                      $brand_id = intval($_GET['brand']);
                      $brand_query = "SELECT * FROM `brands` WHERE brand_id  =$brand_id ";
                      $brand_result = mysqli_query($conn, $brand_query);

                      // Fetch products for the selected brand
                      $products_query = "SELECT
                                          p.product_id,
                                          p.product_title,
                                          p.product_image1,
                                          p.product_description,
                                          p.instock,
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
                                        WHERE brand_title = $brand_id";
                      $products_result = mysqli_query($conn, $products_query);
                      $num_of_rows = mysqli_num_rows($products_result);

                      // Fetch distinct categories with counts
                      $category_query = "
                        SELECT c.category_title, COUNT(*) AS category_count
                        FROM products p
                        LEFT JOIN categories c ON p.category_title = c.category_id
                        WHERE p.brand_title = $brand_id
                        GROUP BY c.category_title";
                      $categories_result = mysqli_query($conn, $category_query);

                      // Fetch distinct genders with counts
                      $genders_query = "
                        SELECT p.demographic, COUNT(*) AS gender_count
                        FROM products p
                        WHERE p.brand_title = $brand_id
                        GROUP BY p.demographic";
                      $genders_result = mysqli_query($conn, $genders_query);

                      // Fetch distinct sizes with counts
                      $sizes_query = "
                        SELECT p.product_size, COUNT(*) AS size_count
                        FROM products p
                        WHERE p.brand_title = $brand_id
                        GROUP BY p.product_size";
                      $sizes_result = mysqli_query($conn, $sizes_query);
                    }
                  ?>

                  <!-- FILTER SECTION -->
                  <div class="row sidebar">
                    <div class="filter">
                      <div class="filter-block">
                        <?php
                          echo isset($_GET['category']) ? "<h4>Brands</h4>" : "<h4>Categories</h4>";
                        ?>

                        <ul>
                          <!-- UNIQUE CATEGORIES -->
                          <?php
                          if (isset($_GET['category'])) {
                            while ($brand = mysqli_fetch_assoc($brands_result)) {
                              ?>
                              <li>
                                <input type="checkbox" name="checkbox" class="filter-brand" id="<?php echo $brand['brand_title'] ?>" value="<?php echo $brand['brand_title'] ?>">
                                <label for="<?php echo $brand['brand_title'] ?>">
                                  <span class="checked"></span>
                                  <span><?php echo $brand['brand_title']?></span>
                                </label>
                                <span class="count"><?php echo $brand['brand_count'] ?></span>
                              </li>
                          <?php }} ?>
                          <!-- UNIQUE BRANDS -->
                          <?php
                          if (isset($_GET['brand'])) {
                            while ($category = mysqli_fetch_assoc($categories_result)) {
                              ?>
                              <li>
                                <input type="checkbox" name="checkbox" class="filter-category" id="<?php echo $category['category_title'] ?>" value="<?php echo $category['category_title'] ?>">
                                <label for="<?php echo $category['category_title'] ?>">
                                  <span class="checked"></span>
                                  <span><?php echo $category['category_title']?></span>
                                </label>
                                <span class="count"><?php echo $category['category_count'] ?></span>
                              </li>
                          <?php }} ?>
                        </ul>
                      </div>
                      <div class="filter-block">
                        <h4>Gender</h4>
                        <ul>
                          <?php
                            if (isset($_GET['category']) || isset($_GET['brand'])) {
                              while ($gender = mysqli_fetch_assoc($genders_result)) {
                                ?>
                                <li>
                                  <input type="checkbox" name="checkbox" class="filter-gender" id="<?php echo $gender['demographic'] ?>" value="<?php echo $gender['demographic'] ?>">
                                  <label for="<?php echo $gender['demographic'] ?>">
                                    <span class="checked"></span>
                                    <span><?php echo $gender['demographic'] ?></span>
                                  </label>
                                  <span class="count"><?php echo $gender['gender_count'] ?></span>
                                </li>
                            <?php }}?>
                        </ul>
                      </div>
                      <div class="filter-block">
                        <h4>Sizes</h4>
                        <ul>
                          <?php
                            if (isset($_GET['category']) || isset($_GET['brand'])) {
                              while ($size = mysqli_fetch_assoc($sizes_result)) {
                                ?>
                                <li>
                                  <input type="checkbox" name="checkbox" class="filter-size" id="<?php echo $size['product_size'] ?>" value="<?php echo $size['product_size'] ?>">
                                  <label for="<?php echo $size['product_size'] ?>">
                                    <span class="checked"></span>
                                    <span><?php echo $size['product_size'] ?></span>
                                  </label>
                                  <span class="count"><?php echo $size['size_count'] ?></span>
                                </li>
                          <?php }}?>
                        </ul>
                      </div>

                    </div>
                  </div>

                  <div class="section">

                    <!-- BREADCRUMB -->
                    <div class="row">
                      <div class="cat-head">
                        <div class="breadcrumb">
                          <ul class="flexitem">
                            <li><a href="index.php">Home</a></li>
                            <li>
                              <?php
                                if (isset($_GET['category'])) {
                                  $cat_row = mysqli_fetch_assoc($cat_result);
                                  echo $cat_row['category_title'];
                                }
                                if (isset($_GET['brand'])) {
                                  $brand_row = mysqli_fetch_assoc($brand_result);
                                  echo $brand_row['brand_title'];
                                }
                              ?>
                            </li>
                          </ul>
                        </div>
                        <div class="cat-navigation flexitem">
                          <div class="item-filter desktop-hide">
                            <a href="javascript:void(0);" class="filter-trigger label">
                              <i class="ri-menu-2-line ri-2x"></i>
                              <span>Filter</span>
                            </a>
                          </div>

                        </div>
                      </div>
                    </div>

                    <!-- PRODUCTS DISPLAYED -->
                    <div class="products main flexwrap" id="product-container">
                      <?php
                        if (isset($_GET['query'])) {
                          include('search_product.php');
                        }
                       ?>
                      <?php
                        if (isset($_GET['category']) || isset($_GET['brand'])) {
                          if ($num_of_rows == 0) {
                            echo "<h3>No Stock Available</h3>";
                          }
                          while ($product = mysqli_fetch_assoc($products_result)) {
                            $product_id  = $product['product_id'];
                            $product_title = $product['product_title'];
                            $product_description = $product['product_description'];
                            $product_image1 = $product['product_image1'];
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
                      <?php }}?>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script/script.js" charset="utf-8"></script>
    <script type="text/javascript">
      const FtoShow = '.filter';
      const Fpopup = document.querySelector(FtoShow);
      const Ftrigger = document.querySelector('.filter-trigger');

      Ftrigger.addEventListener('click', () => {
        setTimeout(() => {
          if(!Fpopup.classList.contains('show')){
            Fpopup.classList.add('show');
          }
        }, 250)
      })

      // auto close by click outside .filter
      document.addEventListener('click', (e) => {
        const isClosest = e.target.closest(FtoShow)
        if(!isClosest && Fpopup.classList.contains('show')){
          Fpopup.classList.remove('show')
        }
      })

      $(document).ready(function () {
        // Function to fetch and display filtered products
        function fetchFilteredProducts() {
          let selectedBrands = [];
          let selectedGenders = [];
          let selectedSizes = [];
          let selectedCategories = [];

          // Collect selected filters
          $('.filter-brand:checked').each(function () {
            selectedBrands.push($(this).val());
          });

          $('.filter-category:checked').each(function () {
            selectedCategories.push($(this).val());
          });

          $('.filter-gender:checked').each(function () {
            selectedGenders.push($(this).val());
          });

          $('.filter-size:checked').each(function () {
            selectedSizes.push($(this).val());
          });

          // Determine whether we are filtering by category or brand
          let filterType = '<?php echo isset($_GET['category']) ? "category" : "brand"; ?>';
          let filterValue = filterType === "category" ? <?php echo json_encode($category_id); ?> : <?php echo json_encode($brand_id ?? null); ?>;

          // Send AJAX request
          $.ajax({
            url: 'functions/filter_products.php',
            method: 'POST',
            data: {
              filterType: filterType,
              filterValue: filterValue,
              categories: selectedCategories,
              brands: selectedBrands,
              genders: selectedGenders,
              sizes: selectedSizes
            },
            success: function (response) {
              $('#product-container').html(response);
              checkWishlistIcons(); // Re-run wishlist check after updating product list
            },
            error: function () {
              alert('Failed to fetch products. Please try again.');
            }
          });
        }

        // Trigger filtering when a filter is changed
        $('.filter-brand, .filter-category, .filter-gender, .filter-size').on('change', function () {
          fetchFilteredProducts();
        });
      });

      // visually mark wishlisted products after filtering function
      function checkWishlistIcons() {
        if (document.querySelector('.wishlist-icon')) {
          fetch('./functions/get_wishlist.php', { method: 'GET' })
            .then(response => {
              if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
              }
              return response.json();
            })
            .then(data => {
              if (data.success && data.wishlist) {
                // Reset all wishlist icons first
                document.querySelectorAll('.wishlist-icon').forEach(icon => icon.classList.remove('clicked'));

                // Apply 'clicked' class to wishlisted products
                data.wishlist.forEach(product => {
                  document
                    .querySelectorAll(`.wishlist-icon[data-product-id="${product.product_id}"]`)
                    .forEach(icon => icon.classList.add('clicked'));
                });
              } else if (!data.success && data.message) {
                console.warn(data.message);
              }
            })
            .catch(error => console.error('Error fetching wishlist:', error));
        }
      }

      // Run wishlist check when the page initially loads
      window.addEventListener('DOMContentLoaded', checkWishlistIcons);


    </script>
  </body>
</html>
