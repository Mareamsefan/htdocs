<?php
$mysqli = require __DIR__ . "/database.php";

// if there is no message, or no subject pin, just return to the previous page.
if (($_POST["message"] != null) && ($_POST["subject_pin"] != null)){
    $message = $_POST["message"];
    $subjectPIN = $_POST["message"];

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

    $sql = "INSERT INTO Message (FirstName, LastName, Email, StudyProgram, Class, Password)
            VALUES ('$FirstName', '$LastName', '$Email', '$StudyProgram', '$Class', '$Password')";
}
else{
    header("Location: /index.html");
}