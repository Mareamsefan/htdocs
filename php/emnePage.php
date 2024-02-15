<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/style/reset.css" />
  <link rel="stylesheet" href="/style/util.css" />
  <link rel="stylesheet" href="/style/emne.css" />
  <title>Lærer minside</title>
</head>

<body>
  <div class="container">
    <header class="header shadow bg">
      <nav>
        <?php
        $mysqli = require __DIR__ . "/../php/database.php";
        $subject_pin = $_GET['subject_pin'] ?? '';
        $sql = "SELECT SubjectName FROM subject WHERE SubjectPIN = '$subject_pin'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        echo "<h1>" . $row['SubjectName'] . "</h1>";
        $mysqli->close();
        ?>
        <ul>
          <li><a href="../../index.html">Hjem</a></li>
        </ul>
      </nav>
    </header>
    <main class="shadow">
      <section class="content">
        <header class="content-header">
          <?php
          $mysqli = require __DIR__ . "/../php/database.php";
          $subject_pin = $_GET['subject_pin'] ?? '';
          $sql = "SELECT SubjectName FROM subject WHERE SubjectPIN = '$subject_pin'";
          $result = $mysqli->query($sql);
          $row = $result->fetch_assoc();
          echo "<h2>" . $row['SubjectName'] . "</h2>";
          $mysqli->close();
          ?>
          <div class="lecturer">
            <div class="picture"></div>
            <div>
              <?php
              $mysqli = require __DIR__ . "/../php/database.php";
              $subject_pin = $_GET['subject_pin'] ?? '';
              $sql = <<<SQL
                    SELECT FirstName, LastName
                    FROM lecturer as l, lecturer_has_subject as lhs, subject as s
                    WHERE s.SubjectPIN = '$subject_pin' 
                    AND s.SubjectPIN = lhs.Subject_SubjectCode
                    AND lhs.Lecturer_ID = l.ID
                    SQL;
              $result = $mysqli->query($sql);
              $row = $result->fetch_assoc();
              echo "<p>" . $row['FirstName'] . " " . $row['LastName'] . "</p>";
              $mysqli->close();
              ?>
            </div>
          </div>
        </header>
        <article class="facts">
          <header>
            <h3>Fakta om emne</h3>
          </header>
          <div>
            <?php
            $mysqli = require __DIR__ . "/../php/database.php";
            $subject_pin = $_GET['subject_pin'] ?? '';
            $sql = "SELECT SubjectCode, SubjectName, SubjectPIN FROM subject WHERE SubjectPIN = '$subject_pin'";
            $result = $mysqli->query($sql);
            $row = $result->fetch_assoc();
            echo "<p>" . "Emne Kode:" . $row['SubjectCode'] . "</p>";
            echo "<p>" . "Emne Navn:" . $row['SubjectName'] . "</p>";
            echo "<p>" . "Emne PIN: " . $row['SubjectPIN'] . "</p>";
            $mysqli->close();
            ?>
          </div>
        </article>
      </section>
      <aside class="comment-area">
        <div class="mening">
          <p>Si hva du mener om emnet til andre!</p>
        </div>
        <form class="comment-container" method="post" action="registerMessage.php" target="hidden_iframe">
          <article class="comment">
            <header class="comment-header">
              <div class="img"></div>
            </header>
            <div>
              <input type="text" name="comment" id="comment" placeholder="Din komment her" />
            </div>
          </article>
          <div class="submit-btn">
            <input class="submit" type="submit" value="Send" />
          </div>
        </form>
        <section class="comments-section">
          <!-- Existing comments section content -->

          <!-- Add this iframe to the end of your HTML body -->
          <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"></iframe>
        </section>
      </aside>
    </main>
  </div>
</body>

</html>
