<h3 class="text-center text-success">Manage Slides</h3>
<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Header Title</th>
      <th>Image Cover</th>
      <th>Description</th>
      <th>Applicable Category</th>
      <th>Created Date</th>
      <th>Status</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center">
    <?php
      $sql_query = "SELECT
                          s.slide_id,
                          s.image_cover,
                          s.header_title,
                          s.description	,
                          s.category_id,
                          s.created_date,
                          s.status,
                          c.category_id,
                          c.category_title
                        FROM
                            slides s
                        LEFT JOIN
                          (
                            SELECT
                            category_id,
                            category_title
                          FROM
                            categories
                          ) c
                        ON
                          s.category_id = c.category_id";
      $exe_query = mysqli_query($conn, $sql_query);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($exe_query)){

        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $row['header_title'] ?></td>
        <td><img src='../product_imgs/<?php echo $row['image_cover'] ?>' class='product_img' alt='image_cover'></td>
        <td><?php echo $row['description'] ?></td>
        <td><?php echo $row['category_title'] ?></td>
        <td><?php echo $row['created_date'] ?></td>
        <td><?php echo $row['status'] ?></td>
        <td> <a href='index.php?edit_slide=<?php echo $row['slide_id'] ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_slide=<?php echo $row['slide_id'] ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
