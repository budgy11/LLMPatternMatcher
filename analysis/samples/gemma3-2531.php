

<?php

/**
 * User Review Function
 *
 * This function allows you to create, display, and manage user reviews
 * for a specific item (e.g., a product, movie, restaurant).
 *
 * @param string $item_name The name of the item being reviewed.
 * @param string $item_id The unique ID of the item.
 * @param array $reviews An array of review objects.  Each object should have:
 *                       - 'user': User's name or ID.
 *                       - 'rating':  Integer (1-5).
 *                       - 'comment':  User's review comment.
 * @return array  An array of review objects to display.
 */
function create_review_function(string $item_name, string $item_id, array $reviews = []) {

  // Validation (Simple example - can be expanded)
  if (empty($item_name) || empty($item_id)) {
    return ['error' => 'Item name and ID are required.'];
  }

  // Add the new review (example - you'll need to adapt this to your database)
  $new_review = [
    'user' => 'John Doe',
    'rating' => 4,
    'comment' => 'Great product!  Works as expected.',
    'item_id' => $item_id // Important: Link the review to the item
  ];

  $reviews[] = $new_review;

  return $reviews;
}

// --- Example Usage ---

// 1. Create a new review
$item_name = 'Awesome Gadget';
$item_id = 'GADGET001';
$reviews = create_review_function($item_name, $item_id);

if (isset($reviews['error'])) {
    echo "<p style='color:red;'>Error: " . $reviews['error'] . "</p>";
} else {
    // 2. Display the reviews
    echo "<h2>Reviews for " . $item_name . " (ID: " . $item_id . ")</h2>";
    if (empty($reviews)) {
        echo "<p>No reviews yet.</p>";
    } else {
        echo "<ul>";
        foreach ($reviews as $review) {
            echo "<li>";
            echo "<strong>User:</strong> " . $review['user'] . "<br>";
            echo "<strong>Rating:</strong> " . $review['rating'] . " / 5<br>";
            echo "<strong>Comment:</strong> " . $review['comment'] . "</li>";
        }
        echo "</ul>";
    }
}


// ---  More Advanced Features to Consider ---

// 1. Database Integration:
//    - Replace the simple review creation with code to insert the review
//      into a database table (e.g., 'reviews').
//    - Use prepared statements to prevent SQL injection.

// 2. Sorting/Filtering:
//    - Add options to sort reviews by rating, date, etc.
//    - Allow filtering reviews based on rating ranges or keywords.

// 3. User Authentication:
//    -  Require users to be logged in before submitting a review.  This is crucial
//       for security and to track who wrote which review.

// 4. Validation:
//   - More robust validation (e.g., checking the rating is within the 1-5 range,
//     validating the comment for offensive content).

// 5.  Pagination: If you have many reviews, implement pagination to
//     display them in manageable chunks.

// 6.  Error Handling: Improve error handling (e.g., logging errors instead of just
//     displaying them to the user).
?>
