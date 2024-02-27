<?php
session_start(); // Starting session

// Handling form submission
if (isset($_POST['submit'])) {
    // Database connection credentials
    $host = 'localhost';
    $dbname = 'swe2';
    $user = 'selman';
    $pass = '123';

    try {
        // Establishing PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Checking for admin
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE adminuser = :username");
        $stmt->execute(['username' => $_POST['username']]);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($_POST['password'], $user['adminpass'])) {
                header("Location: admin.php");
                exit();
            }
        }

        // Checking for customer
        $stmt = $pdo->prepare("SELECT * FROM customer WHERE user = :username");
        $stmt->execute(['username' => $_POST['username']]);
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($_POST['password'], $user['pass'])) {
                header("Location: main.php");
                exit();
            }
        }

        // If no match found
        echo "<script>alert('Invalid username or password.');</script>";
    } catch (PDOException $e) {
      die("Could not connect to the database $dbname :" . $e->getMessage());
    }

    // Close the database connection
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Relationship Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- this is the main page -->
    <div class="container">
        <h1>Customer Relationship Management</h1>
        <form action="index.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Login</button>
        </form>
    </div>
</body>
</html>
