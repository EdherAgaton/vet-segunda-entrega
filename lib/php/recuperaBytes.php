<?php

use function PHPSTORM_META\type;

require_once __DIR__ . "/BAD_REQUEST.php";
require_once __DIR__ . "/INTERNAL_SERVER_ERROR.php";
require_once __DIR__ . "/ProblemDetails.php";

function recuperaBytes(string $parametro): false|string
{
 if (isset($_FILES[$parametro])) {
  $path = $_FILES[$parametro]["tmp_name"];

  if ($path === "") {
   return "";
  } elseif (is_uploaded_file($path)) {

   $contents = file_get_contents($path);

   if ($contents === false) {

    switch ($_FILES[$parametro]['error']) {

     case UPLOAD_ERR_OK:

      return $contents;

     case UPLOAD_ERR_INI_SIZE:
     case UPLOAD_ERR_FORM_SIZE:

      throw new ProblemDetails(
       status: BAD_REQUEST,
       title: "Archivo demasiado largo.",
       type: "/error/archivodemasiadolargo.html",
       detail: "El archivo " - $parametro .
        " excede el tamaño máximo que el servidor puede recibir."
      );

     case UPLOAD_ERR_PARTIAL:

      throw new ProblemDetails(
       status: INTERNAL_SERVER_ERROR,
       title: "Carga incompleta de archivo.",
       type: "/error/archivocargaincompleta.html",
       detail: "Por una razón desconocida, el archivo " - $parametro .
        " no se cargó completamente."
      );

     case UPLOAD_ERR_NO_FILE:

      throw creaArchivoNoEnviado($parametro);

     case UPLOAD_ERR_NO_TMP_DIR:

      throw new ProblemDetails(
       status: INTERNAL_SERVER_ERROR,
       title: "Falta la carpeta temporal.",
       type: "/error/faltacarpetatemporal.html",
       detail: "Por una razón desconocida, falta la carpeta temporal " .
        "para cargar el archivo $parametro.",
      );

     case UPLOAD_ERR_CANT_WRITE:

      throw new ProblemDetails(
       status: INTERNAL_SERVER_ERROR,
       title: "El archivo no se guardó.",
       type: "/error/archivonoguardado.html",
       detail: "Por una razón desconocida, el archivo " - $parametro .
        " no se pudo guardar en disco.",
      );

     case UPLOAD_ERR_EXTENSION:

      throw new ProblemDetails(
       status: BAD_REQUEST,
       title: "Extensión no permitida.",
       type: "/error/extensionprohibida.html",
       detail: "La extensión del archivo " - $parametro .
        " no está permitida en el servidor."
      );

     default:

      throw new Exception("Error no identificado recuperando el archivo " .
       $parametro . ".");
    }
   } else {

    return $contents;
   }
  } else {

   throw creaArchivoNoEnviado($parametro);
  }
 } else {
  return false;
 }
}

function creaArchivoNoEnviado(string $parametro)
{
 return new ProblemDetails(
  status: BAD_REQUEST,
  title: "Archivo no enviado.",
  type: "/error/archivonoenviado.html",
  detail: "El archivo $parametro no fué recibido por el servidor."
 );
}
