<?php
  include('../includes/connect.php');
  require('fpdf/fpdf.php'); // Include FPDF library

  if (!isset($_GET['invoice'])) {
      die("Invoice number is required.");
  }

  $invoiceNumber = $_GET['invoice'];

  // Fetch order details
  $query = "SELECT * FROM `user_orders` WHERE `invoice_number` = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param('s', $invoiceNumber);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    die("Invalid Invoice Number.");
  }

  $order = $result->fetch_assoc();

  $get_details = "SELECT * FROM `view_order_details` WHERE `invoice_number` = ?";
  $stmt_2 = $conn->prepare($get_details);
  $stmt_2->bind_param('s', $invoiceNumber);
  $stmt_2->execute();
  $result_2 = $stmt_2->get_result();

  // Generate PDF
  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);

  $pdf->Cell(0, 10, 'Invoice Receipt', 0, 1, 'C');
  $pdf->SetFont('Arial', '', 12);
  $pdf->Ln(10);

  $pdf->Cell(50, 10, 'Invoice Number:', 0, 0);
  $pdf->Cell(0, 10, $order['invoice_number'], 0, 1);

  $pdf->Cell(50, 10, 'Order Date:', 0, 0);
  $pdf->Cell(0, 10, $order['order_date'], 0, 1);

  $pdf->Cell(50, 10, 'Total Ordered Products:', 0, 0);
  $pdf->Cell(0, 10, $order['total_products'], 0, 1);

  $pdf->Cell(50, 10, 'Amount Due:', 0, 0);
  $pdf->Cell(0, 10, 'Kshs. ' . $order['amount_due'], 0, 1);

  $pdf->Cell(50, 10, 'Order Status:', 0, 0);
  $pdf->Cell(0, 10, $order['order_status'], 0, 1);

  if ($order['order_status'] == 'cancelled') {
    $get_cancel_reason = "SELECT * FROM `canceled_orders` WHERE invoice_number = $invoiceNumber";
    $result_reason = mysqli_query($conn, $get_cancel_reason);
    $row_reason = mysqli_fetch_array($result_reason);
    $cancel_reason = $row_reason['cancel_reason'];

    $pdf->Cell(50, 10, 'Cancellation Reason:', 0, 0);
    $pdf->Cell(0, 10, $cancel_reason, 0, 1);
  }

  // ORDER DETAILS
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(0, 10, 'Products Ordered', 0, 1, 'C');
  $pdf->SetFont('Arial', '', 12);
  $pdf->Ln(10);

  // Table Header
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(60, 10, 'Product Name', 1, 0, 'C');
  $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
  $pdf->Cell(30, 10, 'Price (Ksh)', 1, 0, 'C');
  $pdf->Cell(40, 10, 'Subtotal (Ksh)', 1, 1, 'C');

  // Table Rows
  $pdf->SetFont('Arial', '', 12);
  $totalAmount = 0; // Initialize total amount
  // Table Rows
  $pdf->SetFont('Arial', '', 12);
  $totalAmount = 0;
  while ($row = $result_2->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $subtotal = $row['subtotal'];
    $totalAmount += $subtotal;

    $fetch_product_from_product_table = "SELECT * FROM `products` WHERE product_id = $product_id";
    $result_product = mysqli_query($conn, $fetch_product_from_product_table);
    $row_product = mysqli_fetch_array($result_product);
    $product_title = $row_product['product_title'];

    // Wrap Product Name
    $lineHeight = 10;
    $maxLineWidth = 60;
    $maxLines = ceil($pdf->GetStringWidth($product_title) / $maxLineWidth);
    $rowHeight = $lineHeight * $maxLines;

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->MultiCell($maxLineWidth, $lineHeight, $product_title, 1, 'L');
    $currentY = $pdf->GetY();

    $pdf->SetXY($x + $maxLineWidth, $y);
    $pdf->Cell(30, $rowHeight, $quantity, 1, 0, 'C');
    $pdf->Cell(30, $rowHeight, number_format($price, 2), 1, 0, 'C');
    $pdf->Cell(40, $rowHeight, number_format($subtotal, 2), 1, 0, 'C');

    $pdf->SetY(max($currentY, $y + $rowHeight));
  }

  // Add Bottom Border for the Table
  $pdf->Cell(160, 0, '', 'T'); // Draw a bottom border

  // Total Amount Row
  $pdf->SetFont('Arial', 'B', 12);
  $rowHeight = 10; // Set uniform row height
  $pdf->Cell(120, $rowHeight, 'Total Amount', 1, 0, 'R');
  $pdf->Cell(40, $rowHeight, 'Ksh ' . number_format($totalAmount, 2), 1, 1, 'C');

  // Total Paid Row
  if ($order['order_status'] == 'cancelled'){
    $pdf->Cell(120, $rowHeight, 'Total Amount', 1, 0, 'R');
    $pdf->Cell(40, $rowHeight, 'Ksh ' . number_format($totalAmount, 2), 1, 1, 'C'); // Assuming total paid equals total amount
  }else {
    $pdf->Cell(120, $rowHeight, 'Total Paid', 1, 0, 'R');
    $pdf->Cell(40, $rowHeight, 'Ksh ' . number_format($totalAmount, 2), 1, 1, 'C'); // Assuming total paid equals total amount
  }


  // Optional "Thank You" Note
  if ($order['order_status'] == 'complete') {
    $pdf->Cell(160, $rowHeight, 'Thank you for choosing JamboShop!', 1, 1, 'C');
  }




  // Output PDF
  $pdf->Output('D', "Invoice_$invoiceNumber.pdf");
  $stmt->close();
  $conn->close();
?>
