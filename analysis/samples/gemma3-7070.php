

<?php
// (Previous code from the answer goes here - the handlePurchase function)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID and quantity from the form
    $productId = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    // Call the handlePurchase function
    $result = handlePurchase($productId, $quantity);

    // Display the result
    echo $result . "
";
}
?>
