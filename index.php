<?php
require_once __DIR__ . '/auth.php';

// Get the logged-in username
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Animal Quiz Challenge - Home</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eaf4e8;
            color: #344e41;
            text-align: center;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 650px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #276749;
            font-size: 28px;
            margin-bottom: 25px;
        }

        .welcome {
            font-size: 1.2em;
            color: #344e41;
            margin-bottom: 30px;
        }

        .welcome strong {
            color: #276749;
        }

        .rules {
            text-align: left;
            background: #f0f7f0;
            padding: 22px;
            border-radius: 8px;
            border-left: 5px solid #2f855a;
            margin: 25px 0 30px;
        }

        .rules h3 {
            color: #276749;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .rules ul {
            margin: 0;
            padding-left: 22px;
        }

        .rules li {
            margin: 12px 0;
            line-height: 1.6;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 13px 20px;
            text-decoration: none;
            border-radius: 8px;
            background: #2f855a;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background: #276749;
            color: white;
        }

        .secondary {
            background: #388e65;
        }

        .secondary:hover {
            background: #276749;
        }

        .muted {
            background: #527d66;
        }

        .muted:hover {
            background: #344e41;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .container {
                margin: 25px auto;
                padding: 22px;
            }

            h1 {
                font-size: 24px;
            }

            .buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🐾 Animal Quiz Challenge 🐾</h1>

    <p class="welcome">
        Welcome,
        <strong>
            <?= htmlspecialchars(
                $username,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </strong>!
    </p>

    <div class="rules">

        <h3>How to Play</h3>

        <ul>
            <li>Answer 10 animal questions.</li>

            <li>
                Each correct answer earns 10 points
                (maximum 100).
            </li>

            <li>
                Adaptive difficulty: 2 correct in a row
                levels up; 2 wrong in a row levels down.
            </li>

            <li>
                Your final score is saved to the
                leaderboard when storage is available.
            </li>
        </ul>

    </div>

    <div class="buttons">

        <a class="btn" href="game.php?reset=1">
            Start Quiz
        </a>

        <a class="btn secondary" href="leaderboard.php">
            Leaderboard
        </a>

        <a class="btn muted" href="team.html">
            Our Team
        </a>

        <a class="btn muted" href="logout.php">
            Log Out
        </a>

    </div>

</div>

</body>
</html>
