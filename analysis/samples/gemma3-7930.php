

<?php

// Configuration (Simulated)
$products = [
    1 => ['name' => 'T-Shirt', 'price' => 20],
    2 => ['name' => 'Jeans', 'price' => 50],
    3 => ['name' => 'Hat', 'price' => 15],
];

class Order {
    public $items = [];
    public $total = 0;

    public function addItem($product_id, $quantity = 1) {
        if (isset($products[$product_id])) {
            $item = $products[$product_id];
            $item_total = $item_total = $item['price'] * $quantity;
            $this->items[] = ['product_id' => $product_id, 'quantity' => $quantity, 'item_total' => $item_total];
            $this->total += $item_total;
        } else {
            echo "Error: Product ID $product_id not found.";
        }
    }

    public function getItems() {
        return $this->items;
    }

    public function getTotal() {
        return $this->total;
    }
}


// --- Order Processing ---

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get Product IDs and Quantities from the form
    $product_ids = isset($_POST['product_ids']) ? $_POST['product_ids'] : [];
    $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

    // Validate Input (Basic - more thorough validation needed in production)
    if (count($product_ids) !== count($quantities)) {
        echo "<p>Error: Number of product IDs and quantities must match.</p>";
        exit; // Stop execution
    }

    // Create the order object
    $order = new Order();

    // Process the order
    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = $product_ids[$i];
        $quantity = $quantities[$i];
        $order->addItem($product_id, $quantity);
    }

    // Display the order details
    echo "<h2>Order Confirmation</h2>";
    echo "<p>Items Ordered:</p>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Quantity</th><th>Item Total</th></tr>";
    foreach ($order->getItems() as $item) {
        echo "<tr>";
        echo "<td>" . $products[$item['product_id']]['name'] . "</td>";
        echo "<td>" . $item['quantity'] . "</td>";
        echo "<td>$" . number_format($item['item_total'], 2) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p>Total: $" . number_format($order->getTotal(), 2) . "</p>";
}
?>
