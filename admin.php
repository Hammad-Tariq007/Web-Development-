<?php
session_start();
include 'connect.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

$message = ""; 


if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['image']['name']);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        
        $sql = "INSERT INTO product (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')";

        if (mysqli_query($conn, $sql)) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product: " . mysqli_error($conn);
        }
    } else {
        $message = "Error uploading image.";
    }
}

// Display all products
$query = "SELECT * FROM product";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
       
        :root {
            --main-bg-color: linear-gradient(135deg, #74ebd5, #ACB6E5);
            --glass-bg-color: rgba(255, 255, 255, 0.2);
            --font-color: #333;
            --border-color: rgba(255, 255, 255, 0.5);
            --button-color: #007bff;
            --button-hover-color: #0056b3;
            --input-bg-color: rgba(255, 255, 255, 0.15);
            --input-focus-color: #ffffff;
            --table-bg-color: rgba(255, 255, 255, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--main-bg-color);
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top */
            min-height: 100vh; /* Allow body to expand */
            padding: 20px;
            overflow-y: auto;
        }

        .container {
            backdrop-filter: blur(10px);
            background: var(--glass-bg-color);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            width: 90%;
            max-width: 1000px;
            text-align: center;
            color: var(--font-color);
            overflow: auto; /* Allow scrolling */
        }

        h2, h3 {
            margin-bottom: 20px;
            color: var(--font-color);
        }

        h2{
            color: #1d3557;
        }

        .product-form input, 
        .product-form textarea, 
        .product-form button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background-color: var(--input-bg-color);
            font-size: 16px;
            transition: all 0.3s ease;
            color: black;
        }

        .product-form input:focus, 
        .product-form textarea:focus {
            border-color: var(--input-focus-color);
            outline: none;
        }

        .product-form button {
            background-color: var(--button-color);
            color: white;
            cursor: pointer;
        }

        .product-form button:hover {
            background-color: var(--button-hover-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: var(--table-bg-color);
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        table th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #1d3557;
        }

        table td img {
            width: 50px;
            height: auto;
        }

        .action-links a {
            color: var(--button-color);
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            margin-right: 15px;
            color: var(--button-color);
        }

        /* Success Message Styling */
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Dark Mode Styles */
        body.dark-mode {
            background: linear-gradient(135deg, #232526, #414345);
        }

        body.dark-mode .container {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        body.dark-mode h2, 
        body.dark-mode h3, 
        body.dark-mode .dark-mode-toggle {
            color: white;
        }

        body.dark-mode .product-form input, 
        body.dark-mode .product-form textarea {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        body.dark-mode .product-form button {
            background-color: #5A9;
        }

        body.dark-mode table th, body.dark-mode table td {
            color: white;
        }

        body.dark-mode .links a {
            color: #9CFF2E;
        }

        body.dark-mode .message {
            background-color: #155724;
            color: #d4edda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>

      
        <?php if (!empty($message)) { ?>
            <div class="message" id="message"><?php echo $message; ?></div>
        <?php } ?>


        <div class="product-form">
            <h3>Add New Product</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Product Name" required>
                <textarea name="description" placeholder="Product Description" required></textarea>
                <input type="number" name="price" placeholder="Price" required>
                <input type="file" name="image" required>
                <button type="submit" name="add_product">Add Product</button>
            </form>
        </div>

    
        <h3>Existing Products</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php while ($product = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo '$' . $product['price']; ?></td>
                    <td><img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
                    <td class="action-links">
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> | 
                        <a href="delete_product.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <div class="links">
            <a href="index.php">Back to Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <script>
       
        setTimeout(() => {
            const message = document.getElementById('message');
            if (message) {
                message.style.display = 'none';
            }
        }, 5000); 
    </script>
</body>
</html>