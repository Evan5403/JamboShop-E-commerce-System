<?php

  // Fetch Product Reviews & Ratings
  $get_reviews = "SELECT
                      COUNT(reviews.product_id) AS review_count,
                      products.product_id AS pid,
                      products.product_title AS name,
                      products.product_image1 AS image,
                      products.average_rating AS average_rating
                    FROM
                      reviews
                    INNER JOIN
                      products
                    ON
                      reviews.product_id = products.product_id
                    GROUP BY
                      reviews.product_id";
  $result_reviews = mysqli_query($conn, $get_reviews);
  $reviews_num_rows = mysqli_num_rows($result_reviews);
 ?>

 <div class="header">
     <div class="left">
         <h1>Reviews & Ratings</h1>
         <ul class="breadcrumb">
           <?php
             if ($role == 'admin') { ?>
               <li><a href="admin_profile.php">
                     Dashboard
                   </a></li>
               /
               <li><a href="#" class="active">Reviews & Ratings</a></li>
           <?php } elseif ($role == 'store_manager') { ?>
                   <li><a href="admin_profile.php?store_manager">
                         Dashboard
                       </a></li>
                   /
                   <li><a href="#" class="active">Reviews & Ratings</a></li>
           <?php } else { ?>
                     <li><a href="admin_profile.php?analytics">
                           Analytics
                         </a></li>
                     /
                     <li><a href="#" class="active">Reviews & Ratings</a></li>
           <?php } ?>
         </ul>
     </div>
 </div>

 <!-- End of Insights -->

 <div class="bottom-data">
     <div class="orders">
         <div class="header">
             <i class='bx bx-receipt'></i>
             <h3>Product Reviews & Ratings</h3>
             <!-- <i class='bx bx-filter'></i>
             <i class='bx bx-search'></i> -->
         </div>
         <table>
             <thead>
                 <tr>
                     <th>Product Details</th>
                     <th>Average Ratings</th>
                     <th>No. Of Reviews</th>
                     <th>User Reviews</th>
                 </tr>
             </thead>
             <tbody>
               <?php
                if ($result_reviews && $reviews_num_rows > 0) {
                  while ($row = mysqli_fetch_assoc($result_reviews)) { ?>
                    <tr>
                        <td>
                            <img src="../../product_imgs/<?php echo $row['image'] ?>">
                            <p><?php echo $row['name'] ?></p>
                        </td>
                        <td><?php echo $row['average_rating'] ?></td>
                        <td><?php echo $row['review_count'] ?></td>
                        <td>
                          <button class="status completed view-reviews-btn" data-productID="<?php echo $row['pid'] ?>" data-productName="<?php echo $row['name'] ?>">View</button>
                        </td>
                    </tr>
                <?php }} ?>
             </tbody>
         </table>
     </div>

 </div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
  document.body.addEventListener('click', function(event){
    if (event.target.classList.contains('view-reviews-btn')){
      const button = event.target;
      const productID = button.getAttribute('data-productID');
      const productName = button.getAttribute('data-productName');
      console.log(productName);
      // Fetch user reviews --- don't worry about file name. I just do not want to change it to avoid errors
      fetch(`../../functions/fetch_order_details.php?product_id=${productID}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {

          // Format Date
          function formatDateString(dateString) {
            const date = new Date(dateString);
            if (isNaN(date)) {
                return 'Invalid Date';
            }
            if (date == 'NULL') {
              return 'Awaiting Delivery'
            }else {
              const day = String(date.getDate()).padStart(2, '0'); // Day with leading zeros
              const monthShort = date.toLocaleString('en-US', { month: 'short' }); // Short month name
              const year = String(date.getFullYear()).slice(-2); // Last 2 digits of the year
              const hours = String(date.getHours()).padStart(2, '0'); // Hours with leading zeros
              const minutes = String(date.getMinutes()).padStart(2, '0'); // Minutes with leading zeros
              const seconds = String(date.getSeconds()).padStart(2, '0'); // Seconds with leading zeros

              // Format as "16-Dec-24 00:00:00"
              return `${day}-${monthShort}-${year} ${hours}:${minutes}:${seconds}`;
            }
          }

          const rows = data.user_reviews.map(user_review => `
            <tr>
              <td>
                <img src="../../users_area/user_images/${user_review.user_image}" class="modal_img">
              </td>
              <td>${user_review.user_name}</td>
              <td>${user_review.rating}</td>
              <td>${user_review.review_summary}</td>
              <td>${user_review.review_text}</td>
              <td>${user_review.created_at}</td>
              <td></td>
            </tr>
          `).join('');

          Swal.fire({
            title: `User Reviews On ${productName} product`,
            html: `
                <table>
                    <thead>
                        <tr>
                          <th>User Image</th>
                          <th>User_Name</th>
                          <th>Rating</th>
                          <th>Review Summary</th>
                          <th>Review Text</th>
                          <th>Review Date</th>
                        </tr>
                    </thead>
                    <tbody>
                      ${rows}
                    </tbody>
                </table>
            `,
            showCloseButton: true,
            confirmButtonText: 'Close'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Something went wrong.', 'error');
      });
    }

  })
</script>
