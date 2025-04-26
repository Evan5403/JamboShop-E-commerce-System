<div class="details-myorder">
  <div class="recentOrders">
      <div class="cardHeader">
        <h2>My Wishlist</h2>
      </div>
      <?php
      $query_wishlist = "
                        SELECT
                          w.user_id,
                          w.product_id,
                          p.product_title,
                          p.product_image1,
                          p.price,
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
                          wishlist w
                        INNER JOIN
                          products p
                        ON
                          w.product_id = p.product_id
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
                        WHERE
                          w.user_id = '$user_id'
                    ";
      $result_wishlist = mysqli_query($conn, $query_wishlist);
      $row_count = mysqli_num_rows($result_wishlist);
        if ($row_count == 0) {
          echo "<h2 style='color: red; text-align: center;'> You have no product in your wishlist";
        } else { ?>
          <table>
              <thead>
                  <tr>
                    <td>Product Image</td>
                    <td>Product Name</td>
                    <td>Product Price</td>
                    <td>Product Rating</td>
                    <td>Action</td>
                    <td>Status</td>
                  </tr>
              </thead>

              <tbody id="wishlist-container">
                <?php
                 while ($row_wishlist = mysqli_fetch_assoc($result_wishlist)) {
                   $product_id = $row_wishlist['product_id'];
                   $product_title = $row_wishlist['product_title'];
                   $product_image1 = $row_wishlist['product_image1'];
                   $price = $row_wishlist['final_price'];
                   $average_rating = $row_wishlist['average_rating'];
                   $status = $row_wishlist['status'];
                     ?>
                     <tr>
                       <td width="60px">
                         <div class="imgBx2">
                           <img src="../../product_imgs/<?php echo $product_image1 ?>" alt="">
                         </div>
                       </td>
                       <td><?php echo $product_title ?></td>
                       <td>Kshs.<?php echo $price ?></td>
                       <td><?php echo $average_rating ?></td>
                       <td class="wishlist-td" data-product-id='<?php echo $product_id ?>'>
                         <button class="btn wishlist-details-btn remove-wishlist">Remove</button>
                       </td>
                       <td> <span class="<?php echo $status ?>"><?php echo $status ?></span> </td>
                     </tr>
                 <?php } ?>
              </tbody>
          </table>
      <?php  }
       ?>
  </div>
</div>

<script type="text/javascript">
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-wishlist')){
      const icon = e.target;
      const parentTd = icon.closest('.wishlist-td'); // Get the parent <td>
      const productId = parentTd.getAttribute('data-product-id'); // Fetch product_id from data attribute
      const action =  'remove';

      // Perform AJAX request to add/remove the product from the wishlist
      fetch('../../functions/wishlist_action.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, action: action }),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Product Removed Successfully',
          }).then(() => {
            window.open('user_profile.php?my_wishlist','_self');
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred. Please try again.',
          });
        }
      })
      .catch(error => console.error('Error:', error));

    }

  })
</script>
