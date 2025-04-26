


  <h3 class="text-danger mb-4">Are You Sure You Want To Delete Your Account</h3>

  <form class="mt-5" action="" method="post">
    <div class="form-outline mb-4">
      <input type="submit" name="delete" class="form-control w-50 m-auto" value="proceed">
    </div>
    <div class="form-outline mb-4">
      <input type="submit" name="cancel" class="form-control w-50 m-auto" value="cancel">
    </div>
  </form>

  <?php
    $username = $_SESSION['username'];
    if (isset($_POST['delete'])) {
      $delete_query = "DELETE FROM `user_table` WHERE user_name='$username'";
      $result = mysqli_query($conn,$delete_query);
      if ($result) {
        session_destroy();
        ?>
        <script>
          Swal.fire({
            position: "top",
            icon: "alert",
            title: "Account Deleted!",
            text: "",
            showConfirmButton: false,
            timer: 2300
          }).then(() => {
            window.open('../index.php','_self');
          });
        </script>
        <?php
      }
    }
    if (isset($_POST['cancel'])) {
      echo "<script>window.open('user_profile.php','_self')</script>";
    }
   ?>
