<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";

function validaProducto($producto, $prodId)
{
 if ($producto === false) {
  $htmlId = htmlentities($prodId);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "Producto no encontrado.",
   type: "/error/productonoencontrado.html",
   detail: "No se encontró ningún producto con el id $htmlId.",
  );
 }
}
