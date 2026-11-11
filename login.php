<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Please enter your username and password.";
    } else {
        $file = "/home/yzhang130/animal_quiz_private/users.txt";
        $users = file_exists($file)
            ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        $valid = false;

        foreach ($users as $user) {
            $parts = explode("|", $user, 2);

            if (count($parts) === 2 && $parts[0] === $username) {
                if (password_verify($password, $parts[1])) {
                    $valid = true;
                }
                break;
            }
        }


        if ($valid) {
            session_regenerate_id(true);
            $_SESSION["username"] = $username;

            header("Location: index.php");
            exit;
        } else {
           $message = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Animal Quiz Challenge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h1>Animal Quiz Challenge</h1>
        <h2>Login</h2>

        <?php if ($message !== ""): ?>
            <p>
                <?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account?
            <a href="register.php">Register here</a>
        </p>
    </main>
</body>
</html>
