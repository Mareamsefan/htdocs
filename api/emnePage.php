<?php
session_start();
$mysqli = require __DIR__ . "/../api/database.php";
$subject_pin = $_GET['subject_pin'] ?? '';

// Fetch data for the header
$sql = "SELECT SubjectName FROM subject WHERE SubjectPIN = '$subject_pin'";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();

// Your HTML and PHP code for the header...
?>

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
        $mysqli = require __DIR__ . "/../api/database.php";
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
           // session_start();
             // Check if user ID is set in the session
             if (isset($_SESSION['user_id'])) {
              // Retrieve user ID
              $userId = $_SESSION['user_id'];
              // echo "User ID: " . $userId;
          } else {
              echo "Welcome, guest!";
          }
          $mysqli = require __DIR__ . "/../api/database.php";
          $subject_pin = $_GET['subject_pin'] ?? '';
          $sql = "SELECT SubjectName FROM subject WHERE SubjectPIN = '$subject_pin'";
          $result = $mysqli->query($sql);
          $row = $result->fetch_assoc();
          $sql = <<<SQL
                    SELECT lecturerImage
                    FROM lecturer as l, lecturer_has_subject as lhs, subject as s
                    WHERE s.SubjectPIN = $subject_pin
                    AND s.SubjectCode = lhs.Subject_SubjectCode
                    AND lhs.Lecturer_ID = l.ID
                    SQL;
          $result = $mysqli->query($sql);
          if ($result && $result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $img_path = "/img/" . $row['lecturerImage'];
          }

          echo "<div class='lecturer'>
            <div class='picture'><img height='150px' width='150px' src='$img_path' /></div>";
            $mysqli->close();
            ?>
            <div>
              <?php
              $mysqli = require __DIR__ . "/../api/database.php";
              $subject_pin = $_GET['subject_pin'] ?? '';
              $sql = <<<SQL
                    SELECT FirstName, LastName
                    FROM lecturer as l, lecturer_has_subject as lhs, subject as s
                    WHERE s.SubjectPIN = '$subject_pin' 
                    AND s.SubjectCode = lhs.Subject_SubjectCode
                    AND lhs.lecturer_ID = l.ID
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
            $mysqli = require __DIR__ . "/../api/database.php";
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
        <form class="comment-container" id="message" action="../sendMessage.php" method="post">
          <article class="comment">
            <header class="comment-header">
              <div class="img"></div>
            </header>
            <div>
              <input type="text" name="comment" id="comment" placeholder="Din kommentar her" />
            </div>
              <!-- Hidden input fields for PHP variables -->
              <input type="hidden" name="subject_pin" value="<?php echo $_GET['subject_pin'];?>"/>
          </article>
          <div class="submit-btn">
            <input class="submit" type="submit" value="Send" />
          </div>
        </form>
        <section class="comments-section">
            <?php
          //  session_start();
            $subject_pin = $_GET['subject_pin'] ?? '';
            $mysqli = require __DIR__ . "/../api/database.php";
            $sql = "SELECT DISTINCT m.* FROM message AS m WHERE m.subject_SubjectPIN = $subject_pin ORDER BY m.ID DESC";
            $result = $mysqli->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    // Reply
                    $messageID = $row['ID'];
                    $reply_sql = <<<SQL
                    SELECT DISTINCT r.* 
                    FROM reply as r, message as m 
                    WHERE m.ID = $messageID
                    AND m.ID = r.Message_ID
                    SQL;
                    $reply_result = $mysqli->query($reply_sql);



                    echo '<article class="comment-record">';
                    echo '<header class="comment-header">';
                    echo '<div class="img2"></div>';
                    echo '</header>';
                    echo '<section class="comments-text">';
                    echo '<header>';
                    echo '<h3>Student</h3>';
                    echo '</header>';
                    echo '<div>';
                    echo '<p>' . $row["Message"] . '</p>';

                    // Student skal ikke kunne se reply button.
                    if (isset($_SESSION['account_type']) && $_SESSION['account_type'] != 1){
                        echo '<div class="reply-btn">';
                        echo '<input class="submit" type="submit" value="reply" />';
                        echo '</div>';
                    }

                    // Kun Guest skal kunne se report button.
                    if (empty($_SESSION['account_type'])){
                        echo '<div class="report-btn">';
                        echo '<form action="../reportMessage.php" method="post">';
                        echo '<input type="hidden" name="message_id" value="' . $row["ID"] . '"/>';
                        echo '<input type="hidden" name="subject_pin" value="' . $_GET['subject_pin'] . '"/>';
                        echo '<input class="submit" type="submit" value="Rapporter upassende kommentar" />';
                        echo '</form>';
                        echo '</div>';
                    }

                    while ($reply_row = $reply_result->fetch_assoc()) {
                        echo "<div class='reply_to_message' style='display: flex;gap: 1rem; margin: 1rem;'>";
                        echo '<div class="img2"></div>';
                        echo "<div>";
                        if ($reply_row['from_teacher'] == 1){
                            echo "<h3>Foreleser</h3>";
                        }
                        else{
                            echo "<h3>Gjest</h3>";
                        }
                        echo "<article>" . $reply_row['Message'] . "</article>";
                        echo "</div>";
                        echo "</div>";
                    }


                        echo '</div>';
                    // Gjest & Foreleser skal kunne se "reply"
                    if (empty($_SESSION['account_type']) || $_SESSION['account_type'] != 1) {
                        echo '<form class="comment-container reply-form" id="reply-form" action="../sendReply.php" method="post">';
                        echo '<article class="comment comment2">';
                        echo '<header class="comment-header">';
                        echo '<div class="img2"></div>';
                        echo '</header>';
                        echo '<div>';
                        echo '<input type="text" name="reply" id="reply" placeholder="Svar på kommentar her..." />';
                        echo '<input type="hidden" name="message_id" value="' . $row["ID"] . '"/>';
                        echo '<input type="hidden" name="subject_pin" value="' . $_GET['subject_pin'] . '"/>';
                        echo '</div>';
                        echo '</article>';
                        echo '<div class="submit-btn">';
                        echo '<input class="submit" type="submit" value="Send" />';
                        echo '</div>';
                        echo '</form>';
                    }
                    echo '</section>';
                    echo '</article>';
                }
            }
            ?>
        </section>
      </aside>
    </main>
  </div>
  <script>
    const replyBtn = document.querySelectorAll(".reply-btn");
    const replyForm = document.querySelectorAll(".reply-form");
    console.log("reply btn",replyBtn);
    console.log("reply btn", replyForm);

    
    window.onload = function(){
      toggleReply()
    }
    

    const toggleReply = () => {
      if (replyForm.style.display === "none") {
        replyForm.style.display = "block";
      } else {
        replyForm.style.display = "none";
        console.log("test her: "); 

      }
    }
    replyBtn.addEventListener("click", toggleReply);
  </script>
</body>

</html>
