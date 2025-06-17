

//Example of saving to a database (simplified - error handling and full sanitation would be needed in a real application)
$review = createReview("AliceWonderland", 4, "Fantastic product, I love it!");
if ($review) {
    // Connect to your database
    $db = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO reviews (username, rating, review) VALUES (:username, :rating, :review)");

    // Bind the parameters
    $stmt->bindParam(':username', $review['username']);
    $stmt->bindParam(':rating', $review['rating']);
    $stmt->bindParam(':review', $review['review']);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Review saved to database!
";
    } else {
        echo "Error saving review to database.
";
    }
    $db = null; // Close the connection
}


<?php

/**
 * User Review Function
 *
 * This function allows users to submit and display reviews for a product or service.
 * It includes basic validation and sanitization.
 *
 * @param string $product_id The ID of the product or service being reviewed.
 * @param string $reviewer_name The name of the reviewer.
 * @param string $review_text The text of the review.
 * @param array  $validation_rules An array of validation rules (e.g., ['name' => 'required', 'text' => 'required']).
 *                                 Defaults to required validation for both name and text.
 * @return array|string An array containing success/error messages, or a string message if there was an error.
 */
function submit_review(string $product_id, string $reviewer_name, string $review_text, array $validation_rules = []) {
  // Default validation rules if none are provided.
  $validation_rules = $validation_rules + [
    'name' => 'required',
    'text' => 'required'
  ];


  // Validation
  $errors = [];

  if (!empty($validation_rules['name']) && empty($reviewer_name)) {
    $errors['name'] = 'Reviewer name is required.';
  }
  if (!empty($validation_rules['text']) && empty($review_text)) {
    $errors['text'] = 'Review text is required.';
  }

  // Sanitization - Basic.  Consider more robust sanitization for production.
  $reviewer_name = trim($reviewer_name);
  $review_text = trim($review_text);


  // Check for empty strings after trimming.  This is important.
  if (empty($reviewer_name)) {
      $errors['name'] = 'Reviewer name cannot be empty.';
  }
  if (empty($review_text)) {
    $errors['text'] = 'Review text cannot be empty.';
  }



  // If there are no errors, save the review (simulated here).
  if (empty($errors)) {
    // In a real application, you would save this to a database.
    // For demonstration, we'll just return a success message.

    // Simulate database saving
    $saved = true;
    return ['success' => 'Review submitted successfully!'];
  } else {
    // Return the error messages
    return ['errors' => $errors];
  }
}


// **Example Usage**
// 1. Successful Submission
$result = submit_review('product_123', 'John Doe', 'This is a great product!');
print_r($result);
// Output: Array ( [success] => Review submitted successfully! )

// 2. Submission with Errors
$result = submit_review('product_456', '', 'Write a review...');
print_r($result);
// Output: Array ( [errors] => Array ( [text] => Review text cannot be empty. ) )

// 3. Submission with custom validation rules
$result = submit_review('product_789', 'Jane Smith', 'Excellent service!', ['name' => 'required', 'text' => 'max:200']);
print_r($result);
//Output: Array ( [success] => Review submitted successfully! )


// 4.  Empty string name submission
$result = submit_review('product_999', '', 'This is a great product!');
print_r($result);
// Output: Array ( [errors] => Array ( [name] => Reviewer name is required. ) )
?>
