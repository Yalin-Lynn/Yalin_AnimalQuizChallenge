<?php
require_once __DIR__ . '/auth.php';

// Check whether the player has completed the quiz
if (!isset($_SESSION['final_score'])) {
    header('Location: index.php');
    exit;
}

// Get the final score
$score = (int) $_SESSION['final_score'];

// Check whether the score was saved
$saved = $_SESSION['score_saved'] ?? false;

// Get the error message if saving failed
$error = $_SESSION['score_error'] ?? 'Unknown saving error.';

// Get the logged-in username
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz Results - Animal Quiz Challenge</title>

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
            max-width: 500px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #276749;
            font-size: 28px;
        }

        h2 {
            color: #344e41;
            font-size: 20px;
            margin-top: 25px;
        }

        .score {
            font-size: 48px;
            color: #2f855a;
            font-weight: bold;
            margin: 25px 0;
        }

        .success {
            color: #276749;
            background: #e1f0e1;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .error {
            color: #b00020;
            background: #ffe6e6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
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
            background: #2f855a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
        }

        .btn:hover {
            background: #276749;
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
            .container {
                margin: 25px auto;
                padding: 22px;
            }

            h1 {
                font-size: 24px;
            }

            .score {
                font-size: 40px;
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

    <h1>Quiz Completed!</h1>

    <p>
        Great job,
        <strong>
            <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>
        </strong>!
    </p>

    <h2>Your Final Score</h2>

    <div class="score">
        <?= $score ?> / 100
    </div>

    <?php if ($saved): ?>

        <div class="success">
            <strong>&#10004; Score saved successfully!</strong>
            <p>Your score has been added to the leaderboard.</p>
        </div>

    <?php else: ?>

        <div class="error">
            <strong>&#10006; Score could not be saved.</strong>
            <p>
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>

    <?php endif; ?>

    <div class="buttons">

        <a class="btn" href="game.php?reset=1">
            Play Again
        </a>

        <a class="btn secondary" href="leaderboard.php">
            View Leaderboard
        </a>

        <a class="btn muted" href="index.php">
            Back to Home
        </a>

    </div>

</div>

</body>
</html>
