<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/fetchAll.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_USUARIO.php";

ejecutaServicio(function () {

    $usuId = recuperaIdEntero("id");

    $pdo = Bd::pdo();

    $modelo = selectFirst(pdo: $pdo, from: USUARIO, where: [USU_ID => $usuId]);

    if ($modelo === false) {
        $idHtml = htmlentities($usuId);
        throw new ProblemDetails(
            status: NOT_FOUND,
            title: "Cliente no encontrado.",
            type: "/error/cliente-no-encontrado.html",
            detail: "No se encontró ningún cliente con el id $htmlId."
        );
    }else{

        $rolIds = fetchAll(
            $pdo->query(
             "SELECT ROL_ID
               FROM USU_ROL
               WHERE USU_ID = :USU_ID
               ORDER BY USU_ID"
            ),
            [":USU_ID" => $usuId],
            PDO::FETCH_COLUMN
           );

   

    devuelveJson([
        "id" => ["value" => $usuId],
        "nombre" => ["value" => $modelo[USU_NOMBRE]],
        "email" => ["value" => $modelo[USU_EMAIL]],
        "telefono" => ["value" => $modelo[USU_TELEFONO]],
        "direccion" => ["value" => $modelo[USU_DIRECCION]],
        "rolIds[]" => $rolIds

    ]);
  }
});
