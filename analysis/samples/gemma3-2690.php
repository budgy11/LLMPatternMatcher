

<?php

/**
 * User Review Function
 *
 * This function allows you to collect, process, and display user reviews.
 *
 * @param string $review_form_id The ID of the HTML form used to collect reviews.
 * @param string $review_db_table The name of the database table where reviews will be stored.
 * @param string $review_title The title/name for the reviews (e.g., "Restaurant Reviews")
 * @param string $success_message A success message to display after a review is submitted.
 * @param string $error_message  An error message to display if the review submission fails.
 *
 * @return array An array containing the collected reviews, or an empty array if no reviews were submitted.
 */
function collect_user_reviews(
    string $review_form_id,
    string $review_db_table,
    string $review_title,
    string $success_message = "Review submitted successfully!",
    string $error_message = "An error occurred while submitting your review."
) {
    // 1. Get the form data
    $reviews = [];

    if (isset($_POST[$review_form_id])) {
        $review_text = filter_input(INPUT_POST, $review_form_id, FILTER_SANITIZE_STRING);
        $rating = filter_input(INPUT_POST, $review_form_id . '_rating', FILTER_VALIDATE_INT); // Validate rating as an integer
        $user_name = filter_input(INPUT_POST, $review_form_id . '_user_name', FILTER_SANITIZE_STRING);


        // 2. Validate the input
        if (empty($review_text)) {
            echo "<p style='color: red;'>" . $error_message . "</p>";
            return []; // Return an empty array if no review text
        }

        if ($rating === null || $rating < 1 || $rating > 5) {
            echo "<p style='color: red;'>" . $error_message . "</p>";
            return [];
        }

        if (empty($user_name)) {
            echo "<p style='color: red;'>" . $error_message . "</p>";
            return [];
        }


        // 3. Process the review data
        $review = [
            'review_text' => $review_text,
            'rating'      => $rating,
            'user_name'   => $user_name,
            'submission_date' => date('Y-m-d H:i:s') // Add timestamp for date
        ];

        // 4. Store the review in the database (Example - using a simple database)
        //  You'll need to adapt this to your database setup.
        $db_connection = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_user", "your_db_password"); // Replace with your database credentials

        $stmt = $db_connection->prepare("INSERT INTO " . $review_db_table . " (review_text, rating, user_name, submission_date) VALUES (:review_text, :rating, :user_name, :submission_date)");
        $stmt->bindParam(':review_text', $review_text);
        $stmt->bindParam(':rating', $rating);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':submission_date', $review_text); //Use the review_text here for the date
        $stmt->execute();

        $reviews[] = $review;


    }

    return $reviews;
}


// Example Usage (This would typically be called from within a web page)
//  You'd need to have an HTML form with the ID 'my_review_form'
//  and a database table named 'restaurant_reviews'
//  Example HTML form structure:
// <form id="my_review_form" action="process_review.php" method="post">
//   <label for="review_text">Review Text:</label><br>
//   <textarea id="review_text" name="my_review_form" rows="4" cols="50"></textarea><br><br>
//   <label for="rating">Rating (1-5):</label><br>
//   <input type="number" id="rating" name="my_review_form_rating" min="1" max="5"><br><br>
//   <label for="user_name">Your Name:</label><br>
//   <input type="text" id="user_name" name="my_review_form_user_name" required><br><br>
//   <button type="submit">Submit Review</button>
// </form>

// $collected_reviews = collect_user_reviews('my_review_form', 'restaurant_reviews');

// if (!empty($collected_reviews)) {
//   echo "<h2>Collected Reviews:</h2>";
//   foreach ($collected_reviews as $review) {
//     echo "<p><strong>User:</strong> " . $review['user_name'] . "</p>";
//     echo "<p><strong>Rating:</strong> " . $review['rating'] . "</p>";
//     echo "<p><strong>Review:</strong> " . $review['review_text'] . "</p>";
//     echo "<p><strong>Date:</strong> " . $review['submission_date'] . "</p>";
//     echo "<hr>";
//   }
// } else {
//   echo "<p>No reviews submitted yet.</p>";
// }

?>
