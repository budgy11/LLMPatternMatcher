  </div>

</body>
</html>


    echo htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
    

    This function converts special characters (like `<`, `>`, `&`, `"`, `'`) into their HTML entities, effectively neutralizing them.

3.  **Input Validation:**  Validate the search term to ensure it meets your criteria (e.g., maximum length, allowed characters).

**Example of using Prepared Statements (Highly Recommended):**



<?php

// Assuming you have a database connection established (e.g., $conn)

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the search term from the POST request
    $searchTerm = htmlspecialchars(trim($_POST['search'])); 

    // Sanitize and escape the search term to prevent SQL injection
    $searchTerm = $conn->real_escape_string($searchTerm);  // Assuming $conn is your database connection object

    // Perform the search (replace with your actual query)
    $sql = "SELECT * FROM your_table WHERE your_column LIKE '%" . $searchTerm . "%'"; 

    // Execute the query
    $result = $conn->query($sql);

    // Display the results (you'll need to adjust this based on how you want to display the data)
    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['your_column']) . "</li>"; // Assuming your column is named 'your_column'
        }
        echo "</ul>";
    } else {
        echo "No results found for '" . htmlspecialchars($searchTerm) . "'.";
    }
}

?>
