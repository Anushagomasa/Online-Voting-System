<?php
session_start();

if (!isset($_SESSION['firstname'])) {
    header("Location: login.php");
    exit();
}

// Example candidates data
$candidates = [
    ['id' => 1, 'name' => 'Candidate A', 'party' => 'BJP', 'logo' => 'https://m.media-amazon.com/images/I/61OgrIEL7EL.jpg'],
    ['id' => 2, 'name' => 'Candidate B', 'party' => 'CONGRESS', 'logo' => 'https://upload.wikimedia.org/wikipedia/commons/6/63/Indian_National_Congress_hand_logo.png'],
    ['id' => 3, 'name' => 'Candidate C', 'party' => 'BRS', 'logo' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSld2I1vaoB8htgf2RGbCImGfXbhtZ64GNrMw&s'],
    ['id' => 4, 'name' => 'Candidate D', 'party' => 'JANA SENA', 'logo' => 'https://static.toiimg.com/thumb/msid-31942786,imgsize-24017,width-400,resizemode-4/31942786.jpg'],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .vote-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .vote-button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vote for Your Candidate</h1>
        <?php if (isset($_SESSION['errorMessage'])): ?>
            <div class="message">
                <p><?= htmlspecialchars($_SESSION['errorMessage']) ?></p>
                <?php unset($_SESSION['errorMessage']); ?>
            </div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Candidate</th>
                    <th>Party</th>
                    <th>Logo</th>
                    <th>Vote</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidates as $index => $candidate): ?>
                    <tr>
                        <td><strong><?= ($index + 1) ?>.</strong></td>
                        <td><h3><?= htmlspecialchars($candidate['name']) ?></h3></td>
                        <td><h3><?= htmlspecialchars($candidate['party']) ?></h3></td>
                        <td><img class="logo" src="<?= htmlspecialchars($candidate['logo']) ?>" alt="Party Logo"></td>
                        <td>
                            <form action="thanks.php" method="POST">
                                <input type="hidden" name="candidate_id" value="<?= htmlspecialchars($candidate['id']) ?>">
                                <input type="submit" class="vote-button" value="Vote">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
