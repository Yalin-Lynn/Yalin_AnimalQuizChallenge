<?php
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm = $_POST["confirm"] ?? "";

    if ($username === "" || $password === "" || $confirm === "") {
        $message = "Please fill in all fields.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $message = "Username must be 3-20 letters, numbers, or underscores.";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $file = "/home/yzhang130/animal_quiz_private/users.txt";
        $users = file_exists($file)
            ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        $exists = false;

        foreach ($users as $user) {
            $parts = explode("|", $user, 2);

            if ($parts[0] === $username) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $message = "Username already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $record = $username . "|" . $hash . PHP_EOL;

            if (file_put_contents($file, $record, FILE_APPEND | LOCK_EX) !== false) {
                $message = "Registration successful! You can now log in.";
            } else {
                $message = "Unable to save account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Animal Quiz Challenge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Animal Quiz Challenge</h1>
        <h2>Create an Account</h2>

        <?php if ($message !== ""): ?>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm">Confirm Password:</label>
            <input type="password" id="confirm" name="confirm" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account?
            <a href="login.php">Login here</a>
        </p>
    </main>
</body>
</html>
