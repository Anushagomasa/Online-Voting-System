<?php
session_start();

if (!isset($_SESSION['firstname'])) {
    header("Location: login.php");
    exit();
}

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

$username = $_SESSION['firstname']; // Get username from session
$candidateId = intval($_POST['candidate_id']);

// Check if the user has already voted
$stmt = $conn->prepare("SELECT * FROM votes WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['errorMessage'] = "You have already voted!";
    header("Location: vote.php");
    exit();
} else {
    // Insert the vote into the votes table
    $stmt = $conn->prepare("INSERT INTO votes (username, candidate_id) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $candidateId);

    if ($stmt->execute()) {
        $message = "Thank you for voting!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
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
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message p {
            margin: 5px 0;
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thank You</h2>
        <div class="message">
            <p><?= htmlspecialchars($message) ?></p>
        </div>
    </div>
</body>
</html>
