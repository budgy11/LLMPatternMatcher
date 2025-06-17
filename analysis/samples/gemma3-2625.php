

<?php

/**
 * User Review Function
 *
 * This function handles the process of adding, retrieving, and displaying user reviews.
 *
 * @param string $productId - The ID of the product the review is for.
 * @param string $userEmail - The email address of the user submitting the review.
 * @param string $reviewText - The text of the review.
 * @param string $dbFilePath - Path to the database file (e.g., 'reviews.txt', 'reviews.json', etc.).
 * @param bool $allowAnonymous - Whether to allow anonymous reviews.
 *
 * @return array|string - Returns an array of reviews if successful, or an error message if not.
 */
function add_review(string $productId, string $userEmail, string $reviewText, string $dbFilePath, bool $allowAnonymous = false) {
    // Input Validation (basic example - enhance for production)
    if (empty($productId) || empty($reviewText)) {
        return "Error: Product ID and Review Text cannot be empty.";
    }

    // Sanitize Input (Basic - improve for production)
    $productId = filter_var($productId, FILTER_SANITIZE_STRING);
    $reviewText = filter_var($reviewText, FILTER_SANITIZE_STRING);

    // Check if anonymous review is allowed
    if (!$allowAnonymous && !isset($userEmail)) {
        return "Error: Anonymous reviews are not allowed.";
    }

    // Construct the review data
    $reviewData = [
        'product_id' => $productId,
        'user_email' => $userEmail,
        'review' => $reviewText,
        'timestamp' => date("Y-m-d H:i:s")
    ];

    // Save the review to the database file
    $filename = $dbFilePath;
    $reviewString = json_encode($reviewData); // Use JSON for structured data

    if (file_put_contents($filename, $reviewString) === false) {
        return "Error: Failed to save review to database.";
    }

    return "Review added successfully!";
}


/**
 * Retrieves all reviews for a given product.
 *
 * @param string $productId - The ID of the product.
 * @param string $dbFilePath - Path to the database file.
 * @return array|string - Returns an array of reviews or an error message.
 */
function get_reviews(string $productId, string $dbFilePath) {
    $filename = $dbFilePath;
    if (!file_exists($filename)) {
        return "Error: No reviews found for product ID: " . $productId . ".  Database file not found.";
    }

    $reviews = [];
    if (($handle = fopen($filename, "r")) !== false) {
        while (($data = fgetcsv($handle, 1000, ",")) !== false) { // Adjust delimiter if needed
            if (count($data) == 4 && isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3])) {
                $review = [
                    'product_id' => $data[0],
                    'user_email' => $data[1],
                    'review' => $data[2],
                    'timestamp' => $data[3]
                ];
                $reviews[] = $review;
            }
        }
        fclose($handle);
    } else {
        return "Error: Could not open database file.";
    }

    if (empty($reviews)) {
        return "No reviews found for product ID: " . $productId;
    }

    return $reviews;
}


/**
 * Example Usage
 */

// Configuration
$dbFilePath = 'reviews.json'; // Replace with your actual file path
$productId = 'P123';

// Add a review
$reviewResult = add_review($productId, 'user@example.com', 'Great product!  Highly recommended.', $dbFilePath);
echo "Add Review Result: " . $reviewResult . "
";

// Get reviews for the product
$reviews = get_reviews($productId, $dbFilePath);
echo "Reviews:
";
print_r($reviews);

?>
