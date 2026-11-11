<?php
require_once __DIR__ . '/questions.php';

// Score file stored outside public_html
const SCORE_FILE = '/home/yzhang130/animal_quiz_private/scores.txt';

// Get a random question based on difficulty
function getRandomQuestion(int $difficulty, array $askedQuestions): ?array {
    global $questions;

    $available = array_values(array_filter(
        $questions,
        fn($q) =>
            $q['difficulty'] === $difficulty &&
            !in_array($q['id'], $askedQuestions, true)
    ));

    // If no questions remain at this difficulty,
    // choose from other unanswered questions.
    if (empty($available)) {
        $available = array_values(array_filter(
            $questions,
            fn($q) => !in_array($q['id'], $askedQuestions, true)
        ));
    }

    return !empty($available)
        ? $available[array_rand($available)]
        : null;
}

// Adaptive difficulty
function updateDifficulty(array $state): array {
    if ($state['streak'] >= 2) {
        $state['difficulty'] = min(3, $state['difficulty'] + 1);
        $state['streak'] = 0;
    } elseif ($state['streak'] <= -2) {
        $state['difficulty'] = max(1, $state['difficulty'] - 1);
        $state['streak'] = 0;
    }

    return $state;
}

// Save player's score
function saveScore(string $username, int $score): bool {

    // Validate username and score
    if (
        !preg_match('/^[A-Za-z0-9_]{3,20}$/D', $username) ||
        $score < 0 ||
        $score > 100 ||
        $score % 10 !== 0
    ) {
        return false;
    }

    $dir = dirname(SCORE_FILE);

    // Directory must exist
    if (!is_dir($dir)) {
        error_log("Score directory does not exist: " . $dir);
        return false;
    }

    // If file already exists, check file permissions
    if (file_exists(SCORE_FILE) && !is_writable(SCORE_FILE)) {
        error_log("Score file is not writable.");
        return false;
    }

    // If file does not exist, directory must be writable
    if (!file_exists(SCORE_FILE) && !is_writable($dir)) {
        error_log("Score directory is not writable.");
        return false;
    }

    $fp = @fopen(SCORE_FILE, 'ab');

    if ($fp === false) {
        error_log("Unable to open score file.");
        return false;
    }

    $success = false;

    if (flock($fp, LOCK_EX)) {

        $line = $username . '|' . $score . "\n";

        $written = fwrite($fp, $line);

        $success = ($written === strlen($line));

        fflush($fp);
        flock($fp, LOCK_UN);
    }

    fclose($fp);

    if (!$success) {
        error_log("Failed to save score.");
    }

    return $success;
}

// Read top scores
function getTopScores(int $limit = 10): array {

    if (!is_file(SCORE_FILE) || !is_readable(SCORE_FILE)) {
        return [];
    }

    $rows = @file(
        SCORE_FILE,
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    );

    if ($rows === false) {
        return [];
    }

    $scores = [];

    foreach ($rows as $row) {

        $parts = explode('|', trim($row));

        if (count($parts) !== 2) {
            continue;
        }

        $username = $parts[0];
        $scoreText = $parts[1];

        if (!preg_match('/^[A-Za-z0-9_]{3,20}$/D', $username)) {
            continue;
        }

        if (!ctype_digit($scoreText)) {
            continue;
        }

        $score = (int)$scoreText;

        if ($score > 100 || $score % 10 !== 0) {
            continue;
        }

        $scores[] = [
            'username' => $username,
            'score' => $score
        ];
    }

    // Highest score first
    usort(
        $scores,
        fn($a, $b) => $b['score'] <=> $a['score']
    );

    return array_slice($scores, 0, max(0, $limit));
}