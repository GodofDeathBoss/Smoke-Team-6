<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate inputs
    $first_name = trim(htmlspecialchars($_POST['first_name'] ?? ''));
    $last_name = trim(htmlspecialchars($_POST['last_name'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));

    // Check required fields
    if (empty($first_name) || empty($last_name) || empty($email)) {
        die("Error: All fields are required.");
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Error: Invalid email format.");
    }

    // Create signups_data directory if it doesn't exist
    $directory = "signups_data/";
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true); // 0755 = permissions, true = recursive
    }

    // Get current date and time
    $signup_date = date('Y-m-d');
    $signup_time = date('H:i:s');
    
    // Create daily filename
    $filename = $directory . "signups_" . $signup_date . ".csv";
    
    // Check if file exists, if not add headers
    $file_exists = file_exists($filename);
    
    // Save data
    $file = fopen($filename, "a");
    if ($file) {
        // Add headers if new file
        if (!$file_exists) {
            fputcsv($file, ['First Name', 'Last Name', 'Email', 'Date', 'Time']);
        }
        
        // Add signup data
        $data = [
            $first_name,
            $last_name,
            $email,
            $signup_date,
            $signup_time
        ];
        
        fputcsv($file, $data);
        fclose($file);
    } else {
        die("Error: Unable to save data. Check directory permissions.");
    }

    // Redirect to thank-you page
    header("Location: thank-you.html");
    exit();
}
?>