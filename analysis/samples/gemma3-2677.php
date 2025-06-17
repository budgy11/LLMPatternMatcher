

<?php

/**
 * User Review Function
 *
 * This function allows you to store and display user reviews for a product or item.
 * It includes basic input validation and output formatting.
 *
 * @param string $product_id The unique identifier for the product.
 * @param string $user_name The name of the user submitting the review.
 * @param string $review_text The text of the review.
 * @param int    $rating     The rating given by the user (1-5).
 *
 * @return array An array containing the review data if successful, or an error message
 *               if validation fails.
 */
function createReview(string $product_id, string $user_name, string $review_text, int $rating): array
{
    // Input Validation
    if (empty($product_id)) {
        return ["error" => "Product ID cannot be empty."];
    }

    if (empty($user_name)) {
        return ["error" => "User name cannot be empty."];
    }

    if (empty($review_text)) {
        return ["error" => "Review text cannot be empty."];
    }

    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        return ["error" => "Rating must be a number between 1 and 5."];
    }

    //  Store the review (replace with your database logic)
    $review = [
        "product_id" => $product_id,
        "user_name" => $user_name,
        "review_text" => $review_text,
        "rating" => $rating,
        "timestamp" => date("Y-m-d H:i:s") // Add timestamp for tracking
    ];

    //  Example:  Save to a simple array (replace with database insertion)
    //  $reviews[$product_id][] = $review;


    // Return success with the review data
    return ["success" => true, "review" => $review];
}

/**
 * Display Reviews Function (Example - for demonstration)
 *
 * This function demonstrates how to display reviews based on a product ID.
 * It assumes you have a mechanism to retrieve reviews from a data source.
 *
 * @param string $product_id The product ID to retrieve reviews for.
 * @return array An array containing the reviews or an error message.
 */
function displayReviews(string $product_id): array
{
    // Example: Retrieve reviews from an array (replace with your data retrieval logic)
    $reviews = [
        "product123" => [
            ["user_name" => "John Doe", "review_text" => "Great product!", "rating" => 5],
            ["user_name" => "Jane Smith", "review_text" => "Good value for money.", "rating" => 4]
        ],
        "product456" => [
            ["user_name" => "Peter Jones", "review_text" => "Excellent quality.", "rating" => 5]
        ]
    ];

    if (isset($reviews[$product_id])) {
        return ["success" => true, "reviews" => $reviews[$product_id]];
    } else {
        return ["success" => false, "message" => "No reviews found for this product."];
    }
}



// Example Usage:
// Create a review
$review_data = createReview("product123", "Alice Brown", "This is a fantastic product!", 5);

if (isset($review_data["success"])) {
    echo "Review created successfully!<br>";
    echo "Review ID: " . $review_data["review"]["id"] . "<br>";
    print_r($review_data["review"]);
} else {
    echo "Error creating review: " . $review_data["error"] . "<br>";
}


// Display reviews for product123
$reviews = displayReviews("product123");

if (isset($reviews["success"])) {
    echo "<h3>Reviews for product123:</h3>";
    if (empty($reviews["reviews"])) {
        echo "<p>No reviews yet!</p>";
    } else {
        foreach ($reviews["reviews"] as $review) {
            echo "<strong>" . $review["user_name"] . ":</strong> " . $review["review_text"] . " (" . $review["rating"] . "/5)<br>";
        }
    }
} else {
    echo "Error displaying reviews: " . $reviews["message"] . "<br>";
}

?>
