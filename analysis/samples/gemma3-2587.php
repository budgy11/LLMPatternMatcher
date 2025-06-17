

<?php

/**
 * User Review Function
 *
 * This function allows you to create and display user reviews for a product or service.
 * It includes validation, sanitization, and basic formatting.
 *
 * @param string $productId The ID of the product or service being reviewed.
 * @param string $reviewerName The name of the reviewer.
 * @param string $reviewText The text of the review.
 * @param int $rating (Optional) The rating given (1-5). Defaults to 0.
 * @return array|string  Returns an array of reviews or a single review string.
 */
function createReview(string $productId, string $reviewerName, string $reviewText, int $rating = 0) {
    // **Input Validation & Sanitization** - CRITICAL for security!
    $reviewerName = trim($reviewerName); // Remove leading/trailing whitespace
    $reviewText = trim($reviewText);
    $rating = intval($rating); // Ensure rating is an integer

    if (empty($reviewerName)) {
        return "Error: Reviewer name cannot be empty.";
    }

    if (empty($reviewText)) {
        return "Error: Review text cannot be empty.";
    }

    if ($rating < 1 || $rating > 5) {
        return "Error: Rating must be between 1 and 5.";
    }

    // **Data Preparation & Formatting**
    $formattedReview = "Reviewer: " . $reviewerName . "<br>";
    $formattedReview .= "Rating: " . $rating . " stars<br>";
    $formattedReview .= "Review: " . $reviewText . "<br>";

    return $formattedReview;
}



/**
 * Example Usage:
 */

// Single Review Creation
$review = createReview("product_123", "Alice Smith", "Great product!  I highly recommend it.", 5);
echo $review;

echo "<br><br>";

// Multiple Reviews (example - you'd likely store these in a database)
$reviews = [
    ["productId" => "product_123", "reviewerName" => "Bob Johnson", "reviewText" => "Good value for the price.", 4],
    ["productId" => "product_456", "reviewerName" => "Charlie Brown", "reviewText" => "Could be better.", 2],
];

// Displaying the reviews (example - you'd likely loop through a database)
foreach ($reviews as $reviewData) {
    echo createReview($reviewData["productId"], $reviewData["reviewerName"], $reviewData["reviewText"], $reviewData["rating"]);
    echo "<br><br>";
}


?>
