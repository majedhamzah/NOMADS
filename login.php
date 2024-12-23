<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php'; // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "Both fields are required.";
        exit;
    }

    // Prepare the SQL statement to retrieve the user
    $stmt = $conn->prepare("SELECT User_Id, Password FROM Login_Register WHERE Email_Address = ?");
    if (!$stmt) {
        echo "Database error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "No user found with that email.";
        exit;
    }

    // Bind the result to variables
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Start a session for the logged-in user
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['email'] = $email;
        $_SESSION['logged_in'] = true;

        echo "Login successful!";
        header("Location: index.php"); // Redirect to the dashboard
        exit;
    } else {
        echo "Incorrect password.";
        exit;
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Jogja en Luxe</title>
    <link rel="stylesheet" href="styles/login-styles.css">

</head>

<body>
    <div class="login-container">
        <div class="logo">
            <img src="Screenshot 2024-12-16 165139.png" alt="Jogja en Luxe Logo">
            <span>Jogja en Luxe</span>
        </div>

        <div class="login-content">
            <div class="illustration">
                <img src="Screenshot 2024-12-20 170414.png" alt="Login Illustration">
            </div>

            <div class="login-form">
                <h1>Sign In</h1>
                <form id="loginForm" action="" method="POST">
                    <div class="form-group">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" placeholder="Your Email" required>
                    </div>

                    <div class="form-group">
                        <div class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19 11H5a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7a2 2 0 0 0-2-2zM7 11V7a5 5 0 0 1 10 0v4"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Your Password" required>
                    </div>

                    <button type="submit" class="login-button">Login</button>
                </form>


                <a href="register.php" class="create-account">Create an account</a>
            </div>
        </div>
    </div>
</body>

</html>