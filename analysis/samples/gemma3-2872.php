

<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display user reviews for a given item.
 *
 * @param string $item_name The name of the item being reviewed.
 * @param string $review_text The text of the user's review.
 * @param int $user_id (Optional) The ID of the user submitting the review.  Defaults to 0.
 * @param int $rating (Optional) The rating given to the item (1-5). Defaults to 0.
 *
 * @return array  An array containing review data:
 *               - 'review_id' (int): Unique ID of the review.
 *               - 'user_id' (int):  ID of the user.
 *               - 'item_name' (string): Name of the item.
 *               - 'review_text' (string): Review text.
 *               - 'rating' (int): Rating.
 *               - 'timestamp' (string):  Timestamp of the review.
 */
function create_user_review(string $item_name, string $review_text, int $user_id = 0, int $rating = 0)
{
    //  Basic validation - you'd likely want more robust validation in a real application.
    if (empty($review_text)) {
        return ['error' => 'Review text cannot be empty.'];
    }

    if ($rating < 1 || $rating > 5) {
        return ['error' => 'Rating must be between 1 and 5.'];
    }

    // Generate a unique ID (replace with a database-generated ID in a real app)
    $review_id = time(); 

    // Get the current timestamp
    $timestamp = date("Y-m-d H:i:s");

    // Return the review data
    return [
        'review_id' => $review_id,
        'user_id' => $user_id,
        'item_name' => $item_name,
        'review_text' => $review_text,
        'rating' => $rating,
        'timestamp' => $timestamp
    ];
}


// --- Example Usage ---

// Create a review
$review = create_user_review("Awesome Product", "This product is amazing!  I highly recommend it.");

if (isset($review['error'])) {
    echo "Error creating review: " . $review['error'] . "<br>";
} else {
    echo "Review ID: " . $review['review_id'] . "<br>";
    echo "User ID: " . $review['user_id'] . "<br>";
    echo "Item Name: " . $review['item_name'] . "<br>";
    echo "Review Text: " . $review['review_text'] . "<br>";
    echo "Rating: " . $review['rating'] . "<br>";
    echo "Timestamp: " . $review['timestamp'] . "<br>";
}


// Example with user ID and rating
$review2 = create_user_review("Another Product", "Not bad.", 123, 4);

if (isset($review2['error'])) {
    echo "Error creating review: " . $review2['error'] . "<br>";
} else {
    echo "Review ID: " . $review2['review_id'] . "<br>";
    echo "User ID: " . $review2['user_id'] . "<br>";
    echo "Item Name: " . $review2['item_name'] . "<br>";
    echo "Review Text: " . $review2['review_text'] . "<br>";
    echo "Rating: " . $review2['rating'] . "<br>";
    echo "Timestamp: " . $review2['timestamp'] . "<br>";
}


//Example with invalid input
$review_error = create_user_review("", "Review", 1, 6);
if(isset($review_error['error'])){
    echo "Error creating review: " . $review_error['error'] . "<br>";
}
?>
