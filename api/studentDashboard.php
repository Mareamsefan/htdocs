<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/studentDashboard.css">
    <link rel="stylesheet" href="/style/reset.css">
    <title>Student Dashboard</title>
</head>

<body>
<div class="container">

    <header class="header shadow bg">
        <nav>
            <?php
            session_start();
            // Check if user ID is set in the session
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            } else {
                header("Location: /index.html");
            }

            $mysqli = require __DIR__ . "/../api/database.php";
            $sql = "SELECT FirstName, LastName FROM student WHERE ID = '$userId'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            $StudentFirstName = $row['FirstName'];
            $StudentLastName = $row['LastName'];
            echo "<h1>Dashboard til $StudentFirstName $StudentLastName</h1>";

            ?>
            <ul class="Liste">
                <li><a href="../../index.html">Logg ut</a></li>
                <li><a href="studentMinProfil.php">Min profil</a></li>
            </ul>
        </nav>
    </header>

    <main class="shadow">
        <header class="main-header">
            <h2 class="title">Emner</h2>
        </header>

        <section class="subjects">
            <?php
            $mysqli = require __DIR__ . "/../api/database.php";
            $sql = "SELECT DISTINCT s.* FROM subject AS s";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<article class='subject'>";
                    echo "<header class='subject-header'>";
                    echo "<h3>" . $row['SubjectName'] . "</h3>";
                    echo "</header>";
                    echo "<button><a href='../../api/emnePage.php/?subject_pin={$row['SubjectPIN']}'>Besøk emneside</a></button>";
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