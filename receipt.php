<?php
// Database connection
include('db_connecting.php');

// Check if receipt_id is passed
if (!isset($_GET['receipt_id']) || empty($_GET['receipt_id'])) {
    die("Receipt ID is missing.");
}

$receiptId = $_GET['receipt_id'];

// Fetch the receipt from the database
$receiptQuery = $conn->prepare("SELECT * FROM receipts WHERE receipt_id = ?");
$receiptQuery->bind_param("i", $receiptId);
$receiptQuery->execute();
$receiptResult = $receiptQuery->get_result();

// Check if the receipt exists
if ($receiptResult->num_rows === 0) {
    die("Receipt not found.");
}

$receipt = $receiptResult->fetch_assoc();

// Fetch the receipt items
$itemsQuery = $conn->prepare("SELECT * FROM receipt_items WHERE receipt_id = ?");
$itemsQuery->bind_param("i", $receiptId);
$itemsQuery->execute();
$itemsResult = $itemsQuery->get_result();

$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $items[] = $row;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo htmlspecialchars($receipt['receipt_id']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .receipt-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .receipt-header {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .receipt-info, .customer-info {
            margin-bottom: 20px;
        }
        .receipt-info p, .customer-info p {
            margin: 0;
            font-size: 14px;
        }
        .receipt-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .receipt-table th, .receipt-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        .receipt-total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .back-to-home {
            text-align: center;
            margin-top: 30px;
        }
        .back-to-home a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #9A9F69;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-to-home a:hover {
            background-color: #7c7f49;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">Receipt #<?php echo htmlspecialchars($receipt['receipt_id']); ?></div>

    <div class="customer-info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($receipt['customer_email']); ?></p>
    </div>

    <div class="receipt-info">
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($receipt['user_id']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($receipt['created_at']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $receipt['payment_method']))); ?></p>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                <td>RM<?php echo number_format($item['item_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td>RM<?php echo number_format($item['item_total'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="receipt-total">
        Total Amount: RM<?php echo number_format($receipt['total_amount'], 2); ?>
    </div>

    <div class="back-to-home">
        <a href="KOIKIES.html">Back to Home</a>
    </div>
</div>

</body>
</html>
