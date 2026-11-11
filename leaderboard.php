<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/game_functions.php';

// Get top 10 scores
$top_scores = getTopScores(10);

// Check the score file
$score_file = '/home/yzhang130/animal_quiz_private/scores.txt';

$file_exists = file_exists($score_file);
$file_readable = is_readable($score_file);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Leaderboard - Animal Quiz Challenge</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eaf4e8;
            color: #344e41;
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
            text-align: center;
            color: #276749;
            font-size: 28px;
            margin-bottom: 30px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e1f0e1;
        }

        th {
            background: #2f855a;
            color: white;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background: #f0f7f0;
        }

        tbody tr:hover {
            background: #e1f0e1;
        }

        td {
            color: #344e41;
        }

        td:first-child {
            font-weight: bold;
            color: #276749;
        }

        td:last-child {
            font-weight: bold;
        }

        .empty-message {
            text-align: center;
            color: #527d66;
            padding: 25px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 13px 24px;
            background: #2f855a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            transition: background 0.2s ease;
        }

        .btn:hover {
            background: #276749;
        }

        .error {
            color: #b00020;
            background: #ffe6e6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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

            th, td {
                padding: 10px;
                font-size: 14px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <h1>🏆 Top 10 Scores 🏆</h1>

    <?php if (!$file_exists): ?>

        <div class="error">
            Score file does not exist.
        </div>

    <?php elseif (!$file_readable): ?>

        <div class="error">
            Score file cannot be read.
        </div>

    <?php endif; ?>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Score</th>
                </tr>
            </thead>

            <tbody>

            <?php if (empty($top_scores)): ?>

                <tr>
                    <td colspan="3" class="empty-message">
                        No scores recorded yet.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach ($top_scores as $i => $entry): ?>

                    <tr>
                        <td>
                            <?= $i + 1 ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $entry['username'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= (int) $entry['score'] ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

    <div class="buttons">

        <a class="btn" href="index.php">
            Back to Home
        </a>

    </div>

</div>

</body>
</html>
