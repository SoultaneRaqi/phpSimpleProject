<?php
require_once "connection.php";





if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');

    $photoName = null;

     if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir); // create if not exists
        
        $tmpName = $_FILES['photo']['tmp_name'];

        $originalName=basename($_FILES['photo']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION); 

        
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($tmpName, $uploadDir . $photoName);
    }


    if ($name && is_numeric($age) ) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, age, photo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $age ,$photoName]);

        header("Location: index.php");
        exit();
    } else {
        $error = "Name and age are required. Age must be a number.";
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
    <h2>Add New Student</h2>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" >
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email"><br>
    Age: <input type="number" name="age" required><br>
     Photo: <input type="file" name="photo" accept="image/*"><br>
    <button type="submit">Add Student</button>
</form>

<a href="index.php">Back to Dashboard</a>
</body>
</html>
