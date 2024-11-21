<?php
// Connect to your database (adjust with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "koikies"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products"; // Replace with your actual table name
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOIKIES - Featured Products</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
            color: #4A4A4A;
        }

        header {
            background-color: #9A9F69;
            color: white;
            padding: 15px;
            position: static;
            width: 100%;
            top: 0;
            z-index: 1000;
            text-align: center;
        }

        header .logo h1 {
            margin: 0;
            font-size: 2.5em;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 1.2em;
        }
        
        nav a:hover {
            color:#C4E6A6;
        }
        
        /* Account Icon */
         .account-icon {
            margin-right: 20px; 
       }

       .account-icon img {
            width: 25px;
            height: 25px;
        }
        
        /* Cart Container */
        .cart-container {
            display: flex;
            align-items: center;
            position: relative;
            margin-left: 20px; /* Space from the navigation */
        }

        .cart-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .cart-icon img {
            width: 30px; 
            height: 30px; 
        }

        .cart-count {
            position: absolute;
            top: -10px; /* Position slightly above */
            right: -10px; /* Position slightly to the right */
            background-color: white;
            color: black;
            border-radius: 50%;
            padding: 3px 6px;
            font-size: 0.8em;
            width: 15px; /* Fixed width */
            height: 15px; /* Fixed height */
            display: flex; /* Flexbox to center content */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            border: 1px solid white; /* Optional: white border for better visibility */
        }

        /* Featured Products Section */
        #featured-products {
            padding: 80px 20px;
            text-align: center;
            background-color: #f9f9f9;
        }

        #featured-products h2 {
            font-size: 2.5em;
            margin-bottom: 30px;
        }

        .product-gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .product-item {
            background-color: white;
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .product-item img {
            width: 100%;
            height: 200px; /* Set height for all images */
            object-fit: cover; /* Ensures that the images fit within the container while maintaining aspect ratio */
            border-radius: 5px;
        }

        .product-item h3 {
            font-size: 1.5em;
            margin: 15px 0;
        }

        .product-item p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        .cta-button {
            padding: 10px 20px;
            background-color: #888E4D;
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            border-radius: 5px;
            cursor: pointer; /* Change to pointer to indicate clickable */
        }

        .cta-button:hover {
            background-color: #6c6f3b;
        }

        /* Footer */
        footer {
            background-color: #9A9F69;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
        }

        footer p {
            margin: 0;
        }
        
        /* Back to Top Button */
        .back-to-top {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #9A9F69;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            position: fixed;
            bottom: 30px;
            right: 30px;
            font-size: 1.2em;
            text-align: center;
            line-height: 40px;
            transition: background-color 0.3s ease;
        }

        .back-to-top:hover {
            background-color: #787B51;
        }
    </style>
</head>
<body>

   <!-- Header -->
    <header>
        <div class="logo">
            <h1>𝙺𝙾𝙸𝙺𝙸𝙴𝚂</h1>
        </div>
        <nav>
            <ul>
                <li><a href="KOIKIES.html">Home</a></li>
                <li><a href="Products.php">Shop</a></li>
                <li><a href="KOIKIES - ABOUT US.html">About Us</a></li>
                <li><a href="KOIKIES - CONTACT US.html">Contact</a></li>
            </ul>
        </nav>

        <!-- Account Icon -->
        <div class="cart-container">
            <div class="account-icon">
                <a href="usersignin.html">
                    <img src="usericon.png" alt="Account" width="25" height="25">
                </a>
            </div>

            <div class="cart-container">
                <div class="cart-icon">
                    <a href="shoppingcart.php">
                        <img src="black_shoppictbasket_1484336511-1.png" alt="Cart" width="50" height="50">
                        <span class="cart-count" id="cartCount">0</span> <!-- Cart item count -->
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Featured Products Section -->
    <section id="featured-products">
        <h2>Our Products</h2>
        <div class="product-gallery">
            <?php
            if ($result->num_rows > 0) {
                // Loop through the products
                while($row = $result->fetch_assoc()) {
                    $productName = htmlspecialchars($row['name']);
                    $productDescription = htmlspecialchars($row['description']);
                    $productPrice = htmlspecialchars($row['price']);
                    $productImage = htmlspecialchars($row['image_url']);
            ?>
                <!-- Product Item -->
                <div class="product-item">
                    <img src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>">
                    <h3><?php echo $productName; ?></h3>
                    <p><?php echo $productDescription; ?></p>
                    <p>RM <?php echo $productPrice; ?></p>
                    <a href="#" class="cta-button" onclick="addToCart({ name: '<?php echo $productName; ?>', price: <?php echo $productPrice; ?> })">Add To Cart</a>
                </div>
            <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2024 KOIKIES. All Rights Reserved.</p>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top">↑</a>

    <script>
        // Cart JavaScript
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        function addToCart(product) {
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            let existingProduct = cartItems.find(item => item.name === product.name);
            
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cartItems.push({ ...product, quantity: 1 });
            }
            
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCartCount();
            alert(`${product.name} added to cart!`);
        }

        function updateCartCount() {
            let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
            let cartCount = cartItems.reduce((acc, item) => acc + item.quantity, 0);
            document.getElementById('cartCount').innerText = cartCount;
        }

        window.onload = updateCartCount;

    </script>
</body>
</html>
