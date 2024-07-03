<?php
    require 'db.php';

    if ($_POST['type'] == 'licenses') {
        $runtimeStart = explode('-', $_POST['runtime-start']);
        $runtimeStart = $runtimeStart[2] . '.' . $runtimeStart[1] . '.' . $runtimeStart[0];
        $connection -> query('UPDATE `licenses` SET `name` = "' . $_POST['name'] . '", `description` = "' . $_POST['description'] . '", `price` = ' . (int) $_POST['price'] . ', `renovation-month` = ' . (int) $_POST['renovation-month'] . ', `payment` = "' . $_POST['payment'] . '", `runtime-start` = "' . $runtimeStart . '" WHERE `id` = ' . $_POST['id']);
    }

    else if ($_POST['type'] == 'providers')
        $connection -> query('UPDATE `providers` SET `license-id` = "' . $_POST['license-id'] . '", `name` = "' . $_POST['name'] . '", `description` = "' . $_POST['description'] . '", `domain` = "' . $_POST['domain'] . '", `link` = "' . $_POST['link'] . '" WHERE `id` = ' . $_POST['id']);

    else if ($_POST['type'] == 'clients')
        $connection -> query('UPDATE `clients` SET `license-id` = "' . $_POST['license-id'] . '", `name` = "' . $_POST['name'] . '", `short-name` = "' . $_POST['short-name'] . '" WHERE `id` = ' . $_POST['id']);
?>