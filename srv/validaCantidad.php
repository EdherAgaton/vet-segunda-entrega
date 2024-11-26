<?php

require_once __DIR__ . "/../lib/php/BAD_REQUEST.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";

function validaCantidad(false|null|float $cantidad)
{
 if ($cantidad === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta la cantidad.",
   type: "/error/faltacantidad.html",
   detail: "La solicitud no tiene el valor de cantidad."
  );

 if ($cantidad === null)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta la cantidad.",
   type: "/error/cantidadenblanco.html",
   detail: "Pon un número en el campo cantidad."
  );

 return $cantidad;
}
