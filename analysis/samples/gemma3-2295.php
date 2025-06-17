

<?php

/**
 * User Review Function
 *
 * This function takes user input (review text and rating) and
 * stores it in a database.  It includes basic input validation and error handling.
 *
 * @param string $reviewText The user's review text.
 * @param int $rating A rating from 1 to 5.
 * @param string $productId The ID of the product being reviewed. (For example, from a database)
 * @return bool True if the review was successfully stored, false otherwise.
 */
function storeUserReview(string $reviewText, int $rating, string $productId)
{
    // Input Validation
    if (empty($reviewText)) {
        error_log("Invalid review text: Empty review text provided.");
        return false;
    }

    if ($rating < 1 || $rating > 5) {
        error_log("Invalid rating: Rating must be between 1 and 5.");
        return false;
    }

    if (empty($productId)) {
        error_log("Invalid product ID: Product ID cannot be empty.");
        return false;
    }


    // **Simulated Database Interaction (Replace with your actual database logic)**
    // This part simulates storing the review in a database.  You'll need to
    // replace this with your actual database connection and query logic.
    $review = [
        'review_text' => $reviewText,
        'rating' => $rating,
        'product_id' => $productId,
        'created_at' => date('Y-m-d H:i:s'),  // Add timestamp for tracking
    ];

    // Example:  Storing to a simple array (replace with your database insert)
    $reviews = isset($_SESSION['reviews']) ? $_SESSION['reviews'] : []; // Ensure session exists
    $reviews[] = $review;
    $_SESSION['reviews'] = $reviews;



    // **Error Logging (Recommended)**
    error_log("User review stored: " . $reviewText . " - Rating: " . $rating . " - Product ID: " . $productId);


    return true;
}


// **Example Usage (For testing)**
// Assume you have a form to submit the review.
// This is just an example; you'll integrate this with your form.

// Simulate form submission
// $_POST['review_text'] = "This product is amazing!";
// $_POST['rating'] = 5;
// $_POST['product_id'] = "123";


// if (isset($_POST['review_text']) && isset($_POST['rating']) && isset($_POST['product_id'])) {
//     if (storeUserReview($_POST['review_text'], $_POST['rating'], $_POST['product_id'])) {
//         echo "Review submitted successfully!";
//     } else {
//         echo "Error submitting review.  Please try again.";
//     }
// }


?>
