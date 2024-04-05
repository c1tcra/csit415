<?php
include 'dbconfig.php';

try {
    //customer
    $stmt = $pdo->query("SELECT id, firstName, lastName FROM customer");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //sales
    $stmtSales = $pdo->query("SELECT id, salesname, position FROM sales");
    $salespeople = $stmtSales->fetchAll(PDO::FETCH_ASSOC);

    //service
    $stmtService = $pdo->query("SELECT DISTINCT servicedby FROM service");
    $servicepeople = $stmtService->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-dashboard">
        <h2>Customer List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Details</th>
            </tr>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= htmlspecialchars($customer['id']) ?></td>
                    <td><?= htmlspecialchars($customer['firstName']) ?></td>
                    <td><?= htmlspecialchars($customer['lastName']) ?></td>
                    <td><a href="customerdetails.php?id=<?= $customer['id'] ?>">View Details</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Salespeople List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
            </tr>
            <?php foreach ($salespeople as $salesperson): ?>
                <tr>
                    <td><?= htmlspecialchars($salesperson['id']) ?></td>
                    <td><?= htmlspecialchars($salesperson['salesname']) ?></td>
                    <td><?= htmlspecialchars($salesperson['position']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Service People List</h2>
        <table>
            <tr>
                <th>Serviced By</th>
            </tr>
            <?php foreach ($servicepeople as $serviceperson): ?>
                <tr>
                    <td><?= htmlspecialchars($serviceperson['servicedby']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
