<?php
include('dbconfig.php');

$customerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$customerDetails = [];
$carDetails = [];
$salesDetails = [];

if($customerId > 0) {
    try {
        //customer
        $stmt = $pdo->prepare("SELECT * FROM customer WHERE id = ?");
        $stmt->execute([$customerId]);
        $customerDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //car information about the customer
        $stmt = $pdo->prepare("SELECT * FROM car WHERE cid = ?");
        $stmt->execute([$customerId]);
        $carDetails = $stmt->fetchAll(PDO::FETCH_ASSOC); //more than 1 car associated with customer
        
        //get sales details associated with customer
        if ($customerDetails && $customerDetails['sid']) {
            $stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
            $stmt->execute([$customerDetails['sid']]);
            $salesDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
    } catch (PDOException $e) {
        die("Could not connect to the database $dbname :" . $e->getMessage());
    }
} else {
    echo "Invalid Customer ID";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="centered-form">
        <div class="form-container" style="width: auto;">
            <h2>Customer Details</h2>
            <div class="details-section">
            <?php if (!empty($customerDetails)): ?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                    <tr>
                        <td><?= htmlspecialchars($customerDetails['firstName'] . ' ' . $customerDetails['lastName']) ?></td>
                        <td><?= htmlspecialchars($customerDetails['address']) ?></td>
                        <td><?= htmlspecialchars($customerDetails['phone']) ?></td>
                        <td><?= htmlspecialchars($customerDetails['email']) ?></td>
                    </tr>
                </table>
            </div>

            <h3>Car Details</h3>
            <div class="details-section">
                <table>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                </tr>
                <?php foreach ($carDetails as $car): ?>
                <tr>
                    <td><?= htmlspecialchars($car['make']) ?></td>
                    <td><?= htmlspecialchars($car['model']) ?></td>
                    <td><?= htmlspecialchars($car['year']) ?></td>
                </tr>
                <?php endforeach; ?>
                </table>
            </div>

            <h3>Salesperson Details</h3>
            <div class="details-section">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Total Cars Sold</th>
                    </tr>
                    <?php if (!empty($salesDetails)): ?>
                    <tr>
                        <td><?= htmlspecialchars($salesDetails['salesname']) ?></td>
                        <td><?= htmlspecialchars($salesDetails['position']) ?></td>
                        <td><?= htmlspecialchars($salesDetails['totalCarSold']) ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td colspan="3">No salesperson details found.</td>
                    </tr>
                <?php endif; ?>
                </table>
        <?php else: ?>
            <p>Customer details not found.</p>
        <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

