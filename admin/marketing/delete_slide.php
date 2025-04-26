<?php

  if (isset($_GET['delete_slide'])){
    $slide_id = $_GET['delete_slide'];
    $get_slide = "SELECT * FROM `slides` WHERE slide_id=$slide_id";
    $result_slide = mysqli_query($conn, $get_slide);
    $row_slide = mysqli_fetch_assoc($result_slide);
    $header_title = $row_slide['header_title'];
  }

 ?>

<h3 class="text-danger text-center mb-4">Are You Sure You Want To Remove Slide</h3>

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
    $delete_query = "DELETE FROM `slides` WHERE slide_id='$slide_id'";
    $result = mysqli_query($conn,$delete_query);
    if ($result) {
      $action = "Deleted Slide";
      $action_effect = "negative";
      $details = "Slide Title: $header_title";// Custom details
      logAdminAction($conn, $admin_id, $action, $action_effect, $details);
      ?>
      <script>
        Swal.fire({
          position: "top",
          icon: "success",
          title: "Slide Removed From Database Successfully!",
        }).then(() => {
          window.open('index.php?manage_slides','_self');
        });
      </script>
      <?php
    }
  }
  if (isset($_POST['cancel'])) {
    echo "<script>window.open('index.php?manage_slides','_self')</script>";
  }
 ?>
