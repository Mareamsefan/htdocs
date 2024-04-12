<?php
$mysqli = require __DIR__ . "/database.php";
session_start();

// Function to remove PHP code using regular expressions
function remove_php_tags($comment) {
    // Remove PHP code from the comment
    $comment = preg_replace('/<\?(=|php)?.*?\?>/si', '', $comment);
    return $comment;
}

// Function to remove JavaScript code
function remove_js_code($comment) {
    // Remove JavaScript code from the comment
    $comment = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $comment);
    return $comment;
}

// if there is no message, or no subject pin, just return to the previous page.
if (isset($_POST["comment"]) && isset($_POST["subject_pin"])) {
    $comment = $_POST["comment"];
    // Remove PHP code from the comment
    $comment = remove_php_tags($comment);
    // Remove JavaScript code from the comment
    $comment = remove_js_code($comment);
    // Remove HTML tags and escape special characters
    $comment = htmlspecialchars(strip_tags($comment));
    $subjectPIN = $_POST["subject_pin"];
    $accountType = $_SESSION['account_type'];

    if ($accountType == 1) {
        $studentID = $_SESSION["user_id"];
        $sql = "INSERT INTO message (Message, Student_ID, Reported, subject_SubjectPIN) 
                VALUES (?, ?, 0, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("sis", $comment, $studentID, $subjectPIN);
            if ($stmt->execute()) {
                header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
            } else {
                echo "Error submitting message. Please try again.";
            }
            $stmt->close();
        } else {
            echo "Error preparing statement. Please try again.";
        }
    } else {
        header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
    }
} else {
    $subjectPIN = $_POST["subject_pin"];
    header("Location: /php/emnePage.php/?subject_pin=$subjectPIN");
}



/* HOW TO INCORPORATE MESSAGES
 * 1. Finn en måte å sende inn data på. Dette gjøres i denne filen (sendMessage.php)
 * Data du må inkludere:
 * - message (fra form)
 * - subject_pin (fra URL)
 * - account_type (fra session)
 * - user_id (fra session)
 *
 * 2. Koble disse til riktig til database.
 * 3. skriv om på emnePage.php til å inkludere en for loop(1) som henter alle messages (where subject_pin = subject_pin)
 * - her må du sette username til "Student" for alle messages med en student id
 * - her må du sette username til "Anonym" for alle messages uten student id
 * 4. loopen som henter alle messages skal også legge til en "reply" knapp med en form som linker til sendReply
 * 5. lag sendReply. Gjør steg 1-2 om igjen i sendReply.php.
 * - en endring du må gjøre her er at du må ha if-check som ser etter om reply kommer fra account_type = 2(foreleser)
 * - eller om det kommer fra account_type = null (anonym).
 * 6. legg til en loop(2) inne i loop(1) som henter ut alle replies til hver individuelle message (if exists)
 */
