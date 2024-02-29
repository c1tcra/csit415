<!-- admin site from index -->
<?php

$host = 'localhost';
$dbname = 'swe2';
$user = 'selman'; //replace if youre getting db connection issues
$pass = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetching all customers
    $stmt = $pdo->query("SELECT id, firstName, lastName FROM customer");
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h2>Customer List</h2>
    <ul>
        <?php foreach ($customers as $customer): ?>
            <li>
                <a href="customerdetails.php?id=<?= $customer['id'] ?>">
                    <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
