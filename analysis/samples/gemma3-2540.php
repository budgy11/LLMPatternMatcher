

<?php

/**
 *  User Review Function
 *
 *  This function allows users to submit and display reviews for a product or service.
 *
 *  @param string $productName The name of the product or service being reviewed.
 *  @param string $reviewText The text of the review submitted by the user.
 *  @param string $username The username of the user submitting the review.
 *  @param int $rating (Optional) The rating given by the user (1-5). Defaults to 0.
 *  @return array An array containing the review data or an error message if the input is invalid.
 */
function createReview(string $productName, string $reviewText, string $username, int $rating = 0) {
    // Input validation - essential for security and data integrity
    if (empty($productName)) {
        return ["error" => "Product name cannot be empty."];
    }
    if (empty($reviewText)) {
        return ["error" => "Review text cannot be empty."];
    }
    if (empty($username)) {
        return ["error" => "Username cannot be empty."];
    }
    if ($rating < 1 || $rating > 5) {
        return ["error" => "Rating must be between 1 and 5."];
    }

    //  Construct the review data - you would typically store this in a database
    $reviewData = [
        "product_name" => $productName,
        "review_text" => $reviewText,
        "username" => $username,
        "rating" => $rating,
        "timestamp" => date("Y-m-d H:i:s") // Add a timestamp for tracking
    ];

    return $reviewData;
}

/**
 *  Display Reviews Function
 *
 *  This function takes an array of review data and displays them in an HTML format.
 *
 *  @param array $reviews An array of review data (as returned by createReview).
 *
 *  @return string  HTML string to display the reviews, or a message if no reviews exist.
 */
function displayReviews(array $reviews) {
    if (empty($reviews)) {
        return "<p>No reviews yet.</p>";
    }

    $html = "<h2>Reviews for " . $reviews[0]["product_name"] . "</h2>"; // Display product name
    $html .= "<ul>";

    foreach ($reviews as $review) {
        $html .= "<li>";
        $html .= "<strong>" . $review["username"] . ":</strong> " . $review["review_text"] . " (" . $review["rating"] . "/5)";
        $html .= "<br>";
        $html .= "<small><em>" . $review["timestamp"] . "</em></small>";
        $html .= "</li>";
    }

    $html .= "</ul>";
    return $html;
}


// Example Usage:
// 1. Create a review:
$reviewData = createReview("Awesome Widget", "This widget is fantastic! It does everything I need.", "john_doe", 5);

if (isset($reviewData["error"])) {
    echo "<p>Error: " . $reviewData["error"] . "</p>";
} else {
    // 2. Display the review:
    $reviews = [
        $reviewData,
        ["product_name" => "Another Product", "review_text" => "Good product!", "username" => "jane_doe", "rating" => 4, "timestamp" => date("Y-m-d H:i:s")],
        ["product_name" => "Yet Another Product", "review_text" => "Not bad.", "username" => "peter_pan", "rating" => 3, "timestamp" => date("Y-m-d H:i:s")]
    ];

    echo displayReviews($reviews);
}

?>
