<?php
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
$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $firstname = trim($_POST['firstname']);
    $mobile = trim($_POST['mobile']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $state = trim($_POST['state']);
    $country = trim($_POST['country']);
    $voterid = trim($_POST['voterid']);
    $address = trim($_POST['address']);
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    // Validate mobile number (example: must be 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $errorMessage = "Invalid mobile number.";
    } elseif (!$email) {
        $errorMessage = "Invalid email format.";
    } else {
        // Check for existing users with the same email or voter ID
        $stmt = $conn->prepare("SELECT * FROM voter WHERE email = ? OR voterid = ?");
        $stmt->bind_param("ss", $email, $voterid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errorMessage = "This email or voter ID is already registered. Please <a href='login.php'>log in</a>.";
        } else {
            // Handle image upload
            $target = "uploads/" . basename($image);

            // Create uploads directory if it doesn't exist
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            // Prepare the statement for insertion
            $stmt = $conn->prepare("INSERT INTO voter (firstname, mobile, password, email, state, country, voterid, address, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("SQL prepare error: " . $conn->error);
            }

            $stmt->bind_param("sssssssss", $firstname, $mobile, $password, $email, $state, $country, $voterid, $address, $image);

            // Execute the statement and handle potential errors
            if ($stmt->execute() && move_uploaded_file($tmp_name, $target)) {
                $successMessage = "Registration successful!";
                header('Location: login.php');
                exit();
            } else {
                $errorMessage = "Registration failed. Please try again.";
            }
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
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
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="file"] {
            border: none;
            margin-bottom: 15px;
        }
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #337ab7;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message p {
            margin: 5px 0;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .error a {
            color: red;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Registration Page</h2>
        <form method="post" action="register.php" enctype="multipart/form-data">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="mobile">Mobile:</label>
            <input type="text" id="mobile" name="mobile" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="country">City:</label>
            <input type="text" id="country" name="country" required>

            <label for="voterid">Voter ID:</label>
            <input type="text" id="voterid" name="voterid" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <div class="form-actions">
                <input type="submit" value="Register">
                <p class="login-link">Already registered? <a href="login.php">Login here</a></p>
            </div>
        </form>

        <div class="message">
            <?php if ($successMessage): ?>
                <p class="success"><?= htmlspecialchars($successMessage) ?></p>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <p class="error"><?= htmlspecialchars($errorMessage) ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
