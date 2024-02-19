<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Emner</h1>

<?php
$apiUrl = "http://localhost/api/api/subjects/getAllSubjects.php"; // Replace with your actual API URL

$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session and fetch data
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Decode JSON response
    $subjects = json_decode($response, true);

    // Check if decoding was successful
    if ($subjects !== null) {
        echo "<h2>List of Subjects:</h2>";
        echo "<ul>";

        foreach ($subjects as $subject) {
            echo "<li>";
            echo "Subject Code: " . $subject["SubjectCode"] . "<br>";
            echo "Subject Name: " . $subject["SubjectName"] . "<br>";
            echo "Subject PIN: " . $subject["SubjectPIN"] . "<br>";
            echo "</li>";
        }

        echo "</ul>";
    } else {
        echo "Error decoding JSON response.";
    }
}

// Close cURL session
curl_close($ch);
?>
</body>
</html>