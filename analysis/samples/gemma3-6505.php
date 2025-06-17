</form>


<?php
session_start();

// Example product data (replace with your actual product information)
$products = [
    1 => ['id' => 1, 'name' => 'Laptop', 'price' => 1200],
    2 => ['id' => 2, 'name' => 'Mouse', 'price' => 25],
    3 => ['id' => 3, 'name' => 'Keyboard', 'price' => 75],
];

// Function to add an item to the cart
function addToCart($productId, $quantity = 1)
{
    if (isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] ?? 0;
        $_SESSION['cart'][$productId] = $_SESSION['cart'][$productId] + $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Function to update the quantity of an item in the cart
function updateCartQuantity($productId, $quantity)
{
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = $quantity;
    } else {
        // If the product is not in the cart, add it with the given quantity
        addToCart($productId, $quantity);
    }
}

// Function to remove an item from the cart
function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

// Function to get the cart contents
function getCartContents()
{
    return $_SESSION['cart'] ?? [];
}

// Example usage (handling adding to cart)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        addToCart($productId, $quantity);
        // Optionally, redirect to a product page or display a success message
        // header("Location: product.php?id=$productId");
    }

    if (isset($_POST['update_cart'])) {
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        updateCartQuantity($productId, $quantity);
    }

    if (isset($_POST['remove_from_cart'])) {
        $productId = $_POST['product_id'];
        removeFromCart($productId);
    }
}


// Displaying the cart content
$cartContents = getCartContents();

?>
