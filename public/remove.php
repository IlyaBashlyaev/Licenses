<?php
    require 'db.php';
    $connection -> query('DELETE FROM `' . $_POST['type'] . '` WHERE `id` = ' . $_POST['id']);
?>