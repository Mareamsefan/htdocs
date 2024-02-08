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
          <li><a href="../index.html">Hjem</a></li>
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
                    WHERE s.SubjectPIN = lhs.Subject_SubjectCode
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
        <form class="comment-container">
          <article class="comment">
            <header class="comment-header">
              <div class="img"></div>
            </header>
            <div>
              <input type="text" name="commment" id="comment" placeholder="Din komment her" />
            </div>
          </article>
          <div class="submit-btn">
            <input class="submit" type="submit" value="Send" />
          </div>
        </form>
        <section class="comments-section">
          <article class="comment-record">
            <header class="comment-header">
              <div class="img2"></div>
            </header>
            <section class="comments-text">
              <header>
                <h3>Name of user</h3>
              </header>
              <div>
                <p>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit.
                  Voluptates odit reiciendis numquam est illo? Totam, non
                  voluptatem. Consequuntur, error cupiditate.
                </p>
                <div class="reply-btn">
                  <input class="submit" type="submit" value="Reply" />
                </div>
              </div>
              <form class="comment-container reply-form" id="reply-form">
                <article class="comment comment2">
                  <header class="comment-header">
                    <div class="img2"></div>
                  </header>
                  <div>
                    <input type="text" name="commment" id="comment" placeholder="Din svar på kommentær her" />
                  </div>
                </article>
                <div class="submit-btn">
                  <input class="submit" type="submit" value="Send" />
                </div>
              </form>
            </section>
          </article>
        </section>
      </aside>
    </main>
  </div>
  <script>
    const replyBtn = document.querySelector(".reply-btn");
    const replyForm = document.querySelector(".reply-form");
    
    window.onload = function(){
      toggleReply()
    }
    

    const toggleReply = () => {
      if (replyForm.style.display === "none") {
        replyForm.style.display = "block";
      } else {
        replyForm.style.display = "none";
      }
    }
    replyBtn.addEventListener("click", toggleReply);
  </script>
</body>

</html>