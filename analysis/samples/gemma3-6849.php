

function getProductPrice($product_id) {
  // Assuming you have a database connection established as $db

  $stmt = $db->prepare("SELECT price FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id); // 'i' for integer
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
    return $row['price'];
  } else {
    return 0; // Or handle the error appropriately (e.g., log the error)
  }

  $stmt->close();
}


<?php

// Data storage (replace with a database in a real application)
$cart = [];
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

// Function to add an item to the cart
function add_to_cart($product_id, $quantity = 1) {
    if (isset($products[$product_id])) {
        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = ['name' => $products[$product_id]['name'], 'price' => $products[$product_id]['price'], 'quantity' => $quantity];
        }
    } else {
        echo "Product ID $product_id not found.";
    }
}

// Function to display the cart
function display_cart() {
    echo "<h2>Shopping Cart</h2>";
    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
        return;
    }

    echo "<ul>";
    foreach ($cart as $item_id => $item_data) {
        $product_name = $item_data['name'];
        $item_price = $item_data['price'];
        $quantity = $item_data['quantity'];
        $total_price = $item_price * $quantity;

        echo "<li>" . $product_name . " - $" . $item_price . " x " . $quantity . " = $" . $total_price . "</li>";
    }
    echo "</ul>";

    // Calculate total cart value
    $total_value = 0;
    foreach ($cart as $item_id => $item_data) {
        $total_value += $item_data['price'] * $item_data['quantity'];
    }
    echo "<p><strong>Total Cart Value: $" . $total_value . "</strong></p>";
}

// Function to handle purchase
function handle_purchase() {
    if (empty($cart)) {
        echo "<p>Your cart is empty. Nothing to purchase.</p>";
        return;
    }

    echo "<h2>Purchase Confirmation</h2>";
    echo "<p>Thank you for your purchase!</p>";
    display_cart(); // Display the cart one last time to show the completed order

    // Clear the cart after purchase (optional - depends on requirements)
    $cart = [];
}


// -----  Example Usage and Handling User Input  -----
// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check for "add_to_cart" button
    if (isset($_POST["add_to_cart"])) {
        $product_id = $_POST["product_id"];
        $quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 1;  // Handle quantity
        add_to_cart($product_id, $quantity);
    }

    // Check for "purchase" button
    if (isset($_POST["purchase"])) {
        handle_purchase();
    }
}

// ----- Display the shopping page -----
?>
