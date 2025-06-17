

<?php

/**
 * User Review Function
 *
 * This function allows you to store user reviews for a given product or item.
 * It provides basic input validation and stores the review in a simple file.
 *
 * @param string $product_id  The unique identifier for the product/item.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param string $review_file Path to the file to store reviews in.
 *
 * @return bool True if the review was successfully saved, false otherwise.
 */
function save_user_review(string $product_id, string $user_name, string $review_text, string $review_file): bool
{
    // Input Validation
    if (empty($product_id)) {
        error_log("Invalid product ID: Product ID cannot be empty.");
        return false;
    }
    if (empty($user_name)) {
        error_log("Invalid user name: User name cannot be empty.");
        return false;
    }
    if (empty($review_text)) {
        error_log("Invalid review text: Review text cannot be empty.");
        return false;
    }

    // Sanitize input to prevent XSS attacks
    $review_text = htmlspecialchars($review_text);

    // Create a review entry
    $review_entry = "Product ID: $product_id
";
    $review_entry .= "User: $user_name
";
    $review_entry .= "Review: $review_entry
";

    // Append the review to the file
    if (file_put_contents($review_file, $review_entry, FILE_APPEND)) {
        return true;
    } else {
        error_log("Failed to write review to file: $review_file");
        return false;
    }
}

// Example Usage:
$product_id = "P123";
$user_name = "John Doe";
$review_text = "This product is amazing!  I really enjoyed it.";
$review_file = "reviews.txt";

if (save_user_review($product_id, $user_name, $review_text, $review_file)) {
    echo "Review saved successfully!
";
} else {
    echo "Failed to save review.
";
}

?>
