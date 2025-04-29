<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. INPUT VALIDATION
    $first_name = trim(htmlspecialchars($_POST['first_name'] ?? ''));
    $last_name = trim(htmlspecialchars($_POST['last_name'] ?? ''));
    $email = trim($_POST['email'] ?? '');

    // Validate required fields
    $errors = [];
    if (empty($first_name)) $errors[] = "First name is required";
    if (empty($last_name)) $errors[] = "Last name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Display errors if any
    if (!empty($errors)) {
        header('Content-Type: text/html');
        echo "<h2>Error:</h2><ul>";
        foreach ($errors as $error) echo "<li>$error</li>";
        echo "</ul>";
        exit;
    }

    // 2. FILE HANDLING
    $directory = "signups_data/";
    
    // Create directory if missing
    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        die("Error: Could not create directory");
    }

    // Create daily CSV file
    $filename = $directory . "signups_" . date('Y-m-d') . ".csv";
    
    try {
        // Open file in append mode
        $file = fopen($filename, 'a');
        
        if (!$file) throw new Exception("Could not open file");
        
        // Add headers if new file
        if (filesize($filename) === 0) {
            fputcsv($file, ['First Name', 'Last Name', 'Email', 'Date', 'Time']);
        }
        
        // Write data
        fputcsv($file, [
            $first_name,
            $last_name,
            $email,
            date('Y-m-d'),
            date('H:i:s')
        ]);
        
        fclose($file);
        
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

    // 3. REDIRECT
    header("Location: thank-you.html");
    exit();
} else {
    // If not POST request
    header("HTTP/1.1 405 Method Not Allowed");
    die("Error: Only POST requests allowed");
}
?>