<?php
    require 'db.php';

    if ($_POST['type'] == 'licenses') {
        $maxLicense = $connection -> query('SELECT * FROM `licenses` WHERE `id` = (SELECT max(`id`) FROM `licenses`)')
                      -> fetch_assoc();
        if ($maxLicense) {$licenseId = (int) $maxLicense['id'] + 1;}
        else {$licenseId = 0;}

        $runtimeStart = explode('-', $_POST['runtime-start']);
        $runtimeStart = $runtimeStart[2] . '.' . $runtimeStart[1] . '.' . $runtimeStart[0];

        $connection -> query('INSERT INTO `licenses` (`id`, `name`, `description`, `price`, `renovation-month`, `payment`, `runtime-start`) VALUES
        (' . $licenseId . ', "' . $_POST['name'] . '", "' . $_POST['description'] . '", ' . (int) $_POST['price'] . ', ' . (int) $_POST['renovation-month'] . ', "' . $_POST['payment'] . '", "' . $runtimeStart . '")');
    }

    else if ($_POST['type'] == 'providers') {
        $maxProvider = $connection -> query('SELECT * FROM `providers` WHERE `id` = (SELECT max(`id`) FROM `providers`)')
                      -> fetch_assoc();
        if ($maxProvider) {$providerId = (int) $maxProvider['id'] + 1;}
        else {$providerId = 0;}

        $connection -> query('INSERT INTO `providers` (`id`, `license-id`, `name`, `description`, `domain`, `link`) VALUES
        (' . $providerId . ', "' . $_POST['license-id'] . '", "' . $_POST['name'] . '", "' . $_POST['description'] . '", "' . $_POST['domain'] . '", "' . $_POST['link'] . '")');
    }

    else if ($_POST['type'] == 'clients') {
        $maxClient = $connection -> query('SELECT * FROM `clients` WHERE `id` = (SELECT max(`id`) FROM `clients`)')
                      -> fetch_assoc();
        if ($maxClient) {$clientId = (int) $maxClient['id'] + 1;}
        else {$clientId = 0;}

        $connection -> query('INSERT INTO `clients` (`id`, `license-id`, `name`, `short-name`) VALUES
        (' . $clientId . ', "' . $_POST['license-id'] . '", "' . $_POST['name'] . '", "' . $_POST['short-name'] . '")');
        echo 'INSERT INTO `clients` (`id`, `license-id`, `name`, `short-name`) VALUES
        (' . $clientId . ', "' . $_POST['license-id'] . '", "' . $_POST['name'] . '", "' . $_POST['short-name'] . '")';
    }
?>