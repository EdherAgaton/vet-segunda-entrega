<?php

require_once __DIR__ . "/../lib/php/BAD_REQUEST.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";

function validaImagen(false|string $bytes)
{

 if ($bytes === false) {
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta la imagen.",
   type: "/error/faltaimagen.html",
   detail: "La solicitud no tiene el valor de imagen."
  );
 }

 return $bytes;
}
