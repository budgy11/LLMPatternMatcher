

<?php

/**
 *  User Review Function
 *
 *  This function allows you to create and manage user reviews for a product or item.
 *
 *  @param string $productId  The unique identifier for the product being reviewed.
 *  @param string $productName The name of the product. (Used for display)
 *  @param string $reviewerName The name of the user submitting the review.
 *  @param string $reviewText The text content of the review.
 *  @param int $rating      The rating given by the user (1-5).
 *  @param string $timestamp  (Optional) A timestamp for the review.  If not provided, it will be generated.
 *
 *  @return bool  True if the review was successfully created, false otherwise.
 */
function createReview(string $productId, string $productName, string $reviewerName, string $reviewText, int $rating, string $timestamp = null) {
    // --- Validation ---
    if (empty($productId) || empty($productName) || empty($reviewerName) || empty($reviewText) || $rating < 1 || $rating > 5) {
        return false; // Invalid input
    }

    // --- Data Sanitization (Important!) ---
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);
    $reviewerName = filter_var($reviewerName, FILTER_SANITIZE_STRING);


    // --- Store the review (Simulated Database Interaction) ---
    // In a real application, you would connect to a database here.

    // Create a review object (just for demonstration)
    $review = [
        'productId' => $productId,
        'productName' => $productName,
        'reviewerName' => $reviewerName,
        'reviewText' => $reviewText,
        'rating' => $rating,
        'timestamp' => $timestamp ?: time() // Use current time if timestamp is not provided
    ];

    // Simulate saving to a database (replace with actual database code)
    // echo "Saving review: " . print_r($review, true) . "
"; // For debugging
    //  For example, you might use a database connection and prepare a statement:
    //  $db = new DatabaseConnection();  // Assuming you have a DatabaseConnection class
    //  $stmt = $db->prepare("INSERT INTO reviews (productId, productName, reviewerName, reviewText, rating, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
    //  $stmt->execute([$productId, $productName, $reviewerName, $reviewText, $rating, $timestamp ?: time()]);
    //  $reviewId = $db->lastInsertId();


    // Mock successful save
    return true;  // Indicate successful creation
}


// --- Example Usage ---

// Create a review
$product_id = "123";
$product_name = "Awesome Gadget";
$reviewer = "John Doe";
$review = "This gadget is amazing!  I love it.";
$rating = 4;

$success = createReview($product_id, $product_name, $reviewer, $review, $rating);

if ($success) {
    echo "Review created successfully!
";
} else {
    echo "Error creating review.
";
}

// Example with timestamp
$success = createReview($product_id, $product_name, $reviewer, $review, $rating, date("Y-m-d H:i:s"));
if($success){
    echo "Review created with timestamp.
";
}
else{
    echo "Error creating review with timestamp.
";
}
?>
