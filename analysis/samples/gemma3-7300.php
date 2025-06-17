

<?php

// Database Connection (Replace with your database credentials)
$db_host = "localhost";
$db_name = "shopping_cart";
$db_user = "your_username";
$db_password = "your_password";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to add a product to the cart
function addToCart($product_id, $quantity, $user_id = null) {
    global $pdo;

    // Check if the user is logged in (optional - for user-specific carts)
    if ($user_id) {
        //  Implement user authentication logic here.
        //  For this example, we'll assume the user is logged in (replace this check)
        //  You'd typically check for a session or cookie with a valid user ID.
        $stmt = $pdo->prepare("SELECT * FROM carts WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $cart = null; // Guest cart
    }

    if ($cart) {
        // Product already in the cart, update the quantity
        $product_id_exists = false;
        foreach ($cart['items'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                $item['quantity'] += $quantity;
                $item['total_price'] = $item['product_price'] * $item['quantity'];
                $cart['items'][$key] = $item;
                $product_id_exists = true;
                break;
            }
        }

        if (!$product_id_exists) {
            $cart['items'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'product_price' => getProductPrice($product_id), // Fetch product price
                'total_price' => getProductPrice($product_id) * $quantity
            ];
        }
    } else {
        // Create a new cart for the user
        $cart = [
            'user_id' => $user_id,
            'items' => [[
                'product_id' => $product_id,
                'quantity' => $quantity,
                'product_price' => getProductPrice($product_id),
                'total_price' => getProductPrice($product_id) * $quantity
            ]]
        ];
    }

    // Update the cart in the database
    if ($user_id) {
        $stmt = $pdo->prepare("UPDATE carts SET items = :items WHERE user_id = :user_id");
        $stmt->execute(['items' => json_encode($cart['items']), 'user_id' => $user_id]);
    } else {
        // For guest carts, we don't store them in the database, just in session
        // This is simplified and requires session handling in your front-end.
        // You'd typically store the cart as a session variable.
        // The database store of a guest cart is only for persistent storage across multiple sessions
        //  For this example, we're creating the cart in memory (which isn't a production-ready solution)
        $cart['user_id'] = null;
    }

    return $cart;
}


// Function to get a product's price (you'll need to fetch this from your database)
function getProductPrice($product_id) {
    // Replace with your database query to retrieve the product price
    // This is just a placeholder example
    // In a real application, you'd fetch the product price from your database.

    //Example:
    $product_prices = [
        1 => 10.00,
        2 => 20.00,
        3 => 5.00
    ];
    return $product_prices[$product_id] ?? 0; // Return 0 if not found.
}



// Function to display the shopping cart
function displayCart($cart) {
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart['items'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart['items'] as $item) {
        echo "<li>";
        echo "Product ID: " . $item['product_id'] . "<br>";
        echo "Quantity: " . $item['quantity'] . "<br>";
        echo "Price: $" . $item['product_price'] . "<br>";
        echo "Total: $" . $item['total_price'] . "<br>";
        echo "</li>";
    }
    echo "</ul>";
}

// Example Usage (Simulated Purchase)
$product_id = 1;
$quantity = 2;

//Add to cart
$cart = addToCart($product_id, $quantity);
displayCart($cart);


?>
