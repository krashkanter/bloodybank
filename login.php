<?php
global $conn;
session_start();
require_once 'php/connect.php'; // Adjust the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $sql = "SELECT * FROM Users WHERE Username = ? AND Role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];

            if ($role === 'manager') {
                header("Location: dashboard-manager.html");
            } elseif ($role === 'analyst') {
                header("Location: dashboard-analyst.html");
            } else {
                header("Location: index.html");
            }
            exit;
        }
    }
    echo "<script>alert('Invalid username or password.'); window.location.href = 'login.php?role={$role}';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Blood Bank System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: #e76f51;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 10px;
        }

        main {
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        .form-section {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-grid {
            display: grid;
            gap: 15px;
        }

        input, button {
            padding: 10px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #e76f51;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #d65a3f;
        }

        .note {
            font-size: 0.9rem;
            color: #555;
            margin-top: 10px;
        }

        .role-display {
            font-weight: bold;
            background: #f2f2f2;
            padding: 8px;
            border-radius: 6px;
            margin-top: 5px;
            text-transform: capitalize;
        }
    </style>
</head>
<body>
<header>
    <h1>🔐 Login Portal</h1>
    <nav>
        <ul>
            <li><a href="index.html">🏠 Home</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <h2>Login to Continue</h2>

        <div id="roleContainer" style="display:none;">
            <label>Role:</label>
            <div class="role-display" id="displayRole">-</div>
        </div>

        <form action="login.php" method="POST" class="form-grid">
            <input type="hidden" name="role" id="hiddenRole">

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p class="note">Only authorized staff can access this section.</p>
    </section>
</main>

<footer>
    <p>&copy; 2025 Blood Bank Management System</p>
</footer>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role');

        if (role) {
            document.getElementById('roleContainer').style.display = 'block';
            document.getElementById('displayRole').textContent = role;
            document.getElementById('hiddenRole').value = role;
        }
    });
</script>
</body>
</html>
