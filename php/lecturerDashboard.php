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
                <?php
                session_start();
                // Check if user ID is set in the session
                if (isset($_SESSION['user_id'])) {
                    // Retrieve user ID
                    $userId = $_SESSION['user_id'];
                    // echo "User ID: " . $userId;
                } else {
                    echo "User ID not found in session.";
                }

                // testing
                $mysqli = require __DIR__ . "/../php/database.php";
                $sql = "SELECT FirstName, LastName FROM Lecturer WHERE ID = '$userId'";
                $result = $mysqli->query($sql);
                $row = $result->fetch_assoc();
                $LecturerFirstName = $row['FirstName'];
                $LecturerLastName = $row['LastName'];
                echo "<h1>Dashboard til $LecturerFirstName $LecturerLastName and btw account_type = {$_SESSION['account_type']}</h1>";
                ?>
                <ul>
                    <li><a href="../../index.html">Hjem</a></li>
                </ul>
            </nav>
        </header>

        <main class="shadow">
            <header class="main-header">
                <h2 class="title">Emner</h2>
            </header>
            <section class="add">
                <button><a href="/html/registerSubject.html">Legg til emne</a></button>
            </section>

            <section class="upload">
                <h2>Last opp bilde</h2>
                <form action="../php/uploadImage.php" method="post" enctype="multipart/form-data">
                    Velg bilde for opplasting:
                    <input type="file" name="lecturerImage" id="lecturerImage">
                    <input type="submit" value="Last opp bilde" name="submit">
                </form>
            </section>

            <section class="subjects">
                <?php

                // SQL for å finne alle subjects til innlogget lecturer :)
                // Hentet for det meste kode fra chatGPT for denne. Se log under.
                // https://chat.openai.com/share/d8d4c359-b56e-4983-acac-acd2e754765f
                $sql = "SELECT DISTINCT s.*
                FROM subject AS s
                INNER JOIN lecturer_has_subject AS lhs ON s.SubjectCode = lhs.Subject_SubjectCode
                INNER JOIN lecturer AS l ON lhs.Lecturer_ID = l.ID
                WHERE l.ID = $userId";
                $result = $mysqli->query($sql);


                // Check if subjects are retrieved
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<article class='subject'>";
                        echo "<header class='subject-header'>";
                        echo "<h3>" . $row['SubjectName'] . "</h3>";
                        echo "</header>";
                        echo "<button><a href='../../php/emnePage.php/?subject_pin={$row['SubjectPIN']}'>Besøk emneside</a></button>";
                        echo "</article>";
                    }
                } else {
                    echo "Ingen emner funnet.";
                }
                ?>
            </section>
        </main>
    </div>
</body>

</html>
