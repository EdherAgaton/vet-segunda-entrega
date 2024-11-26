<?php

require_once __DIR__ . "/../lib/php/fetch.php";

function ventaEnCapturaBusca(PDO $pdo)
{
 return fetch($pdo->query("SELECT * FROM VENTA WHERE VENT_EN_CAPTURA = 1"));
}
