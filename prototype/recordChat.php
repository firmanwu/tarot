<?php
    session_start();
    if (isset($_SESSION["user"])) {
        $text = $_POST["text"];
        date_default_timezone_set("Asia/Taipei");
        $date = date('H:i:s');
        $fp = fopen("message.html", "a+");
        $message = "<div class='message'><span class='name'>" . $_SESSION["user"] . 
                   ":</span> " . stripslashes(htmlspecialchars($text)) . 
                   "<span style='float:right;'>" . $date . "</span></div>";
        fwrite($fp, $message);
        fclose($fp);
    }
