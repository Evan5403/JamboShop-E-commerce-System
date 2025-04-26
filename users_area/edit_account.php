<?php

  if (isset($_GET['edit_account'])) {
    $user_session = $_SESSION['username'];
    $select_query = "SELECT * FROM `user_table` WHERE user_name='$user_session'";
    $result_query = mysqli_query($conn,$select_query);
    $row_fetch = mysqli_fetch_assoc($result_query);
    $user_id = $row_fetch['user_id'];
    $username = $row_fetch['user_name'];
    $user_email = $row_fetch['user_email'];
    $user_address = $row_fetch['user_address'];
    $user_mobile = $row_fetch['user_mobile'];
  // var_dump($user_email);
  // die;
  }

  if (isset($_POST['user_update'])) {
    $update_id = $user_id;
    $username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_address = $_POST['address'];
    $user_mobile = $_POST['contact'];
    $user_image = $_FILES['image']['name'];
    $user_img_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($user_img_tmp,"./user_images/$user_image");

    // update data
    $update_query = "UPDATE `user_table`
                     SET
                        user_name='$username', user_email='$user_email', user_image='$user_image', user_address='$user_address', user_mobile='$user_mobile'
                     WHERE user_id='$update_id'";
   $result_update_query = mysqli_query($conn,$update_query);
   if($result_update_query) {
     ?>
     <script>
       Swal.fire({
         position: "top",
         icon: "success",
         title: "Updated!",
         text: "User Updated Successfully",
         showConfirmButton: false,
         timer: 2300
       }).then(() => {
         window.open('user_logout.php','_self');
       });
     </script>
     <?php
   }

  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit Account</title>
    <!-- <link rel="stylesheet" href="./css/master.css"> -->
  </head>
  <style media="screen">
    .profilic_img{
      width: 70px;
      height: 70px;
      object-fit: contain;
      margin-left: 10px;
    }
  </style>
  <body>
    <h3 class="text-success text-center mb-4">Edit Account</h3>
    <form class="text-center" action="" method="post" enctype="multipart/form-data">
      <div class="form-outline mb-4">
        <input type="text" class="form-control w-50 m-auto" name="username" value="<?php echo $username; ?>">
      </div>
      <div class="form-outline mb-4">
        <input type="email" class="form-control w-50 m-auto" name="email" value="<?php echo $user_email;?>">
      </div>
      <div class="form-outline mb-4 d-flex w-50 m-auto">
        <input type="file" class="form-control" name="image">
        <img src="./user_images/<?php echo $user_image; ?>" class="profilic_img">
      </div>
      <div class="form-outline mb-4">
        <input type="text" class="form-control w-50 m-auto" name="address" value="<?php echo $user_address; ?>">
      </div>
      <div class="form-outline mb-4">
        <input type="tel" name="contact" class="form-control w-50 m-auto" id="contact" autocomplete="off" value="<?php echo $user_mobile; ?>" pattern="[0-9]{10}" title="Please enter a correct mobile number" required>
      </div>
      <input type="submit" name="user_update" value="Update" class="bg-info py-2 px-3 border-0">
    </form>
  </body>
</html>
