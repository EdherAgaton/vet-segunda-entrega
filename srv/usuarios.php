<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_USUARIO.php";

ejecutaServicio(function () {

    //$lista = select(pdo: Bd::pdo(), from: USUARIO, orderBy: USU_NOMBRE);

    $lista = fetchAll(Bd::pdo()->query(
        "SELECT
          U.USU_ID,
          U.USU_EMAIL,
          U.USU_NOMBRE,
          GROUP_CONCAT(UR.ROL_ID, ', ') AS roles
         FROM USUARIO U
          LEFT JOIN USU_ROL UR
          ON U.USU_ID = UR.USU_ID
         GROUP BY U.USU_EMAIL
         ORDER BY U.USU_EMAIL"
       ));

    $render = "";
    foreach ($lista as $modelo) {
        $encodeId = urlencode($modelo[USU_ID]);
        $id = htmlentities($encodeId);
        $nombre = htmlentities($modelo[USU_NOMBRE]);
        $roles = $modelo["roles"] === null || $modelo["roles"] === ""
        ? "<em>-- Sin roles --</em>"
        : htmlentities($modelo["roles"]);

        $render .=
            "<dt><a href='modifica-usuario.html?id=$id'>$nombre</a></dt>
             <dd><a href='modifica-usuario.html?id=$id'>$roles</a></dd>";
    }

    devuelveJson(["lista" => ["innerHTML" => $render]]);
});
