<?php
    require 'messageController.php';

    foreach($userList as $user) {
        echo '<h2>'.$user.'</h2>';
        foreach($user->getMessages as $message) {
            echo '<p>'.$message.'</p>';
        }
    }