<?php

require_once __DIR__ . "/../lib/php/fetchAll.php";

function detVentaConsulta(PDO $pdo, int $ventaId)
{
 return fetchAll(
  $pdo->query(
   "SELECT
    DV.PRO_ID,
    P.PRO_NOMBRE,
    P.PROD_EXISTENCIAS,
    P.PRO_PRECIO,
    DV.DTV_CANTIDAD,
    DV.DTV_PRECIO
   FROM DET_VENTA DV, PRODUCTO P
   WHERE
    DV.PRO_ID = P.PRO_ID
    AND DV.VENT_ID = :VENT_ID
   ORDER BY P.PRO_NOMBRE"
  ),
  [":VENT_ID" => $ventaId]
 );
}
