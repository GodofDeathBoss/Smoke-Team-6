<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the name and email from the form
    if (isset($_POST['name']) && isset($_POST['email'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        // Save the data (e.g., in a database or a file)
        // Example: Save to a text file
        $file = fopen("signups.txt", "a");
        if ($file) {
            $data = json_encode(['name' => $name, 'email' => $email]) . "\n";
            fwrite($file, $data);
            fclose($file);
        } else {
            // Handle file open error
            die("Error: Unable to open the file.");
        }
    } else {
        // Handle missing form data
        die("Error: Name and email are required.");
    }

    // Redirect to a thank-you page
    header("Location: thank-you.html");
    exit();
}
?>