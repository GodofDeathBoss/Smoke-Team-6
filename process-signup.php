<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the name and email from the form
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    // Save the data (e.g., in a database or a file)
    // Example: Save to a text file
    $file = fopen("signups.txt", "a");
    fwrite($file, "Name: $name, Email: $email\n");
    fclose($file);

    // Redirect to a thank-you page
    header("Location: thank-you.html");
    exit();
}
?>