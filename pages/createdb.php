<?php
    include_once('classes.php');
    $pdo=Tool::connect();

    $userfile='create table Title(id int not null auto_increment primary key, title varchar(32)not null unique,
                textpath varchar(255))default charset="utf8"';

    $pdo->exec($userfile);
?>