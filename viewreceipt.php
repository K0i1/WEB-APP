    <?php
session_start();
include 'db_connecting.php';

if (!isset($_GET['id'])) {
    die('Receipt ID is required');
}

$receipt_id = $_GET['id'];

// Fetch receipt details
$query = "SELECT r.id, r.receipt_date, r.amount_paid, r.payment_method, r.receipt_image, o.id AS order_id
          FROM receipts r
          LEFT JOIN orders o ON r.order_id = o.id
          WHERE r.id = ?";
$stmt = $conn->prepare($query);
if ($stmt) {
    $stmt->bind_param("i", $receipt_id);
    $stmt->execute();
    $receiptResult = $stmt->get_result();
    $receipt = $receiptResult->fetch_assoc();
    $stmt->close();
} else {
    die("Error fetching receipt details");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Details - KOIKIES</title>
</head>
<body>

    <h2>Receipt for Order #<?php echo $receipt['order_id']; ?></h2>
    <p><strong>Receipt ID:</strong> <?php echo $receipt['id']; ?></p>
    <p><strong>Receipt Date:</strong> <?php echo date('Y-m-d H:i', strtotime($receipt['receipt_date'])); ?></p>
    <p><strong>Amount Paid:</strong> $<?php echo number_format($receipt['amount_paid'], 2); ?></p>
    <p><strong>Payment Method:</strong> <?php echo $receipt['payment_method']; ?></p>

    <?php if ($receipt['receipt_image']): ?>
        <p><strong>Receipt Image:</strong> <img src="uploads/<?php echo $receipt['receipt_image']; ?>" alt="Receipt Image"></p>
    <?php endif; ?>

</body>
</html>
