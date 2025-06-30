<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "ecommerce";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_product_by_id($conn, $product_id) {
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product;
}

function insert_cart_item($conn, $user_id, $product_id, $quantity) {
    $sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    return $conn->insert_id; // Returns the new cart_id
}

function get_cart_items($conn) {
    $sql = "SELECT p.product_name, p.price, p.image_url, c.quantity FROM carts c JOIN products p ON c.product_id = p.product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    } else {
        return [];
    }
}
?>
