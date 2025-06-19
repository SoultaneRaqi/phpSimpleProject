<?php
session_start();
require_once "connection.php";
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);
    
    if(empty($username)) {
        $errors[] = "Username is required";
    }
    if(empty($password)) {
        $errors[] = "Password is required";
    }

    if(empty($errors)) {  
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');  
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if($user && $password === $user['password']) { 
            // Set session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username']
            ];

            if($remember) {
                $cookie_value = $user['id'] . ':' . $user['username'];
                $expiry = time() + 3600; 
                setcookie('remember_me', $cookie_value, $expiry, '/');
            }
            
            header("Location: index.php");
            exit();    
        } else {
            $errors[] = "Username or Password incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login</h1>
    
    <?php if (!empty($errors)): ?>
        <div style="color:red;">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div>
            <label>Username:</label>
            <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <div>
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>
        <button type="submit">Login</button>
    </form>
    
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</body>
</html>