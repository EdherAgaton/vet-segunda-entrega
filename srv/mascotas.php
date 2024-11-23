<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_MASCOTA.php";

ejecutaServicio(function () {

    $lista = select(pdo: Bd::pdo(), from: MASCOTA, orderBy: MAS_NOMBRE);

    $render = "";
    foreach ($lista as $modelo) {
        $encodeId = urlencode($modelo[MAS_ID]);
        $id = htmlentities($encodeId);
        $nombre = htmlentities($modelo[MAS_NOMBRE]);

        $render .=
            "<dd>
                <p>
                    <a href='modifica-mascota.html?id=$id'>$nombre</a>
                </p>
            </dd>";
    }

    devuelveJson(["lista" => ["innerHTML" => $render]]);
});
