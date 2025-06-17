

<?php

/**
 * User Review Function
 *
 * This function handles the submission and display of user reviews.
 * It includes validation, saving to a database, and displaying the reviews.
 *
 * @param string $username The username of the user submitting the review.
 * @param string $review_text The review text entered by the user.
 * @param string $db_host The database host.
 * @param string $db_name The database name.
 * @param string $db_user The database username.
 * @param string $db_password The database password.
 * @return array An array containing:
 *   - 'success' => bool: True if the review was submitted successfully, false otherwise.
 *   - 'message' => string: A message indicating the result of the operation.
 *   - 'reviews' => array: An array of review objects, or an empty array if there are no reviews yet.
 */
function submit_review(string $username, string $review_text, string $db_host, string $db_name, string $db_user, string $db_password) {

    // Validation (Simple example - can be expanded)
    if (empty($username) || empty($review_text)) {
        return [
            'success' => false,
            'message' => 'Username and review text cannot be empty.',
            'reviews' => []
        ];
    }

    // Connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error); // Or handle the error differently
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO reviews (username, review_text) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $review_text);
    $stmt->execute();

    // Check for errors
    if ($stmt->errno) {
        $stmt->close();
        return [
            'success' => false,
            'message' => 'Error submitting review: ' . $stmt->error,
            'reviews' => []
        ];
    }

    $stmt->close();

    // Fetch all reviews
    $reviews_query = "SELECT * FROM reviews";
    $result = $conn->query($reviews_query);

    if ($result->num_rows > 0) {
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    } else {
        $reviews = [];
    }

    // Close connection
    $conn->close();

    return [
        'success' => true,
        'message' => 'Review submitted successfully!',
        'reviews' => $reviews
    ];
}

// Example Usage:
//  Replace these with your actual database credentials
$db_host = 'localhost';
$db_name = 'my_database';
$db_user = 'my_user';
$db_password = 'my_password';

// Example 1: Successful submission
$result = submit_review('John Doe', 'This is a great product!', $db_host, $db_name, $db_user, $db_password);
print_r($result);
// Example 2:  Empty input
$result = submit_review('', 'This is a review', $db_host, $db_name, $db_user, $db_password);
print_r($result);

?>
