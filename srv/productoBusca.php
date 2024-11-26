<?php

require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_PRODUCTO.php";

function productoBusca(PDO $pdo, int $id)
{
 return selectFirst(pdo: $pdo, from: PRODUCTO, where: [PRO_ID => $id]);
}
