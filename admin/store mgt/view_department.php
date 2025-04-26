<h3 class="text-center text-success">All Departments</h3>
<table class="table table-bordered mt-5">
  <thead class="table-primary text-center">
    <tr>
      <th>SIno.</th>
      <th>Department Title</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
  </thead>
  <tbody class="table-dark text-center">
    <?php
      $get_department = "SELECT * FROM `department`";
      $result_department = mysqli_query($conn, $get_department);
      $si_no = 0;
      while ($row = mysqli_fetch_assoc($result_department)){
        $department_id = $row['department_id'];
        $department_title = $row['department_title'];
        $si_no += 1;
      ?>
      <tr>
        <td><?php echo $si_no ?></td>
        <td><?php echo $department_title ?></td>
        <td> <a href='index.php?edit_department=<?php echo $department_id ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
        <td><a href='index.php?delete_department=<?php echo $department_id ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
      </tr>
    <?php } ?>
  </tbody>
</table>
