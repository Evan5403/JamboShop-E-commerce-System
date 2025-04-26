<?php

  if (isset($_GET['delete_department'])) {
    $department_id = $_GET['delete_department'];
    $stmt = $conn->prepare("SELECT * FROM `department` WHERE department_id  = ?");
    $stmt->bind_param("i", $department_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $department_title = $row['department_title'];
  }
?>

<h3 class="text-danger mb-4">Are You Sure You Want To Delete Department</h3>

<form class="mt-5" action="" method="post">
  <div class="form-outline mb-4">
    <input type="submit" name="delete" class="form-control w-50 m-auto" value="proceed">
  </div>
  <div class="form-outline mb-4">
    <input type="submit" name="cancel" class="form-control w-50 m-auto" value="cancel">
  </div>
</form>

<?php

  if (isset($_POST['delete'])) {
    $delete_query = "DELETE FROM `department` WHERE department_id ='$department_id '";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted a Department";
      $action_effect = "negative";
      $details = "Brand Name: $department_title"; // Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Department Deleted From Database Successfully!",
          text: "",
          showConfirmButton: false,
          timer: 2300
        }).then(() => {
          window.open('index.php?view_department','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?view_department','_self')</script>";
  }
 ?>
