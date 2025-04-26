<?php
  if (isset($_GET['edit_department'])) {
    $department_id = $_GET['edit_department'];

    $get_department = "SELECT * FROM `department` WHERE department_id=$department_id";
    $result_department = mysqli_query($conn, $get_department);
    $row_department_title = mysqli_fetch_assoc($result_department);
    $department_title = $row_department_title['department_title'];
  }

 ?>


<div class="container mt-3">
  <h1 class="text-center">Edit Department</h1>
  <form class="text-center" action="" method="post">
    <div class="form-outline mb-4 w-50 m-auto">
      <label for="department_title" class="form-label">Department Title</label>
      <!-- <span class="input-group-text bg-info" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span> -->
      <input type="text" name="department_title" value="<?php echo $department_title ?>" id="department_title" class="form-control" autocomplete="off" required>
    </div>
    <input type="submit" name="update_department" value="Update Department" class="btn btn-info px-3 mb-3">
  </form>
</div>


<?php

  if (isset($_POST['update_department'])) {
    $department_title = htmlspecialchars($_POST['department_title'], ENT_QUOTES, 'UTF-8');

    // check if department exists
    $stmt = $conn->prepare("SELECT * FROM `department` WHERE department_title  = ? AND department_id != ?");
    $stmt->bind_param("si", $department_title, $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows_count = $result->num_rows;
    if ($rows_count > 0) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "error",
          title: "Error...",
          text: "Department Already Exists",
          showConfirmButton: false,
          timer: 2000
        });
      </script>
      <?php
      die;
    }

    $update_department = "UPDATE `department` SET department_title='$department_title' WHERE department_id =$department_id ";
    $result = mysqli_query($conn, $update_department);
    if ($result) {
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Updated!",
          text: "Department Updated Successfully",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?edit_department=<?php echo $department_id ?>','_self');
        });
      </script>
      <?php
    }
  }

 ?>
