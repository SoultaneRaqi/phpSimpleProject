<?php

require_once"connection.php";

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}


if (!isset($_GET["id"])){
    die("No id provided");
}


$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ;
    $email = $_POST['email'] ;
    $age = $_POST['age'] ;

    $newPhoto = $student['photo'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir);

        $tmpName = $_FILES['photo']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($tmpName, $uploadDir . $fileName);
        


        //delete the old photo file after uploading a new one
        if ($user['photo'] && file_exists("uploads/" . $user['photo'])) {  
            unlink("uploads/" . $user['photo']);
        }


        $newPhoto = $fileName;
    }

    if(empty($name) && empty($email) && empty($age)){
        die("Empty values!!!");
    }
    $update = $pdo->prepare("UPDATE students SET name = ?, email = ?, age = ?, photo = ? WHERE id = ?");
    $update->execute([$name, $email, $age,  $newPhoto , $id]);

    header("Location: index.php");
    exit();
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
    <h2>Edit Student</h2>  
<form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>"><br>
    Email: <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>"><br>
    Age: <input type="number" name="age" value="<?php echo htmlspecialchars($student['age']); ?>"><br>
    <?php if (!empty($user['photo'])): ?>
        Current Photo: <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" width="60"><br>
    <?php endif; ?>
    Change Photo: <input type="file" name="photo" accept="image/*"><br>
    <button type="submit">Update</button>
</form>
<a href="index.php">Back</a>
</body>
</html>