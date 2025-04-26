<?php
  include('../includes/connect.php');
  include('../functions/common_functions.php');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filterType = $_POST['filterType']; // 'category' or 'brand'
    $filterValue = intval($_POST['filterValue']);
    $departments = isset($_POST['departments']) && is_array($_POST['departments']) ? $_POST['departments'] : [];
    var_dump($departments);

    $query = "SELECT * FROM `categories` WHERE ";
    if ($filterType === 'admin') {
      $query .= "$filterValue";
    }
    if (!empty($departments)) {
      $department_placeholders = implode("','", array_map(function ($department) use ($conn) {
        return mysqli_real_escape_string($conn, $department);
      }, $departments));
      $query .= " AND department_id IN (SELECT department_id FROM department WHERE department_title IN ('$department_placeholders'))";
    }
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
      $si_no = 0;
      while ($category = mysqli_fetch_assoc($result)){
        $si_no += 1;
       ?>
        <tr>
          <td><?php echo $si_no ?></td>
          <td><?php echo $category['category_title'] ?></td>
          <td> <a href='index.php?edit_category=<?php echo $category['category_id'] ?>' class='text-light'><i class='fa-solid fa-pen-to-square'></i></a></td>
          <td><a href='index.php?delete_category=<?php echo $category['category_id'] ?>' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
        </tr>
      <?php  }
    } else {
    echo "<td class='text-light'>No products found for the selected filters.</td>";
  }

  }


 ?>
