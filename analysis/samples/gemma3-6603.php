

<?php
session_start();

// 1. Initialize the cart (if it doesn't exist)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// 2. Handle Adding Items to the Cart

// Example: Adding an item to the cart
function addToCart($product_id, $product_name, $price, $quantity = 1) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product already exists in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // If the product exists, increment the quantity
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // If the product doesn't exist, add it to the cart
        $_SESSION['cart'][$product_id] = array(
            'name' => $product_name,
            'price' => $price,
            'quantity' => $quantity
        );
    }
}

// Example usage:
// addToCart(1, 'Laptop', 1200, 1);
// addToCart(2, 'Mouse', 25, 2);



// 3. Handle Removing Items from the Cart

function removeCartItem($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Example usage:
// removeCartItem(1);



// 4. Handle Updating Quantity of an Item in the Cart

function updateCartQuantity($product_id, $quantity) {
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
  }
}

// Example usage:
// updateCartQuantity(1, 3);



// 5. Displaying the Cart Contents (for demonstration)

function displayCart() {
    echo "<h2>Your Shopping Cart</h2>";

    if (empty($_SESSION['cart'])) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<ul>";
        foreach ($_SESSION['cart'] as $product_id => $product_details) {
            echo "<li>";
            echo "<strong>Product:</strong> " . $product_details['name'] . "<br>";
            echo "<strong>Price:</strong> $" . $product_details['price'] . "<br>";
            echo "<strong>Quantity:</strong> " . $product_details['quantity'] . "<br>";
            echo "<strong>Total:</strong> $" . $product_details['price'] * $product_details['quantity'] . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    }
}


// Call the display function to show the cart contents
displayCart();
?>
