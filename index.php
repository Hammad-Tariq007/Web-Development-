<?php
session_start();
include 'connect.php';

// Initialize the cart if it's not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle "Add to Cart" functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    
    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => 1
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Shop</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 2rem;
        }

        .container {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Full-Screen Landing Section */
        .full-screen-bg {
            background: url('uploads/12.jpg') no-repeat center center / cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            width: 100%;
        }

        .full-screen-bg h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .full-screen-bg p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #e63946; /* Updated to match the red tone in the background */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            margin: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background-color: #d62828; /* Darker red on hover */
        }

        .btn.admin-btn {
            background-color: #457b9d; /* Blue-gray tone to complement the background */
        }

        .btn.admin-btn:hover {
            background-color: #1d3557; /* Darker blue-gray on hover */
        }

        .logout-btn {
            background-color: #f4a261; /* Warm orange tone */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #e76f51; /* Darker orange on hover */
        }

        .products-section {
            margin-top: 50px;
        }

        .products-section h2 {
            color: #1d3557;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-card h3 {
            font-size: 1.25rem;
            color: #333;
        }

        .product-card p {
            font-size: 1rem;
            color: #666;
            margin-bottom: 10px;
        }

        .product-card .price {
            font-size: 1.5rem;
            color: #28a745;
            font-weight: bold;
        }

        .cart-btn {
            background-color: #457b9d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        .cart-btn:hover {
            background-color: #1d3557;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Full-Screen Landing Section -->
    <div id="landing-section" class="full-screen-bg">
        <h1>Discover Premium Shoes</h1>
        <p>Browse through our exclusive collection and find your perfect pair.</p>
        <div>
            <?php if (!isset($_SESSION['username'])) { ?>
                <a href="login.php" class="btn">Login</a>
                <a href="signup.php" class="btn">Sign Up</a>
            <?php } else { ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                    <a href="admin.php" class="btn admin-btn">Admin Panel</a>
                <?php } ?>
                <a href="logout.php" class="btn logout-btn">Logout</a>
            <?php } ?>
        </div>
    </div>

    <!-- Product Section (Only visible after login) -->
    <?php if (isset($_SESSION['username'])) { ?>
    <div id="products-section" class="products-section">
        <h2>Products Available</h2>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM product";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($product = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="product-card">
                        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        <h3><?php echo $product['name']; ?></h3>
                        <p><?php echo $product['description']; ?></p>
                        <p class="price">$<?php echo $product['price']; ?></p>

                        <!-- Add to Cart Form -->
                        <form method="post" action="">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                            <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No products available at the moment.</p>";
            }
            ?>
        </div>
    </div>
    <?php } ?>
</div>

</body>
</html>
