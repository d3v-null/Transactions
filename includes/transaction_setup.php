<?php
    define('DEBUG', 'true');
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'transaction');

    mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
    mysql_select_db(DB_NAME) or die(mysql_error());
?>