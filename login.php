<?php
session_start();
include "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $password = md5($_POST["password"]);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($kon, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        if (isset($_POST['remember'])) {
            setcookie("username", $username, time() + 3600, "/");
            setcookie("password", $password, time() + 3600, "/");
        }

        header("Location: index.php");
        exit;
    } else {
        echo "<div class='alert'>Username atau Password salah.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 2em;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            margin-bottom: 1em;
            color: #333;
        }

        .form-group {
            margin-bottom: 1em;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.3em;
            color: #555;
        }

        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="checkbox"] {
            width: 100%;
            padding: 0.7em;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1em;
        }

        .form-group input[type="checkbox"] {
            width: auto;
            margin-right: 0.5em;
        }

        .form-group .remember-me {
            display: flex;
            align-items: right;
        }

        .form-group input[type="checkbox"] + label {
            font-size: 0.9em;
            color: #666;
        }

        button {
            width: 100%;
            padding: 0.8em;
            font-size: 1em;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            color: red;
            margin-bottom: 1em;
            font-size: 0.9em;
        }

        @media (max-width: 400px) {
            .container {
                padding: 1.5em;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error_message)): ?>
        <div class="alert"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group remember-me">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Remember Me</label>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
