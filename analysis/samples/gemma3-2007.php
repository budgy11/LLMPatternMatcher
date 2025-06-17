

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 *
 * @param string $productName The name of the product or service being reviewed.
 * @param string $reviewText The text of the review submitted by the user.
 * @param string $username The username of the reviewer.
 * @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 * @return array An array containing review data (ID, product name, user, review, rating, date).
 *               Returns an empty array if an error occurs.
 */
function createReview(string $productName, string $reviewText, string $username, int $rating = 0) {
    // Input validation - Essential for security and data integrity
    if (empty($productName)) {
        error_log("Error: Product name cannot be empty.");
        return [];
    }

    if (empty($reviewText)) {
        error_log("Error: Review text cannot be empty.");
        return [];
    }

    if (empty($username)) {
        error_log("Error: Username cannot be empty.");
        return [];
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Error: Rating must be between 1 and 5.");
        return [];
    }

    // Sanitize inputs to prevent XSS vulnerabilities.  Important!
    $productName = htmlspecialchars($productName);
    $reviewText = htmlspecialchars($reviewText);
    $username = htmlspecialchars($username);

    // Generate a unique review ID (consider using a more robust method in production)
    $reviewId = uniqid();


    // Store the review (This would typically involve a database operation)
    // This is a placeholder for demonstration purposes.
    $review = [
        'id' => $reviewId,
        'product' => $productName,
        'user' => $username,
        'review' => $reviewText,
        'rating' => $rating,
        'date' => date('Y-m-d H:i:s')  // Get the current date and time
    ];

    // In a real application, you would save the $review array to a database.
    // Example:
    //  $db = new DatabaseConnection(); // Assuming you have a DatabaseConnection class
    //  $db->insertReview($review);


    return $review;
}


/**
 * Display Reviews (Example -  Should be adapted to your display method)
 *
 *  This is just an example function to show how to display the reviews.
 *  Adapt this to your specific display needs (e.g., HTML, JSON, API response)
 *
 * @param array $reviews An array of review objects.
 */
function displayReviews(array $reviews) {
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
        return;
    }

    echo "<h2>Reviews for " . end(array_keys($reviews)) . "</h2>"; //Display the product name from the key of the array.
    echo "<ul>";
    foreach ($reviews as $review) {
        echo "<li>";
        echo "<strong>" . $review['user'] . ":</strong> " . $review['review'] . " (Rating: " . $review['rating'] . ") - " . $review['date'];
        echo "</li>";
    }
    echo "</ul>";
}


// --- Example Usage ---

// Create a review
$newReview = createReview("Awesome Widget", "This widget is fantastic! I love it.", "JohnDoe", 5);

if (!empty($newReview)) {
    echo "<h2>New Review Created:</h2>";
    print_r($newReview); // For debugging - remove in production
}


// Create another review
$anotherReview = createReview("Great Service", "Excellent customer support.", "JaneSmith", 4);

if (!empty($anotherReview)) {
    displayReviews([$anotherReview]); //Pass the review to display.
}


?>
