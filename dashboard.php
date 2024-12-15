<?php
session_start();

if (!isset($_SESSION['firstname'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome to the Dashboard, " . htmlspecialchars($_SESSION['firstname']) . "!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #f0f4c3, #e8eaf6); /* Light pastel gradient */
        }
        .sidebar {
            height: 100vh;
            background: #bbdefb; /* Light blue */
            padding: 20px;
        }
        .sidebar a {
            color: #333333; /* Dark text for contrast */
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #90caf9; /* Slightly darker blue on hover */
        }
        .content {
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        h1, h2 {
            color: #1976d2; /* Dark blue for headings */
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <div class="sidebar">
            <h2 class="text-dark">Dashboard</h2>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vote.php">Main Page</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" href="instruction.php">Instructions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
        <div class="content flex-grow-1">
            <h1>Welcome to Your Dashboard, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</h1>
            <hr>
            <h2>Vote</h2>
            <p>Compulsory Voting means an “obligation to vote,” i.e., it shall be the duty of a qualified voter to cast their vote at elections, failing which they will be liable to penalty or be declared as a “defaulter voter.”</br>

            In India, the right to vote is provided by Article 326 of the Constitution and the Representation of People's Act, 1951, for every citizen aged 18 and above, subject to certain disqualifications.</br>

            Since the right to vote is a legal as well as constitutional right, compulsory voting may violate the fundamental rights of liberty and expression guaranteed to citizens in a democratic state. If the constitutional right to vote may be interpreted to include “the right not to vote,” the provision of compulsory voting in that case violates the Constitution. The Representation of People Act, 1951, provides “the right to vote rather than a duty to vote."
            </p>
            <!-- Add more content as needed -->
        </div>
    </div>
</body>
</html>
