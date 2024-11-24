<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_ARCHIVO.php";

ejecutaServicio(function () {

 // Evita que la imagen se cargue en el caché del navegador.
 header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache");

 $archId = recuperaIdEntero("id");

 $archivo =
  selectFirst(pdo: Bd::pdo(), from: ARCHIVO, where: [ARCH_ID => $archId]);

 if ($archivo === false) {
  $idHtml = htmlentities($archId);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "Archivo no encontrado.",
   type: "/error/archivonoencontrado.html",
   detail: "No se encontró ningún archivo con el id $idHtml.",
  );
 }

 $bytes = $archivo[ARCH_BYTES];
 $contentType = (new finfo(FILEINFO_MIME_TYPE))->buffer($bytes);
 header("Content-Type: $contentType");
 echo $bytes;
});
