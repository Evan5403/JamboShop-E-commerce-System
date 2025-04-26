<h3 class="text-center text-success">All Promotions</h3>
<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Flashsale Name</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Discount Value</th>
      <th>Applicable Product</th>
      <th>Stock Limit</th>
      <th>Qty Sold</th>
      <th>Qty Remaining</th>
      <th>Status</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center">
    <?php
      $get_flashsale = "SELECT
                          fs.flash_sale_id,
                          fs.flash_sale_name,
                          fs.discount_value,
                          fs.stock_limit,
                          fs.qty_sold,
                          fs.qty_remaining,
                          fs.start_date,
                          fs.end_date,
                          fs.applicable_id,
                          fs.status,
                          p.applicable_id,
                          p.product_title
                        FROM
                            flash_sales fs
                        LEFT JOIN
                          (
                            SELECT
                            product_id AS applicable_id,
                            product_title
                          FROM
                            products
                          ) p
                        ON
                          fs.applicable_id = p.applicable_id";
      $result_flashsale = mysqli_query($conn, $get_flashsale);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($result_flashsale)){

        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $row['flash_sale_name'] ?></td>
        <td><?php echo $row['start_date'] ?></td>
        <td><?php echo $row['end_date'] ?></td>
        <td><?php echo $row['discount_value'] . '%' ?></td>
        <td><?php echo $row['product_title'] ?></td>
        <td><?php echo $row['stock_limit'] ?></td>
        <td><?php echo $row['qty_sold'] ?></td>
        <td><?php echo $row['qty_remaining'] ?></td>
        <td><?php echo $row['status'] ?></td>
        <td> <a href='index.php?edit_flashsale=<?php echo $row['flash_sale_id'] ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_flashsale=<?php echo $row['flash_sale_id'] ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
