

<?php

/**
 * User Review Function
 *
 * This function allows you to create, read, update, and delete user reviews.
 * It demonstrates basic CRUD (Create, Read, Update, Delete) operations 
 * for a simple review system.
 *
 * Assumptions:
 * - You'll likely want to store these reviews in a database.  This example uses a simple
 *   array for demonstration purposes.
 * - This is a simplified example and doesn't include full error handling, validation,
 *   or security measures (e.g., preventing SQL injection).  You should implement
 *   robust error handling and security in a production environment.
 *
 * @param string $reviewText - The text of the review.
 * @param int    $rating     - The rating given for the review (e.g., 1-5).
 * @param int    $productId  - The ID of the product this review is for.
 * @param int    $userId     - The ID of the user who wrote the review (optional, for tracking).
 *
 * @return array|false - Returns an array containing the review ID, timestamp, text, rating, 
 *                       and user ID if successful.  Returns false on failure.
 */
function createReview(string $reviewText, int $rating, int $productId, int $userId = null) {
    // Basic validation (add more robust validation in a real application)
    if (empty($reviewText) || $rating < 1 || $rating > 5) {
        return false;
    }

    // Generate a unique ID (consider a better method for real applications)
    $reviewId = time();

    // Store the review (replace with database insertion in a real application)
    $review = [
        'id' => $reviewId,
        'timestamp' => time(),
        'text' => $reviewText,
        'rating' => $rating,
        'userId' => $userId,
    ];

    // Simulate storing in a database.  This is just for example.
    // In a real application, you'd use a database query.
    // $result = db_insert('reviews', $review); 

    //Simulate database insertion success.
    return $review;
}

/**
 * Reads a review by its ID.
 *
 * @param int $reviewId - The ID of the review to retrieve.
 *
 * @return array|null - Returns the review data if found, otherwise null.
 */
function readReview(int $reviewId) {
    // Example data (replace with database query)
    $reviews = [
        ['id' => 1, 'timestamp' => 1678886400, 'text' => 'Great product!', 'rating' => 5, 'userId' => 101],
        ['id' => 2, 'timestamp' => 1678972800, 'text' => 'Okay product.', 'rating' => 3, 'userId' => 102],
    ];

    foreach ($reviews as $review) {
        if ($review['id'] == $reviewId) {
            return $review;
        }
    }

    return null; // Review not found
}


/**
 * Updates a review by its ID.
 *
 * @param int $reviewId - The ID of the review to update.
 * @param string $newReviewText - The new text of the review.
 * @param int    $newRating     - The new rating for the review.
 *
 * @return bool - True if the update was successful, false otherwise.
 */
function updateReview(int $reviewId, string $newReviewText, int $newRating) {
    //Basic validation -  add more specific validation based on your needs.
    if (empty($newReviewText) || $newRating < 1 || $newRating > 5) {
        return false;
    }
    
    // In a real application, you would retrieve the existing review from the database
    // and then update it.  This example simulates the update.
    
    // Find the review (simulate database lookup)
    $reviews = [
        ['id' => 1, 'timestamp' => 1678886400, 'text' => 'Great product!', 'rating' => 5, 'userId' => 101],
        ['id' => 2, 'timestamp' => 1678972800, 'text' => 'Okay product.', 'rating' => 3, 'userId' => 102],
    ];

    $index = -1;
    for ($i = 0; $i < count($reviews); $i++) {
        if ($reviews[$i]['id'] == $reviewId) {
            $index = $i;
            break;
        }
    }

    if ($index === -1) {
        return false; // Review not found
    }
    
    $reviews[$index]['text'] = $newReviewText;
    $reviews[$index]['rating'] = $newRating;

    return true;
}


/**
 * Deletes a review by its ID.
 *
 * @param int $reviewId - The ID of the review to delete.
 *
 * @return bool - True if the delete was successful, false otherwise.
 */
function deleteReview(int $reviewId) {
    // In a real application, you would query the database to delete the review.
    // This example simulates the delete.

    // Find the review (simulate database lookup)
    $reviews = [
        ['id' => 1, 'timestamp' => 1678886400, 'text' => 'Great product!', 'rating' => 5, 'userId' => 101],
        ['id' => 2, 'timestamp' => 1678972800, 'text' => 'Okay product.', 'rating' => 3, 'userId' => 102],
    ];

    $index = -1;
    for ($i = 0; $i < count($reviews); $i++) {
        if ($reviews[$i]['id'] == $reviewId) {
            $index = $i;
            break;
        }
    }

    if ($index === -1) {
        return false; // Review not found
    }

    unset($reviews[$index]);

    return true;
}



// Example Usage:
// Create a review
$newReview = createReview("This is an amazing product!", 5, 1);
if ($newReview) {
    echo "Review created with ID: " . $newReview['id'] . "
";
} else {
    echo "Failed to create review.
";
}

// Read the review
$review = readReview(1);
if ($review) {
    echo "Review:
";
    print_r($review);
} else {
    echo "Review not found.
";
}

// Update the review
if (updateReview(1, "This is even better!", 6)) {
    echo "Review updated successfully.
";
} else {
    echo "Failed to update review.
";
}

// Read the updated review
$updatedReview = readReview(1);
if ($updatedReview) {
    echo "Updated Review:
";
    print_r($updatedReview);
}

// Delete the review
if (deleteReview(1)) {
    echo "Review deleted successfully.
";
} else {
    echo "Failed to delete review.
";
}

//Try to read a deleted review
$deletedReview = readReview(1);
if ($deletedReview) {
    echo "Deleted Review:
";
    print_r($deletedReview);
}
else {
    echo "Review not found (as expected). 
";
}
?>
