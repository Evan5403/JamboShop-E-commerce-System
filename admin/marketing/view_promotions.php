<h3 class="text-center text-success">All Promotions</h3>
<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Promotion Name</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Discount Value</th>
      <th>Applicable To</th>
      <th>Applicable Product/Category</th>
      <th>Minimum Cart Value</th>
      <th>Status</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center">
    <?php
      $get_promotions = "SELECT
                          pp.promotion_id,
                          pp.promotion_name,
                          pp.discount_value,
                          pp.start_date,
                          pp.end_date,
                          pp.applicable_to,
                          pp.applicable_id,
                          pp.minimum_cart_value,
                          pp.status,
                          p.applicable_id,
                          p.product_title,
                          c.applicable_id,
                          c.category_title
                        FROM
                            promotions pp
                        LEFT JOIN
                          (
                            SELECT
                            product_id AS applicable_id,
                            product_title
                          FROM
                            products
                          ) p
                        ON
                          pp.applicable_to = 'product' AND pp.applicable_id = p.applicable_id
                        LEFT JOIN
                          (
                            SELECT
                            category_id AS applicable_id,
                            category_title
                          FROM
                            categories
                          ) c
                        ON
                          pp.applicable_to = 'category' AND pp.applicable_id = c.applicable_id";
      $result_promotions = mysqli_query($conn, $get_promotions);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($result_promotions)){

        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $row['promotion_name'] ?></td>
        <td><?php echo $row['start_date'] ?></td>
        <td><?php echo $row['end_date'] ?></td>
        <td><?php echo $row['discount_value'] . '%' ?></td>
        <td><?php echo $row['applicable_to'] ?></td>
        <td>
          <?php
            if (($row['applicable_to'] == 'category')) {
              echo $row['category_title'];
            } elseif (($row['applicable_to'] == 'product')) {
              echo $row['product_title'];
            } else {
              echo "NULL";
            }
           ?>
        </td>
        <td><?php echo ($row['applicable_to'] == 'cart') ? $row['minimum_cart_value'] : 'NULL' ?></td>
        <td><?php echo $row['status'] ?></td>
        <td> <a href='index.php?edit_promotion=<?php echo $row['promotion_id'] ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_promotion=<?php echo $row['promotion_id'] ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
