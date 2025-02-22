<?php
include 'connect.php';
$message = "";

// Check if the signup form was submitted
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if the username already exists
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($result) > 0) {
        $message = "User already exists. <a href='login.php'>Login here</a>";
    } else {
        // Insert new user into the database
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        
        if (mysqli_query($conn, $query)) {
            $message = "Signup successful! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        /* CSS Variables for consistent design */
        :root {
            --main-bg-color: linear-gradient(135deg, #74ebd5, #ACB6E5);
            --glass-bg-color: rgba(255, 255, 255, 0.2);
            --font-color: #333;
            --border-color: rgba(255, 255, 255, 0.5);
            --button-color: #007bff;
            --button-hover-color: #0056b3;
            --input-bg-color: rgba(255, 255, 255, 0.15);
            --input-focus-color: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--main-bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            backdrop-filter: blur(10px);
            background: var(--glass-bg-color);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        h2 {
            color: var(--font-color);
            font-size: 26px;
            margin-bottom: 20px;
        }

        .signup-container input, .signup-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background-color: var(--input-bg-color);
            color: var(rgba(255, 255, 255, 0.15));
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .signup-container input:focus, .signup-container select:focus {
            border-color: var(--input-focus-color);
            outline: none;
        }

        button {
            background-color: var(--button-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--button-hover-color);
        }

        .signup-container a {
            color: var(--button-color);
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            display: inline-block;
        }

        .signup-container a:hover {
            text-decoration: underline;
        }

        .message {
            color: green;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .dark-mode-toggle {
            margin-top: 15px;
            display: inline-block;
            cursor: pointer;
            font-size: 14px;
            color: var(--font-color);
        }

        body .signup-container select,option {
            color: black;
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background: linear-gradient(135deg, #232526, #414345);
        }

        body.dark-mode .signup-container {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        body.dark-mode h2, body.dark-mode .dark-mode-toggle {
            color: white;
        }

        body.dark-mode .signup-container input, 
        body.dark-mode .signup-container select {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.5);
        }

        body.dark-mode button {
            background-color: #5A9;
        }

        body.dark-mode .signup-container a {
            color: #9CFF2E;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .signup-container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Create Your Account</h2>

        <!-- Display message if there is one -->
        <?php if ($message != "") { ?>
            <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : 'message'; ?>">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Enter your username" required>
            <input type="password" name="password" placeholder="Create a password" required>
            <select name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" name="signup">Sign Up</button>
        </form>
        <a href="login.php">Already have an account? Log in here</a>
        <div class="dark-mode-toggle" onclick="toggleDarkMode()">Toggle Dark Mode</div>
    </div>

    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
