<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/lecturerDashboard.css">
    <link rel="stylesheet" href="/style/reset.css">
    <link rel="stylesheet" href="/style/util.css">
    <title>Lærer Dashboard</title>
</head>

<body>
    <div class="container">

        <header class="header shadow bg">
            <nav>
                <h1>All emner</h1>
                <ul>
                    <li><a href="../index.html">Hjem</a></li>
                </ul>
            </nav>
        </header>

        <main class="shadow">
            <header class="main-header">
                <h2 class="title">Emner</h2>
            </header>
            <section class="add">
                <button><a href="/api/registerSubject.php">Legg til emne</a></button>
            </section>
            <section class="subjects">
                <?php

                    // URL of the API endpoint
$url = "http://localhost/api/api/subjects/getAllSubjects.php";

// Fetch data from the API
$response = file_get_contents($url);

// Decode JSON data
$data = json_decode($response, true);

// Check if the decoding was successful
if ($data !== null) {
    // Output HTML for each subject
    foreach ($data as $row) {
        echo "<article class='subject'>";
        echo "<header class='subject-header'>";
        echo "<h3>" . $row['SubjectName'] . "</h3>";
        echo "</header>";
        echo "<button><a href='#'>Besøk emneside</a></button>";
        echo "</article>";
    }
} else {
    // Handle the case where decoding failed
    echo "Failed to fetch or decode data from the API.";
}
                ?>
            </section>
        </main>
    </div>
</body>

</html>
