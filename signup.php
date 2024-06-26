<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        echo "Semua bidang harus diisi.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert the new user into the database
            $query = $pdo->prepare('INSERT INTO users (username, password, role) VALUES (:username, :password, :role)');
            $query->bindParam(':username', $username);
            $query->bindParam(':password', $hashedPassword);
            $query->bindParam(':role', $role);
            $query->execute();

            echo "Registrasi berhasil. <a href='login.php'>Login di sini</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo "Username sudah digunakan.";
            } else {
                echo "Terjadi kesalahan: " . $e->getMessage();
            }
        }
    }
}
?>


<html>
    <head>
        <meta charset="utf-8">
        <title>SignUp</title>
        <link rel="stylesheet" href="css/signup.css">
    
    </head>
    <body>
        <div class="center">
            <h1>SIGN UP</h1>
            <form action="" method="post" autocomplete="on">
                <div class="sign">
                    <input type="text" name="username">
                    <span></span>
                    <label>Username</label>
                </div>
                <div class="sign">
                    <input type="password" name="password">
                    <span></span>
                    <label>Password</label>
                </div>
                <div>
                <label for="role">Role:</label>
                    <select name="role" id="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="send">
                    <span></span>
                    <input type="submit" value="Daftar">
                </div>
            </form>
        </div>
    </body>
</html>