<?php
session_start();
include('../includes/connect.php');
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Welcome @<?php echo $_SESSION['username']; ?></title>

      <!-- bootstrap css link -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <!-- font awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <!-- css file -->
      <link rel="stylesheet" href="../style.css">

      <!-- sweetalert js link -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <title></title>
  </head>
  <body class="bg-secondary">
    <div class="row mt-200">
      <div class="col-md-6 m-auto">
        <table class="table">
          <thead class="table-dark">
            <th>Goods</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total Price</th>
          </thead>
          <tbody>
            <tr>
              <td>Mango</td>
              <td>8</td>
              <td>85</td>
              <td>680</td>
            </tr>
            <tr>
              <td>Capsicum</td>
              <td>2</td>
              <td>65</td>
              <td>130</td>
            </tr>
            <tr class="border border-5 fw-bold">
              <td colspan="3">Subtotal</td>
              <td>810</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><span id="invoice_number"></span></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead class="table-dark">
                <th>Goods</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal Price</th>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <?php
      $display_order_details = "SELECT * FROM `view_order_details` WHERE invoice_number = $invoice_number";
      $execute_query = mysqli_query($conn,$display_order_details);
      while ($row_order = mysqli_fetch_assoc($execute_query)) {
        $product_id = $row_order['product_id'];
        $select_product_name = "SELECT * FROM `products` WHERE product_id = $product_id";
        $execute = mysqli_query($conn,$select_product_name);
        $fetch_product_name = mysqli_fetch_assoc($execute);
        $product_name = $fetch_product_name['product_title'];
        $quantity = $row_order['quantity'];
        $price = $row_order['price'];
        $subtotal = $row_order['subtotal'];

        echo "<tr>
              <td>$product_name</td>
              <td>$quantity</td>
              <td>$price</td>
              <td>$subtotal</td>
            </tr>";
      }

     ?>

    <script type="text/javascript">

       var order_details = document.getElementById('exampleModal')

       order_details.addEventListener('show.bs.modal', function(event){
         var btn = event.relatedTarget
         var invoice_number = btn.getAttribute('data-id')
         var modalId = exampleModal.querySelector('#invoice_number')
         modalId.textContent = invoice_number
         // console.log(modalId);


         fetch('user_profile.php?my_orders', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              'invoice_number': invoice_number
          })
      })
      .then(response => response.json())
      .then(data => {
          console.log('Server response:', data);
      })
      .catch(error => console.error('Error:', error));

       })

    </script>

  </body>
</html>
<!-- <div class="modal fade" id="<?php echo $invoice_number; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $invoice_number ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table>

          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div> -->
