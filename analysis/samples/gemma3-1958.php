

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or item.
 *
 * @param string $productName The name of the product being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $userId (Optional) The ID of the user submitting the review.  Useful for tracking.
 * @param array $reviews (Optional) An existing array of reviews to append to.
 *
 * @return array An updated array of reviews, with the new review added.
 */
function addReview(string $productName, string $reviewText, string $userId = '', array &$reviews = []): array
{
    // Sanitize and Validate Input (Crucial for security!)
    $reviewText = trim($reviewText); // Remove leading/trailing whitespace
    if (empty($reviewText)) {
        return $reviews; // Don't add empty reviews
    }
    //  You would typically add more robust validation here
    //  e.g., check length, prevent malicious code, etc.

    // Create a new review object (or just an array - depending on your needs)
    $review = [
        'user_id' => $userId,
        'review_text' => $reviewText,
        'date' => date('Y-m-d H:i:s') // Add a timestamp for organization
    ];

    // Add the review to the array.  The "&" makes $reviews passed by reference.
    $reviews[] = $review;

    return $reviews;
}



/**
 * Function to Display Reviews
 *
 * Displays reviews for a product.  This is a helper function
 * to simplify the output.
 *
 * @param string $productName The name of the product.
 * @param array $reviews An array of reviews for the product.
 */
function displayReviews(string $productName, array $reviews) : void
{
    echo "<h2>Reviews for " . $productName . "</h2>";
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li><strong>" . htmlspecialchars($review['user_id']) . ":</strong> " . htmlspecialchars($review['review_text']) . " - " . htmlspecialchars($review['date']) . "</li>";
        }
        echo "</ul>";
    }
}



// Example Usage:
$product = "Awesome Widget";

// Initial Reviews (can come from a database, for example)
$reviews = [
    ['user_id' => 'user1', 'review_text' => 'Great product!', 'date' => '2023-10-26'],
    ['user_id' => 'user2', 'review_text' => 'Works as expected.', 'date' => '2023-10-27']
];


// Add a new review
$newReview = addReview($product, "This is fantastic!", "user3");
$reviews = addReview($product, "Amazing!", "user4"); //Using the array reference for updates.

// Display the reviews
displayReviews($product, $reviews);
?>
