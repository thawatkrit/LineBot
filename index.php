<?php
    require 'global.php';
    $reponse = $GLOBALS['userList'][0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Line Bot - Admin</title>
    <link rel="stylesheet" href="css/style.css?v1">
</head>
<body>
    <h1>Push Message</h1>
    <form action="pushMessage.php" method="POST">
        <label for="toUser">to:</label>
        <input type="text" name="userId" value="U1cdfde31d77b135318bd76d016f834a7">
        <br>
        <label for="text">text:</label>
        <input type="text" name="text" id="" value="<?php echo $response; ?>">
        <input type="submit" value="Send" name="send">
    </form>
</body>
</html>