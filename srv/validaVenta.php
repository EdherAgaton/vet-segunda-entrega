<?php

require_once __DIR__ . "/../lib/php/BAD_REQUEST.php";

function validaVenta($venta)
{
 if ($venta === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Venta en captura no encontrada.",
   type: "/error/ventaencapturanoencontrada.html",
   detail: "No se encontró ninguna venta en captura.",
  );
}
