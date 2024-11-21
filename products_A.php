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

// Handle Add, Edit, and Delete Product functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        // Add new product
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_name = $_FILES['image']['name'];
            $image_path = "uploads/" . $image_name;

            // Move the uploaded file to the "uploads" directory
            move_uploaded_file($image_tmp, $image_path);
        }

        // Insert into database
        $query = "INSERT INTO products (name, description, price, image_url, created_at) VALUES ('$name', '$description', '$price', '$image_path', NOW())";
        $conn->query($query); // Execute query

        // Redirect after adding
        header('Location: products_A.php');
    }

    if (isset($_POST['edit_product'])) {
        // Edit an existing product
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Handle image upload for edit
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_name = $_FILES['image']['name'];
            $image_path = "uploads/" . $image_name;

            // Move the uploaded file to the "uploads" directory
            move_uploaded_file($image_tmp, $image_path);

            // Update image in the database
            $query = "UPDATE products SET name='$name', description='$description', price='$price', image_url='$image_path', updated_at=NOW() WHERE product_id='$product_id'";
        } else {
            // Update without changing the image
            $query = "UPDATE products SET name='$name', description='$description', price='$price', updated_at=NOW() WHERE product_id='$product_id'";
        }

        $conn->query($query); // Execute query
        header('Location: products_A.php');
    }

    if (isset($_POST['delete_product'])) {
        // Delete a product
        $product_id = $_POST['product_id'];
        $query = "DELETE FROM products WHERE product_id='$product_id'";
        $conn->query($query); // Execute query

        header('Location: products_A.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOIKIES - Admin Panel</title>
    <link rel="stylesheet" href="style.css">
      <?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "koikies"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        // Add product
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $conn->real_escape_string($_POST['price']);
        $image_path = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = basename($_FILES['image']['name']);
            $image_path = "uploads/" . $image_name;

            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        }

        $query = "INSERT INTO products (name, description, price, image_url, created_at) 
                  VALUES ('$name', '$description', '$price', '$image_path', NOW())";
        $conn->query($query);
        header('Location: products_A.php');
    }

    if (isset($_POST['edit_product'])) {
        // Edit product
        $product_id = $conn->real_escape_string($_POST['product_id']);
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = $conn->real_escape_string($_POST['price']);
        $image_path = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = basename($_FILES['image']['name']);
            $image_path = "uploads/" . $image_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

            $query = "UPDATE products SET name='$name', description='$description', price='$price', 
                      image_url='$image_path', updated_at=NOW() WHERE product_id='$product_id'";
        } else {
            $query = "UPDATE products SET name='$name', description='$description', price='$price', 
                      updated_at=NOW() WHERE product_id='$product_id'";
        }
        $conn->query($query);
        header('Location: products_A.php');
    }

    if (isset($_POST['delete_product'])) {
        // Delete product
        $product_id = $conn->real_escape_string($_POST['product_id']);
        $query = "DELETE FROM products WHERE product_id='$product_id'";
        $conn->query($query);
        header('Location: products_A.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOIKIES - Manage Products</title>
    <link rel="stylesheet" href="style.css">
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

        #product-management {
            padding: 40px 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            border-radius: 8px;
            max-width: 1100px;
            margin: 50px auto;
        }

        #product-management h2 {
            font-size: 2.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

       form {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="number"],
        textarea,
        input[type="file"] {
            padding: 10px;
            margin: 10px 0;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1.1em;
            box-sizing: border-box;
        }

        form input[type="file"] {
            margin-bottom: 20px; /* Adds space below the file input */
        }

        form button {
            padding: 10px 20px;
            background-color: #888E4D;
            color: white;
            font-size: 1.1em;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            margin-top: 20px; /* Adds space above the submit button */
        }

        form button:hover {
            background-color: #6c6f3b;
        }

        table {
            width: 100%;
            margin: 40px 0;
            border-collapse: collapse;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 18px;
            text-align: center;
            font-size: 1.1em;
        }

        th {
            background-color: #9A9F69;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #F9F9F9;
        }

        td img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .actions button {
            padding: 8px 15px;
            margin: 5px;
            background-color: #FF6347;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .actions button:hover {
            background-color: #E85B4A;
        }

        footer {
            background-color: #9A9F69;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
            font-size: 1em;
        }

        footer p {
            margin: 0;
        }

        .back-to-top {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            background-color: #A9B83D;
            color: white;
            border-radius: 50%;
            position: fixed;
            bottom: 30px;
            right: 30px;
            font-size: 1.5em;
            text-align: center;
            line-height: 50px;
            transition: background-color 0.3s ease;
        }

        .back-to-top:hover {
            background-color: #8A9B2F;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <h1>𝙺𝙾𝙸𝙺𝙸𝙴𝚂 - Admin</h1>
    </div>
    <nav>
       <ul>
          <li><a href="home_A.html">Home</a></li>
          <li><a href="products_A.php">Manage Products</a></li>
            </ul>
    </nav>

     <!-- Account Icon -->
    <div class="cart-container">
        <div class="account-icon">
            <a href="login_A.html">
                <img src="usericon.png" alt="Account" width="25" height="25">
            </a>
        </div>
</header>

<section id="product-management">
    <h2>Manage Products</h2>
    <form method="POST" action="products_A.php" enctype="multipart/form-data">
        <h3>Add New Product</h3>
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Product Description" required></textarea>
        <input type="number" name="price" placeholder="Price (RM)" step="0.01" required>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h3>Existing Products</h3>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM products ORDER BY created_at DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td><img src='" . htmlspecialchars($row['image_url']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width: 100px;'></td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['description']) . "</td>
                        <td>RM " . htmlspecialchars($row['price']) . "</td>
                        <td>
                            <form method='POST' action='products_A.php' enctype='multipart/form-data'>
                                <input type='hidden' name='product_id' value='" . htmlspecialchars($row['product_id']) . "'>
                                <input type='text' name='name' value='" . htmlspecialchars($row['name']) . "' required>
                                <textarea name='description'>" . htmlspecialchars($row['description']) . "</textarea>
                                <input type='number' name='price' value='" . htmlspecialchars($row['price']) . "' step='0.01' required>
                                <input type='file' name='image' accept='image/*'>
                                <button type='submit' name='edit_product'>Edit</button>
                                <button type='submit' name='delete_product'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<footer>
    <p>© 2024 KOIKIES. All Rights Reserved.</p>
</footer>
</body>
</html>

<?php
$conn->close();
?>


