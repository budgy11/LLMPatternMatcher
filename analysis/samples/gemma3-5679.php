        <?php endif; ?>
    </div>


    <script>
        // Get the search input element
        const searchInput = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('resultsContainer');

        // Add an event listener for the 'keyup' event on the search input
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.trim();

            // Clear the previous results
            resultsContainer.innerHTML = '';

            // If the search term is not empty, perform the search
            if (searchTerm !== '') {
                // Perform the search (same as in PHP)
                const products = [
                    'Laptop - Dell XPS 13',
                    'Mouse - Logitech MX Master 3',
                    'Keyboard - Corsair K95 RGB',
                    'Monitor - LG 27UL850-W',
                    'Webcam - Logitech Brio 4K',
                ];

                const searchResults = [];
                for (const product of products) {
                    if (product.toLowerCase().includes(searchTerm.toLowerCase())) {
                        searchResults.push(product);
                    }
                }

                // Display the results
                if (searchResults.length > 0) {
                    searchResults.forEach(product => {
                        const resultDiv = document.createElement('div');
                        resultDiv.className = 'result-item';
                        resultDiv.innerHTML = product;
                        resultsContainer.appendChild(resultDiv);
                    });
                } else {
                    resultsContainer.innerHTML = '<p>No results found.</p>';
                }
            }
        });
    </script>

</body>
</html>


<?php

// Function to handle the search bar
function search_results($search_term) {
  // Sanitize the search term - VERY IMPORTANT for security
  $search_term = htmlspecialchars(trim($search_term));

  //  You would normally fetch data from a database here,
  //  based on the $search_term.  This example uses an array
  //  for demonstration purposes.

  $data = [
    ['id' => 1, 'name' => 'Apple iPhone 14 Pro'],
    ['id' => 2, 'name' => 'Samsung Galaxy S23'],
    ['id' => 3, 'name' => 'Google Pixel 7 Pro'],
    ['id' => 4, 'name' => 'OnePlus 11'],
    ['id' => 5, 'name' => 'Apple Watch Series 8'],
  ];

  // Convert the search term to lowercase for case-insensitive search
  $search_term = strtolower($search_term);

  $results = [];

  foreach ($data as $item) {
    // Check if the search term is in the item's name (case-insensitive)
    if (strpos($item['name'], $search_term) !== false) {
      $results[] = $item;
    }
  }

  // Return the results or an empty array if no results are found
  return $results;
}

?>
