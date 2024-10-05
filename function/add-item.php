<?php
session_start();
$user = 'root';
$password = '';
$database = 'garden_roots';
$servername = 'localhost:3306';
$mysqli = new mysqli($servername, $user, $password, $database);

// Check if image is uploaded
if (isset($_FILES['image'])) {
    $imageName = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $uploadDir = 'C:/xampp/htdocs/Online-Grocery-Store-Using-PHP/GardenRoots/products/';
    $imagePath = $uploadDir . $imageName;

    // Move the uploaded file to the target folder
    if (move_uploaded_file($imageTmpName, $imagePath)) {
        $name = $_POST['product'];
        $price = $_POST['price'];
        $discount = $_POST['discount'];
        $type = $_POST['category'];

        // Insert into database
        $sql = "INSERT INTO `product` (`product_id`, `product`, `price`, `discount`, `product_image`, `category`, `sales`, `ordered`, `featured`) 
                VALUES (NULL, '$name', '$price', '$discount', '$imageName', '$type', '0', '0', '0')";

        if ($mysqli->query($sql)) {
            echo "<script>
                    alert('Product added successfully!');
                    
                  </script>";
        } else {
            echo "<script>alert('Database error: " . $mysqli->error . "');</script>";
        }
    } else {
        echo "<script>alert('Image upload failed');</script>";
    }
} else {
    echo "<script>alert('No image selected');</script>";
}
$mysqli->close();
?>