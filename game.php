<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/game_functions.php';

$username = $_SESSION['username'];

// Check why a score could not be saved
function checkScoreSave(int $score, string $username): void {

    $_SESSION['score_saved'] = saveScore($username, $score);
    unset($_SESSION['score_error']);

    if ($_SESSION['score_saved']) {
        return;
    }

    $dir = dirname(SCORE_FILE);

    if (!is_dir($dir)) {
        $_SESSION['score_error'] = 'Score directory does not exist.';
    } elseif (file_exists(SCORE_FILE) && !is_writable(SCORE_FILE)) {
        $_SESSION['score_error'] = 'Score file is not writable.';
    } elseif (!file_exists(SCORE_FILE) && !is_writable($dir)) {
        $_SESSION['score_error'] = 'Score directory is not writable.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/D', $username)) {
        $_SESSION['score_error'] = 'Invalid username for score saving.';
    } else {
        $_SESSION['score_error'] =
            'Score saving failed. Please check server error logs.';
    }
}

// Start a new game
if (
    $_SERVER['REQUEST_METHOD'] === 'GET' &&
    isset($_GET['reset'])
) {
    unset(
        $_SESSION['game_state'],
        $_SESSION['final_score'],
        $_SESSION['score_saved'],
        $_SESSION['score_error'],
        $_SESSION['feedback']
    );

    header('Location: game.php');
    exit;
}

// Initialize game
if (!isset($_SESSION['game_state'])) {

    $_SESSION['game_state'] = [
        'score' => 0,
        'current_q' => 1,
        'difficulty' => 1,
        'streak' => 0,
        'asked' => [],
        'total_questions' => 10,
        'current_question_data' => null,
        'token' => null
    ];
}

$state = &$_SESSION['game_state'];

// Handle answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $answer = $_POST['answer'] ?? null;
    $token = $_POST['token'] ?? null;
    $q = $state['current_question_data'];

    // Prevent duplicate or invalid submissions
    if (
        is_string($answer) &&
        is_string($token) &&
        $q !== null &&
        is_string($state['token']) &&
        hash_equals($state['token'], $token) &&
        in_array($answer, $q['options'], true)
    ) {

        $correct = hash_equals($q['answer'], $answer);

        if ($correct) {

            $state['score'] += 10;

            $state['streak'] =
                max(0, $state['streak']) + 1;

            $_SESSION['feedback'] = 'Correct!';

        } else {

            $state['streak'] =
                min(0, $state['streak']) - 1;

            $_SESSION['feedback'] =
                'Wrong! The correct answer was: ' .
                $q['answer'];
        }

        // Update adaptive difficulty
        $state = updateDifficulty($state);

        // Move to next question
        $state['current_q']++;
        $state['current_question_data'] = null;
        $state['token'] = null;

        // Game completed
        if ($state['current_q'] > $state['total_questions']) {

            $_SESSION['final_score'] = $state['score'];

            // Save score and diagnose errors
            checkScoreSave($state['score'], $username);

            unset(
                $_SESSION['game_state'],
                $_SESSION['feedback']
            );

            header('Location: results.php');
            exit;
        }
    }

    // Prevent form resubmission
    header('Location: game.php');
    exit;
}

// Load next question
if ($state['current_question_data'] === null) {

    $q = getRandomQuestion(
        $state['difficulty'],
        $state['asked']
    );

    // No more questions
    if ($q === null) {

        $_SESSION['final_score'] = $state['score'];

        checkScoreSave($state['score'], $username);

        unset($_SESSION['game_state']);

        header('Location: results.php');
        exit;
    }

    $state['current_question_data'] = $q;

    $state['asked'][] = $q['id'];

    // Unique token for each question
    $state['token'] = bin2hex(random_bytes(16));
}

$q = $state['current_question_data'];

$labels = [
    1 => 'Easy',
    2 => 'Medium',
    3 => 'Hard'
];

$feedback = $_SESSION['feedback'] ?? '';

unset($_SESSION['feedback']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Animal Quiz Challenge - Game</title>

    <style>
        body {
    font-family: Arial, sans-serif;
    background: #eaf4e8;
    color: #344e41;
    padding: 20px;
    margin: 0;
}

.container {
    max-width: 650px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

h1 {
    color: #276749;
    text-align: center;
}

.header {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    border-bottom: 2px solid #e1f0e1;
    padding-bottom: 14px;
}

.question {
    font-size: 1.25em;
    font-weight: bold;
    margin: 25px 0;
    color: #276749;
}

.options {
    display: grid;
    gap: 10px;
}

.option-btn {
    padding: 14px;
    border: 1px solid #cbd5ce;
    border-radius: 8px;
    background: #f0f7f0;
    color: #344e41;
    text-align: left;
    cursor: pointer;
    font-size: 1em;
}

.option-btn:hover {
    background: #d9efdb;
}

.message {
    padding: 12px;
    background: #e1f0e1;
    color: #276749;
    border-radius: 8px;
    margin-top: 16px;
}

.nav {
    margin-top: 22px;
}

.nav a {
    margin-right: 16px;
    color: #276749;
    text-decoration: none;
    font-weight: bold;
}

.nav a:hover {
    text-decoration: underline;
}
    </style>
</head>

<body>

<div class="container">

    <h1>Animal Quiz Challenge</h1>

    <div class="header">

        <span>
            <strong>Player:</strong>
            <?= htmlspecialchars(
                $username,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </span>

        <span>
            <strong>Score:</strong>
            <?= (int)$state['score'] ?> pts
        </span>

        <span>
            <strong>Level:</strong>
            <?= htmlspecialchars(
                $labels[$state['difficulty']],
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </span>

    </div>

    <p>
        Question <?= (int)$state['current_q'] ?>
        of <?= (int)$state['total_questions'] ?>
    </p>

    <?php if ($feedback !== ''): ?>

        <p class="message">
            <?= htmlspecialchars(
                $feedback,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    <?php endif; ?>

    <div class="question">
        <?= htmlspecialchars(
            $q['question'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </div>

    <form method="post"
          action="game.php"
          class="options">

        <input
            type="hidden"
            name="token"
            value="<?= htmlspecialchars(
                $state['token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <?php foreach ($q['options'] as $option): ?>

            <button
                class="option-btn"
                type="submit"
                name="answer"
                value="<?= htmlspecialchars(
                    $option,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
                <?= htmlspecialchars(
                    $option,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </button>

        <?php endforeach; ?>

    </form>

    <div class="nav">
        <a href="index.php">Home</a>
        <a href="logout.php">Log Out</a>
    </div>

</div>

</body>
</html>