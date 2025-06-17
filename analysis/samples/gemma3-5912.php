
<!DOCTYPE html>
<html>
<head>
  <title>Simple Search</title>
  <style>
    .search-results {
      border: 1px solid #ccc;
      padding: 10px;
      margin-top: 10px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      margin-bottom: 5px;
    }
  </style>
</head>
<body>

  <h1>Simple Search Example</h1>

  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
