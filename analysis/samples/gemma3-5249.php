
<!DOCTYPE html>
<html>
<head>
  <title>Search Form</title>
  <style>
    /* Basic styling for the form */
    input[type="text"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
      margin-bottom: 10px;
    }
    button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h1>Search</h1>

  <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">  <!--  Using the current page as the action -->
