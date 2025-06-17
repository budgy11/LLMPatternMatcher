
</body>
</html>


<?php

// This is a simplified purchase functionality for demonstration purposes.
// In a real-world application, you would need more robust security,
// database integration, error handling, and more comprehensive features.

class Purchase {
    private $product_id;
    private $quantity;
    private $total_price;

    public function __construct($product_id, $quantity) {
        $this->product_id = $product_id;
        $this->quantity = $quantity;

        //  **Important:** In a real application, you'd fetch product details
        //  from a database based on $product_id.  For this example,
        //  we'll use hardcoded values.
        $product = $this->getProduct($product_id);

        if ($product) {
            $this->total_price = $product['price'] * $this->quantity;
        } else {
            $this->total_price = 0;
            $this->setError("Product not found");
        }
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getTotalPrice() {
        return $this->total_price;
    }

    private function getProduct($product_id) {
        // **Replace this with a real database query**
        // This is just a simulated database lookup.
        $products = [
            1 => ['name' => 'T-Shirt', 'price' => 20],
            2 => ['name' => 'Jeans', 'price' => 50],
            3 => ['name' => 'Hat', 'price' => 15],
        ];
        return $products[$product_id] ?? null; // Null-coalescing operator for null safety
    }

    private $errors = [];

    public function addError($message) {
        $this->errors[] = $message;
    }

    public function getErrors() {
        return $this->errors;
    }
}

// Example Usage:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    if (is_numeric($product_id) && is_numeric($quantity) && $quantity > 0) {
        $purchase = new Purchase($product_id, $quantity);

        $errors = $purchase->getErrors();
        if (empty($errors)) {
            echo "<p>Purchase successful!</p>";
            echo "<p>Product ID: " . $purchase->getProductId() . "</p>";
            echo "<p>Quantity: " . $purchase->getQuantity() . "</p>";
            echo "<p>Total Price: $" . number_format($purchase->getTotalPrice(), 2) . "</p>";
        } else {
            echo "<p>Error: " . implode(", ", $errors) . "</p>";
        }
    } else {
        echo "<p>Invalid input. Please enter valid numeric values for product ID and quantity, and ensure quantity is greater than 0.</p>";
    }
}
?>
