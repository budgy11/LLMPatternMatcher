
<!-- HTML Form for the Search Bar -->
<form action="search.php" method="get">
  <input type="text" name="search" placeholder="Enter search term..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
