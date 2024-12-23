<?php
// Enable error reporting at the top
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form inputs
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Combine first name and last name
    $name = $firstName . ' ' . $lastName;

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT User_Id FROM Login_Register WHERE Email_Address = ?");
    if (!$stmt) {
        echo "Database error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email is already registered.";
        exit;
    }

    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $conn->prepare("INSERT INTO Login_Register (Nama, Email_Address, Password) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Database error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        // Redirect to the login page
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Jogja en Luxe</title>
    <link rel="stylesheet" href="styles/register.css">
</head>

<body>
    <div class="create-account-container">
        <div class="logo">
            <img src="Screenshot 2024-12-16 165139.png" alt="Jogja en Luxe Logo">
            <span>Jogja en Luxe</span>
        </div>

        <div class="create-account-content">
            <div class="illustration">
                <img src="Screenshot 2024-12-20 170414.png" alt="Create Account Illustration" class="main-illustration">
            </div>

            <div class="create-account-form">
                <h1>Create Account</h1>
                <form id="createAccountForm" action="" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" id="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" name="lastName" id="lastName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>

                    <button type="submit" class="create-account-button">Create Account</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>