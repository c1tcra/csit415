<!-- bugged and needs fixing -->
<?php
if (!isset($_GET['id'])) {
    die('Need to specify an ID');
}

$id = $_GET['id'];

$host = 'localhost';
$dbname = 'swe2';
$user = 'selman';
$pass = '123';
$dsn = "mysql:host=$host;dbname=$dbname";
$pdo = new PDO($dsn, $user, $pass);

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //customer
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        die('Customer not found');
    }

    //sales
    $salesStmt = $pdo->prepare("SELECT * FROM sales WHERE sid = :id");
    $salesStmt->execute(['id' => $id]);
    $sales = $salesStmt->fetchAll(PDO::FETCH_ASSOC);
//car 8 sales
    $cars = [];
    foreach ($sales as $sale) {
    //car in sale
    if (isset($sale['carId'])) {
        $carStmt = $pdo->prepare("SELECT * FROM car WHERE id = :carId");
        if ($carStmt->execute(['carId' => $sale['carId']])) {
            $car = $carStmt->fetch(PDO::FETCH_ASSOC);
            if ($car) {
                $cars[] = $car;
            }
        }
    }
}

    //fetching services information
    $services = [];
    $serviceStmt = $pdo->prepare("SELECT * FROM service WHERE cid = :id"); 
    $serviceStmt->execute(['id' => $id]);
    $services = $serviceStmt->fetchAll(PDO::FETCH_ASSOC);

    //fetching terms information
    $terms = [];
    $termStmt = $pdo->prepare("SELECT * FROM term WHERE cid = :id"); 
    $termStmt->execute(['id' => $id]);
    $terms = $termStmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
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
    <h2>Customer Details</h2>
    <p>Name: <?= htmlspecialchars($customer['firstName'] . ' ' . $customer['lastName']) ?></p>
    <h3>Sales</h3>
    <ul>
        <?php foreach ($sales as $sale): ?>
            <li>Sale ID: <?= htmlspecialchars($sale['id']) ?>, Amount: <?= htmlspecialchars($sale['amount']) ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>Cars</h3>
    <ul>
        <?php foreach ($cars as $car): ?>
            <li>Make: <?= htmlspecialchars($car['make']) ?>, Model: <?= htmlspecialchars($car['model']) ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>Services</h3>
    <ul>
        <?php foreach ($services as $service): ?>
            <li><?= htmlspecialchars($service['name']) ?>: <?= htmlspecialchars($service['description']) ?></li>
        <?php endforeach; ?>
    </ul>
    <h3>Terms</h3>
    <ul>
        <?php foreach ($terms as $term): ?>
            <li><?= htmlspecialchars($term['description']) ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
