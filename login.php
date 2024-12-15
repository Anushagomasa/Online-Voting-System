<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onlinevotingsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize messages
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine login type
    $loginType = $_POST['login_type'];
    $password = trim($_POST['password']);
    
    if ($loginType === 'admin') {
        $username = trim($_POST['username']);
        // Admin login
        $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
    } else {
        $firstname = trim($_POST['firstname']);
        // User login
        $stmt = $conn->prepare("SELECT password FROM voter WHERE firstname = ?");
        $stmt->bind_param("s", $firstname);
    }

    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Fetch the hashed password
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            if ($loginType === 'admin') {
                // Admin login successful
                $_SESSION['admin_username'] = $username;
                header('Location: results.php'); // Redirect to results page for admin
            } else {
                // User login successful
                $_SESSION['firstname'] = $firstname;
                header('Location: dashboard.php'); // Redirect to dashboard page
            }
            exit();
        } else {
            $errorMessage = "Invalid password.";
        }
    } else {
        $errorMessage = "Invalid details. User not found. Please register first.";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .button-wrapper input,
        .button-wrapper a button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 48%;
            box-sizing: border-box;
        }
        .button-wrapper input:hover,
        .button-wrapper a button:hover {
            background-color: #4cae4c;
        }
        .login-button {
            display: block;
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 50%;
            margin: 0 auto 15px auto;
        }
        .login-button:hover {
            background-color: #4cae4c;
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message p {
            margin: 5px 0;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="">
            <input type="hidden" name="login_type" value="user">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" class="login-button" value="Login">
        </form>

        <form method="post" action="">
            <input type="hidden" name="login_type" value="admin">
            <label for="username">Admin Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Admin Password:</label>
            <input type="password" id="password" name="password" required>

            <div class="button-wrapper">
                <input type="submit" value="Login as Admin">
                <a href="register.php">
                    <button type="button">Register Here</button>
                </a>
            </div>
        </form>

        <div class="message">
            <?php if ($errorMessage): ?>
                <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
