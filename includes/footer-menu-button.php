<!-- FOOTER -->
<footer>
  <!-- newsletter -->
  <!-- <div class="newsletter">
    <div class="container">
      <div class="wrapper">
        <div class="box">
          <div class="content">
            <h3>Join Our Newsletter</h3>
            <p>Get E-mail updates about our latest shop and <strong>special offers</strong></p>
          </div>
          <form action="" class="search">
            <span class="icon-large"><i class="ri-mail-line"></i></span>
            <input type="mail" placeholder="Your Email Address" required>
            <button type="submit">Sign Up</button>
          </form>
        </div>
      </div>
    </div>
  </div> -->

  <div class="widgets">
    <div class="container">
      <div class="wrapper">
        <div class="flexwrap">
          <div class="row">
            <div class="item mini-links">
              <h4>Customer Support</h4>
              <ul class="flexcol">
                <li><a href="">Contact Us</a></li>
                <li><a href="">Order Tracking</a></li>
                <li><a href="">Returns & Refund Policy</a></li>
                <li><a href="">FAQS</a></li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="item mini-links">
              <h4>Product Categories</h4>
              <ul class="flexcol">
                <?php
                  $product_cat = "SELECT
                                      vod.invoice_number,
                                      uo.invoice_number,
                                      uo.order_status,
                                      c.category_title,
                                      c.category_id,
                                      SUM(vod.quantity) AS total_purchases
                                    FROM
                                      view_order_details vod
                                    INNER JOIN
                                      products p ON vod.product_id = p.product_id
                                    INNER JOIN
                                      categories c ON p.category_title = c.category_id
                                    INNER JOIN
                                      user_orders uo ON vod.invoice_number = uo.invoice_number
                                    WHERE
                                      uo.order_status = 'complete'
                                    GROUP BY
                                      c.category_title
                                    ORDER BY
                                      total_purchases DESC";
                  $execution_query = mysqli_query($conn, $product_cat);
                  while ($row = mysqli_fetch_assoc($execution_query)) {
                    $categoryTitle = $row['category_title'];
                    $categoryId = $row['category_id'];
                    ?>
                    <li><a href="products.php?category=<?php echo $categoryId ?>"><?php echo $categoryTitle ?></a></li>
                  <?php } ?>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="item mini-links">
              <h4>Flash Sales & Promotions</h4>
              <ul class="flexcol">
                <?php
                  $flashsale = "SELECT
                                  flash_sales.*
                                FROM
                                  flash_sales
                                ORDER BY
                                  flash_sales.status = 'active' DESC,
                                  flash_sales.end_date DESC
                                LIMIT 1";
                  $result_flashsale = mysqli_query($conn, $flashsale);
                  if ($row_flashsale = mysqli_fetch_assoc($result_flashsale)){
                    $flashsale = $row_flashsale['flash_sale_name'];
                    ?>
                    <li><a href="index.php#trending"><?php echo $flashsale ?></a></li>
                  <?php }
                  $promotions = "SELECT
                                  promotions.*
                                FROM
                                  promotions
                                WHERE
                                  promotions.status = 'active'
                                ";
                  $result_promotions = mysqli_query($conn, $promotions);
                  while ($row = mysqli_fetch_assoc($result_promotions)){
                    $promotionName = $row['promotion_name'];
                    ?>
                    <li><a href="index.php#trending"><?php echo $promotionName ?></a></li>
                  <?php } ?>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="item mini-links">
              <h4>About Us</h4>
              <ul class="flexcol">
                <li><a href="about us.html">About Jambo Shop</a></li>
                <li><a href="">Eco-Friendly Fashion Mission</a></li>
                <li><a href="">Careers </a></li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="item mini-links">
              <h4>Payment & Security</h4>
              <ul class="flexcol">
                <li><a href="">Payment Methods</a></li>
                <li><a href="">Privacy Policy</a></li>
                <li><a href=""> Terms & Conditions </a></li>
              </ul>
            </div>
          </div>
          <div class="row">
            <div class="item mini-links">
              <h4>Copyright & Legal Notice</h4>
              <ul class="flexcol">
                <li><a href="">© 2025 Jambo Shop. All Rights Reserved</a></li>
                <li><a href="">Designed & Developed by [Your Name/Company]</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- footer info -->
   <div class="footer-info">
    <div class="container">
      <div class="wrapper">
        <div class="flexcol">
          <div class="logo">
            <a href=""><span class="circle"></span>.Store</a>
          </div>
          <div class="socials">
            <ul class="flexitem">
              <li><a href="#"><i class="ri-twitter-line"></i></a></li>
              <li><a href="#"><i class="ri-facebook-line"></i></a></li>
              <li><a href=""><i class="ri-instagram-line"></i></a></li>
              <li><a href=""><i class="ri-linkedin-line"></i></a></li>
              <li><a href=""><i class="ri-youtube-line"></i></a></li>
            </ul>
          </div>
        </div>
        <p class="mini-text">Copyright 2022 &#169; .Store. All rights reserved </p>
      </div>
    </div>
   </div>

</footer>

<!-- menu button -->
<div class="menu-bottom desktop-hide">
  <div class="container">
    <div class="wrapper">
      <nav>
        <ul class="flexitem">
          <li>
            <a href="#">
              <i class="ri-bar-chart-line"></i>
              <span>Featured Products</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="ri-user-6-line"></i>
              <span>Account</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="ri-heart-line"></i>
              <span>Wishlist</span>
            </a>
          </li>
          <li>
            <a href="#0" class="t-search">
              <i class="ri-search-line"></i>
              <span>Search</span>
            </a>
          </li>
          <li>
            <a href="javascript:void(0);" class="cart-trigger">
              <i class="ri-shopping-cart-line"></i>
              <span>Cart</span>
              <div class="fly-item">
                <span class="item-number"><?php cart_item_numbers(); ?></span>
              </div>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<!-- search-bottom -->
<div class="search-bottom desktop-hide">
  <div class="container">
    <div class="wrapper">
      <form action="" class="search">
        <a href="#" class="t-close search-close flexcenter"><i class="ri-close-line"></i></a>
        <span class="icon-large"><i class="ri-search-line"></i></span>
        <input type="search" placeholder="Search Products" required>
        <button type="submit">Search</button>
      </form>
    </div>
  </div>
</div>
