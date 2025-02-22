<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if user exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    // Check if any row is returned
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if ($user['password'] == $password) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User does not exist.";
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
        /* CSS Variables for Consistency */
        :root {
            --primary-color: #007BFF;
            --primary-hover: #0056b3;
            --bg-gradient: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            --glass-bg: rgba(255, 255, 255, 0.2);
            --glass-border: rgba(255, 255, 255, 0.4);
            --font-color: #3A3A3A;
            --error-color: #ff6b6b;
        }

        /* General Styles */
        body {
            background: var(--bg-gradient);
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            backdrop-filter: blur(10px);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.6s ease-out;
        }

        h1 {
            color: var(--font-color);
            margin-bottom: 20px;
            font-size: 2rem;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.6);
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .login-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .login-btn:hover {
            background-color: var(--primary-hover);
        }

        .forgot-password, .social-login {
            margin-top: 15px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .social-login button {
            margin: 5px;
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48%;
        }

        .social-login button img {
            width: 20px;
            margin-right: 8px;
        }

        .google-btn {
            background-color: #ea4335;
            color: white;
        }

        .google-btn:hover {
            background-color: #c62828;
        }

        .facebook-btn {
            background-color: #3b5998;
            color: white;
        }

        .facebook-btn:hover {
            background-color: #2d4373;
        }

        .error {
            color: var(--error-color);
            margin-top: 10px;
            font-size: 14px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive for Mobile */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            input[type="text"], input[type="password"] {
                font-size: 14px;
            }

            .login-btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <div class="social-login">
            <button class="google-btn">
                <img src="uploads/google_logo.png" alt="Google"> Google
            </button>
            <button class="facebook-btn">
                <img src="uploads/fb_logo.png" alt="Facebook"> Facebook
            </button>
        </div>
    </div>
</body>
</html>
