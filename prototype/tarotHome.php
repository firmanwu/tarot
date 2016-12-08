<?php
    session_start();
    if (isset($_GET["user"])) {
        $user = $_GET["user"];
    }
    else {
        $user = "";
    }

    if ("" != $user) {
        if ("logout" == $user) {
            $name = $_SESSION["user"];
            $message = "<div>User:<span class='name'>" . $name . "</span> leaves.</div>";
            $fp = fopen("message.html", "a+");
            fwrite($fp, $message);
            fclose($fp);
            session_destroy();
            header("Location:tarotHome.php");
        }

        if (!isset($_SESSION["user"])) {
            $_SESSION["user"] = $user;
            $fp = fopen("message.html", "a+");
            $message = "<div>User:<span class='name'>" . $user . "</span> enters.</div>";
            fwrite($fp, $message);
            fclose($fp);
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Welcome Tarot World</title>
<script src="jquery-3.1.1.min.js"></script>
<script>
$(document).ready(function() {
    $("form#chat-form").submit(function() {
        var message = $("#chatMessage").val();

        $.post("recordChat.php", { text: message });
        $("#chatMessage").val('');
        return false;
    });

    function loadContent() {
        var oldHeight = $("#chatWindow").prop("scrollHeight") - 20;

        $.ajax({
            url: "message.html",
            cache: false,
            success: function(content) {
                $("#chatWindow").html(content);

                var newHeight = $("#chatWindow").prop("scrollHeight") - 20;

                if (newHeight > oldHeight) {
                    $("#chatWindow").animate(
                        { scrollTop: newHeight }, 'slow');
                }
            }
        });
    }
    setInterval(loadContent, 1000);
});
</script>
</head>
<body>
<div id="chatWrapper">
<?php
if (!isset($_SESSION["user"])) {
?>
    <form id="loginForm" action="tarotHome.php">
        User: <input type="text" name="user" id="username" />
        <input type="submit" value="login" id="login" />
    </form>
<?php
}
else {
?>
    <div id="chatTitle">
        <div class="welcome">
            <p> Welcome <?php echo $_SESSION["user"]; ?></p>
        </div>
        <div class="logout">
            <p><a href="tarotHome.php?user=logout" id="logout">Logout</a></p>
        </div>
    </div>
    <div id="chatWindow"></div>
    <div id="chatForm">
        <form id="chat-form" action="#">
            <input type="text" name="message" id="chatMessage" />
            <input type="submit" id="send" value="send" />
        </form>
    </div>
<?php
}
?>
</div>
</body>
</html>