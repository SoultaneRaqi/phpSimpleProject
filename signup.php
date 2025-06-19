<<<<<<< HEAD
<?php

require_once "connection.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $passwordC = trim($_POST['passwordC']);
    $errors = [];
    
    if(empty($username)){
        $errors[]= "User name is required";
    }

    if (empty($password)) {
        $errors[] = 'Password is required';
    }

    if ($password !== $passwordC) {
        $errors[] = 'Type the same password';
    }

    if(empty($errors)){
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $userExists = $stmt->fetch();
        
        if($userExists){
            $errors[] = 'Username already exists';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->execute([$username, $password]); 
            
            // Redirect to prevent form resubmission
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <h1>Enter your informations to REGISTER</h1>
     
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
     
    <form action="signup.php" method="POST">
        <label for="username">User: </label>
        <input type="text" name="username" id="username" value="<?php echo isset($username) ? $username : ''; ?>"><br><br>
        <label for="password">Password: </label>
        <input type="text" name="password" id="password"><br><br>
        <label for="passwordC">CONFIRM Password: </label>
        <input type="text" name="passwordC" id="passwordC"><br><br>
        <button type="submit" value="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>