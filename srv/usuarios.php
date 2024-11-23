<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_USUARIO.php";

ejecutaServicio(function () {

    $lista = select(pdo: Bd::pdo(), from: USUARIO, orderBy: USU_NOMBRE);

    $render = "";
    foreach ($lista as $modelo) {
        $encodeId = urlencode($modelo[USU_ID]);
        $id = htmlentities($encodeId);
        $nombre = htmlentities($modelo[USU_NOMBRE]);

        $render .=
            "<dd>
                <p>
                    <a href='modifica-usuario.html?id=$id'>$nombre</a>
                </p>
            </dd>";
    }

    devuelveJson(["lista" => ["innerHTML" => $render]]);
});
