<?php
// Start session to store cart data
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KOIKIES - Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background-color: whitesmoke;
            margin: 0;
            padding: 0;
            color: #333;
        }


        /* Shopping Cart Section */
        #cart {
            padding: 80px 20px;
            text-align: center;
            background-color: #f7f7f7;
        }

        #cart h2 {
            font-size: 2.8em;
            color: #333;
            margin-bottom: 40px;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .cart-item {
            background-color: white;
            border-radius: 30px;
            padding: 20px;
            text-align: left;
            width: 600px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cart-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .cart-item h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .cart-item p {
            font-size: 1.1em;
            margin: 5px 0;
            color: #666;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .quantity-controls button {
            padding: 8px 12px;
            background-color: #9A9F69;
            color: white;
            font-size: 1.2em;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .quantity-controls button:hover {
            background-color: #942828;
        }

        /* Proceed to Checkout Button */
        .cta-button {
            padding: 15px 30px;
            background-color: #9A9F69;
            color: white;
            text-decoration: none;
            font-size: 1.3em;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-top: 50px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #7c7f49;
        }

        
    </style>
</head>
<body>

   
    <!-- Shopping Cart Section -->
    <section id="cart">
        <h2>Your Cart</h2>
        <div class="cart-items" id="cartItems"></div>
        <a href="checkout.php" class="cta-button">Proceed to Checkout</a>
    </section>


    <script>
        // Cart JavaScript
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        function updateCart() {
            const cartContainer = document.getElementById('cartItems');
            cartContainer.innerHTML = ''; // Clear the container

            if (cartItems.length === 0) {
                cartContainer.innerHTML = '<p>Your cart is empty.</p>';
            } else {
                cartItems.forEach(item => {
                    const cartItem = document.createElement('div');
                    cartItem.classList.add('cart-item');

                    cartItem.innerHTML = `
                        <div class="cart-item-details">
                            <h3>${item.name}</h3>
                            <p>RM ${item.price}</p>
                            <p>Total: RM ${(item.price * item.quantity).toFixed(2)}</p>
                        </div>
                        <div class="quantity-controls">
                            <button onclick="changeQuantity('${item.name}', -1)">-</button>
                            <span>${item.quantity}</span>
                            <button onclick="changeQuantity('${item.name}', 1)">+</button>
                        </div>
                        <button class="cta-button" onclick="removeFromCart('${item.name}')">Remove</button>
                    `;
                    cartContainer.appendChild(cartItem);
                });
            }
        }

        function changeQuantity(productName, change) {
            const itemIndex = cartItems.findIndex(item => item.name === productName);
            if (itemIndex !== -1) {
                cartItems[itemIndex].quantity += change;
                if (cartItems[itemIndex].quantity <= 0) {
                    cartItems[itemIndex].quantity = 1; // Prevent negative or zero quantity
                }
                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                updateCart();
            }
        }

        function removeFromCart(productName) {
            cartItems = cartItems.filter(item => item.name !== productName);
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            updateCart();
        }

        window.onload = updateCart;
    </script>

</body>
</html>
