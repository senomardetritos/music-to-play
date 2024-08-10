<?php
include '../helpers/header.php';
include '../helpers/db.php';

class Listas
{

    public static function estilos()
    {
        $db = new DB();
        $usuarios_id = $db->check_login();
        $result = $db->select(
            'musicas',
            [
                'fields' => ['estilo', 'cantor'],
                'where' => ['and' => ['usuarios_id', '=', DB::data($usuarios_id)]],
                'group' => ['estilo', 'cantor'],
                'order' => ['estilo', 'cantor'],
            ]
        );
        echo json_encode(['data' => $result]);
    }
}

$action = $_REQUEST['action'];
Listas::$action();
