<?php

require_once __DIR__ . "/../lib/php/selectFirst.php";

function ventaEnCapturaAgrega(PDO $pdo)
{
 $pdo->exec("INSERT INTO VENTA (VENT_EN_CAPTURA) VALUES (1)");
}
