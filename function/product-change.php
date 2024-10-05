<?php
include 'connection.php';

$product_id = $_POST['product_id'];
$op = $_POST['op'];

// Define the folder where product images will be uploaded
$uploadDir = 'C:/xampp/htdocs/Online-Grocery-Store-Using-PHP/GardenRoots/products/';


if ($op == "image") {
    // Handle image upload
    if (isset($_FILES["productIMG"]) && $_FILES["productIMG"]["error"] == UPLOAD_ERR_OK) {
        // Get the temporary file path and the original file name
        $imageTmpPath = $_FILES["productIMG"]["tmp_name"];
        $imageName = $_FILES["productIMG"]["name"];

        // Clean the file name (remove spaces and special characters)
        $imageName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $imageName);
        $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the uploaded file is an allowed type
        if (in_array($imageExtension, $allowedExtensions)) {
            // Create a unique file name using the product ID and timestamp
            $newImageName = $product_id . '_' . time() . '.' . $imageExtension;

            // Full destination path where the image will be saved
            $imageDestPath = $uploadDir . $newImageName;

            // Move the uploaded file to the destination directory
            if (move_uploaded_file($imageTmpPath, $imageDestPath)) {
                // Update the database with the new image file name
                $productUpdate = "UPDATE `product` SET `product_image`='$newImageName' WHERE `product_id`='$product_id';";
                $productUpdateResult = $mysqli->query($productUpdate);

                if ($productUpdateResult) {
                    echo "Image uploaded and updated successfully!";
                } else {
                    echo "Database update for image failed.";
                }
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Error: Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        echo "Error: No file uploaded or an error occurred during file upload.";
    }
} 
else if($op == "name"){
    $name = $_POST['name'];
    $productUpdate = "UPDATE `product` SET `product`='$name' WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}
else if($op == "price"){
    $price = $_POST['price'];
    $productUpdate = "UPDATE `product` SET `price`='$price' WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}
else if($op == "discount"){
    $discount = $_POST['discount'];
    $productUpdate = "UPDATE `product` SET `discount`='$discount' WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}
else if($op == "category"){
    $category = $_POST['category'];
    $productUpdate = "UPDATE `product` SET `category`='$category' WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}
else if($op == "featured"){
    $featured = $_POST['featured'];
    $productUpdate = "UPDATE `product` SET `featured`='$featured' WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}
else if($op == "remove"){
    $productUpdate = "DELETE FROM `product` WHERE `product_id`='$product_id';";
    $productUpdateResult = $mysqli->query($productUpdate);
}

$mysqli->close();

// Redirect back to the previous page
echo '<script type="text/JavaScript"> history.back();</script>';
?>
