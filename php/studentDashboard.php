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
            <h1>Dashboard til "student navn"</h1>
            <ul>
                <li><a href="../index.html">Hjem</a></li>
            </ul>
        </nav>
    </header>

    <main class="shadow">
        <header class="main-header">
            <h2 class="title">Emner</h2>
        </header>

        <section class="subjects">
            <?php
            $mysqli = require __DIR__ . "/../php/database.php";
            $sql = "SELECT DISTINCT s.* FROM subject AS s";
            $result = $mysqli->query($sql);

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