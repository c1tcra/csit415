<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $dbname = 'swe2';
    $user = 'selman';
    $pass = '123';
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $pass);

    $username = htmlspecialchars($_POST['user']);
    $password = $_POST['pass']; // Password will be hashed, no need to sanitize
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);

    //username unique
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE user = ?");
    $stmt->execute([$username]);
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        //hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $sql = "INSERT INTO customer (user, pass, firstName, lastName, address, phone, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $hashedPassword, $firstName, $lastName, $address, $phone, $email]);

        echo "<script>alert('Registration successful!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="centered-form">
        <div class="form-container">
            <h1>Customer Registration</h1>
            <form action="creg.php" method="post">
                <input type="text" name="user" placeholder="Username" required>
                <input type="password" name="pass" placeholder="Password" required>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="phone" placeholder="Phone Number" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="submit" value="Register">
            </form>
        </div>
    </div>
</body>
</html>
