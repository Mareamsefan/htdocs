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
                // Retrieve lecturer's email from the query parameter
                $lecturer_email = $_GET['email'];
                echo "<h1>Dashboard til \"$lecturer_email\"</h1>";
                ?>
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
                <article class="subject">
                    <header class="subject-header">
                        <h3>Emne 1 Navn</h3>
                    </header>
                    <button><a href="#">Besøk emneside</a></button>
                </article>

                <article class="subject">
                    <header class="subject-header">
                        <h3>Emne 2 Navn</h3>
                    </header>

                    <button><a href="#">Besøk emneside</a></button>

                </article>
                <article class="subject">
                    <header class="subject-header">
                        <h3>Emne 3 Navn</h3>
                    </header>
                    <button><a href="#">Besøk emneside</a></button>
                </article>
                <article class="subject">
                    <header class="subject-header">
                        <h3>Emne 4 Navn</h3>
                    </header>
                    <button><a href="#">Besøk emneside</a></button>
                </article>
            </section>
        </main>
    </div>
</body>

</html>
