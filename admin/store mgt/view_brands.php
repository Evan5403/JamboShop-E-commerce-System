<h3 class="text-center text-success">All Brands</h3>
<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Brand Title</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center">
    <?php
      $get_brands = "SELECT * FROM `brands`";
      $result_brand = mysqli_query($conn, $get_brands);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($result_brand)){
        $brand_id = $row['brand_id'];
        $brand_title = $row['brand_title'];
        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $brand_title ?></td>
        <td> <a href='index.php?edit_brand=<?php echo $brand_id ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_brand=<?php echo $brand_id ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
