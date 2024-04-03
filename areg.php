<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connection to the database
    $host = 'localhost';
    $dbname = 'swe2';
    $user = 'selman';
    $pass = '123';
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass);

    // Collecting user input
    $adminUser = htmlspecialchars($_POST['adminuser']);
    $adminPass = $_POST['adminpass']; // Password will be hashed, no need to sanitize
    $adminName = htmlspecialchars($_POST['adminname']); // Sanitizing the admin name

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE adminuser = ?");
    $stmt->execute([$adminUser]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        // Hashing the password
        $hashedPassword = password_hash($adminPass, PASSWORD_DEFAULT);

        // Inserting data into the database
        $sql = "INSERT INTO admin (adminuser, adminpass, adminname) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$adminUser, $hashedPassword, $adminName]);

        echo "<script>alert('Registration successful!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <form action="areg.php" method="post">
        <input type="text" name="adminuser" placeholder="Admin Username" required>
        <input type="password" name="adminpass" placeholder="Admin Password" required>
        <input type="text" name="adminname" placeholder="Admin Name" required>
        <input type="submit" value="Register">
    </form>
</body>
</html>
