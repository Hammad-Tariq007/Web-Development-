<?php
session_start();
include 'connect.php';

// Check if the user is admin, restrict access if not
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

// Get the product ID from the query string
$product_id = $_GET['id'];

// Fetch product details from the database
$query = "SELECT * FROM product WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found!";
    exit();
}

// Handle product update
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Check if an image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        // Update with new image
        $sql = "UPDATE product SET name = '$name', description = '$description', price = '$price', image = '$image' WHERE id = '$product_id'";
    } else {
        // Update without changing the image
        $sql = "UPDATE product SET name = '$name', description = '$description', price = '$price' WHERE id = '$product_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully!";
        header("Location: admin.php");
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .edit-container {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .edit-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .edit-container h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #6c757d;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #007bff;
        }

        .form-group input[type="file"] {
            padding: 5px;
            background-color: #f1f1f1;
            border: 1px solid #e1e1e1;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Image preview styling */
        .image-preview {
            width: 100px;
            height: 100px;
            margin: 10px auto;
            display: block;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

    </style>
</head>
<body>

<div class="edit-container">
    <h2>Edit Product</h2>

    <!-- Display current image preview -->
    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="image-preview">

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Upload New Image (Optional)</label>
            <input type="file" id="image" name="image">
        </div>
        <div class="form-group">
            <button type="submit" name="update_product">Update Product</button>
        </div>
    </form>

    <div class="back-link">
        <a href="admin.php">Back to Admin Panel</a>
    </div>
</div>

</body>
</html>
