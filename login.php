<?php
include "koneksi.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Username dan password harus diisi.";
    } else {
        $query = $pdo->prepare('SELECT username, password, role FROM users WHERE username = :username');
        $query->bindParam(':username', $username);
        $query->execute();
        
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: user_dashboard.php');
            }
            exit;
        } else {
            $message = "Username atau password salah.";
        }
    }
}
?>



<html>
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <script>
        function showNotification(message) {
            alert(message);
        }

    
        <?php if ($login_success): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('Login berhasil!');
            });
        <?php endif; ?>
    </script>

        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
        <div class="center">
            <h1>Login</h1>
            <form action="" method="POST">
                <div class="txt_field">
                    <input type="text" name="username" id="username" required>
                    <span></span>
                    <label>Username</label>
                </div>
                <div class="txt_field">
                    <input type="password" name="password" id="password" required>
                    <span></span>
                    <label>Password</label>
                </div>
                <div class="pass">
                    <label>Remember me</label>
                    <input type="checkbox">
                </div>
                <input type="submit" value="Login">
                <div class="signup_link">
                    Belum Memiliki Akun? <a href="signup.php">SignUp</a>
                </div>
            </form>
        </div>
    </body>
</html>