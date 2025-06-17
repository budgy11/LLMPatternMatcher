

<?php

// This is a simplified purchase functionality for demonstration purposes.
// It doesn't include a database or payment gateway integration.
// It's designed to illustrate the basic flow of a purchase.

class Purchase {

    private $items = []; // Array to store purchased items and their quantities
    private $total_amount = 0;

    public function __construct() {
        // Initialize the purchase
        $this->items = [];
        $this->total_amount = 0;
    }

    public function addItem($item_name, $quantity, $price) {
        if (is_numeric($quantity) && is_numeric($price)) {
            $this->items[] = [
                'name' => $item_name,
                'quantity' => (int)$quantity,
                'price' => (float)$price
            ];
            $this->total_amount += ($quantity * $price);
        } else {
            echo "Invalid quantity or price.  Must be numeric values.
";
        }
    }

    public function removeItem($item_name) {
        // Simple removal logic - find and remove the first matching item.
        foreach ($this->items as $key => $item) {
            if ($item['name'] === $item_name) {
                unset($this->items[$key]);
                $this->total_amount -= ($item['quantity'] * $item['price']);
                return; // Exit after removing the item
            }
        }
        echo "Item '$item_name' not found in the purchase.
";
    }

    public function getTotalAmount() {
        return number_format($this->total_amount, 2); // Format to two decimal places
    }

    public function displayPurchase() {
        echo "<h2>Purchase Details</h2>
";
        echo "<ul>
";
        foreach ($this->items as $item) {
            echo "<li>$item['name']: $item['quantity'] x $item['price'] = $item['quantity'] * $item['price']</li>
";
        }
        echo "</ul>
";
        echo "<p><strong>Total Amount: $" . $this->getTotalAmount() . "</strong></p>
";
    }
}


// --- Example Usage ---

// Create a new purchase object
$purchase = new Purchase();

// Add some items
$purchase->addItem("Laptop", 1, 1200.00);
$purchase->addItem("Mouse", 2, 25.00);
$purchase->addItem("Keyboard", 1, 75.00);

// Display the purchase details
$purchase->displayPurchase();

// Remove an item
$purchase->removeItem("Mouse");

// Display the updated purchase details
echo "
<h2>Updated Purchase Details</h2>
";
$purchase->displayPurchase();


//  ---  Important Considerations and Enhancements ---

// 1. Database Integration:
//    - You'll typically store purchase information in a database (e.g., MySQL, PostgreSQL).
//    - This class would need to interact with the database to save, retrieve, and update purchases.

// 2. Payment Gateway Integration:
//    -  To process payments, you'll need to integrate with a payment gateway (e.g., PayPal, Stripe, Braintree).
//    - This involves handling credit card details securely (using tokenization - avoid storing sensitive card data directly).
//    -  The transaction would likely be handled asynchronously (e.g., via a webhook from the payment gateway).

// 3. Security:
//    - **Input Validation:**  Thoroughly validate all user inputs to prevent security vulnerabilities (e.g., SQL injection, XSS).
//    - **Output Encoding:**  Encode all output to prevent XSS attacks.
//    - **HTTPS:**  Always use HTTPS to encrypt communication between the user's browser and your server.

// 4. Error Handling:
//    - Implement robust error handling to gracefully handle exceptions and display informative error messages to the user.

// 5. User Interface (UI):
//   - This is a command-line example.  You'll need to create a user-friendly web interface (e.g., HTML, JavaScript, PHP) to interact with the purchase functionality.
?>
