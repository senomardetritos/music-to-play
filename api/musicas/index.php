<?php

include '../helpers/header.php';
include '../helpers/db.php';

class Musicas
{
    public static function estilos()
    {
        $db = new DB();
        $usuarios_id = $db->check_login();
        $result = $db->select('musicas', [
            'fields' => ['estilo'],
            'where' => ['usuarios_id', '=', DB::data($usuarios_id)],
            'group' => ['estilo'],
            'order' => ['estilo']
        ]);

        echo json_encode(['data' => $result]);
    }

    public static function cantores()
    {
        $db = new DB();
        $usuarios_id = $db->check_login();
        $result = $db->select('musicas', [
            'fields' => ['cantor'],
            'where' => [
                'and' => [
                    ['usuarios_id', '=', DB::data($usuarios_id)],
                    'and',
                    ['estilo', '=', DB::data($_GET['estilo'])]
                ],
            ],
            'group' => ['cantor'],
            'order' => ['cantor']
        ]);

        echo json_encode(['data' => $result]);
    }

    public static function musicas()
    {
        $db = new DB();
        $usuarios_id = $db->check_login();
        $result = $db->select('musicas', [
            'fields' => ['id', 'musica'],
            'where' => [
                'and' => [
                    ['usuarios_id', '=', DB::data($usuarios_id)],
                    'and',
                    ['cantor', '=', DB::data($_GET['cantor'])]
                ]
            ],
            'order' => ['musica']
        ]);

        echo json_encode(['data' => $result]);
    }

    public static function musica()
    {
        $db = new DB();
        $usuarios_id = $db->check_login();

        $result = $db->select('musicas', [
            'where' => [
                'and' => [
                    ['usuarios_id', '=', DB::data($usuarios_id)],
                    'and',
                    ['id', '=', DB::data($_GET['id'])]
                ]
            ]
        ]);

        echo json_encode(['data' => $result]);
    }

    public static function cadastrar()
    {
        $post = json_decode(file_get_contents('php://input'));
        $db = new DB();
        $usuarios_id = $db->check_login();
        $data = [
            'estilo' => DB::data($post->estilo),
            'cantor' => DB::data($post->cantor),
            'musica' => DB::data($post->musica),
        ];
        if (isset($post->id)) {
            $db->update('musicas', $data, [
                ['id', '=', DB::data($post->id)],
                'and',
                ['usuarios_id', '=', DB::data($usuarios_id)]
            ]);
            return;
        } else {
            $data['usuarios_id'] = DB::data($usuarios_id);
            $db->insert('musicas', $data);
        }

        if ($data) {
            echo json_encode(['data' => 'Cadastrada']);
        } else {
            echo json_encode(['error' => 'Erro ao cadastrar música']);
        }
    }

    public static function deletar()
    {
        $post = json_decode(file_get_contents('php://input'));
        $db = new DB();
        $usuarios_id = $db->check_login();
        $db->delete('musicas', [
            ['id', '=', DB::data($post->id)],
            'and',
            ['usuarios_id', '=', DB::data($usuarios_id)]
        ]);
        echo json_encode(['data' => 'Deletada']);
    }
}

$action = $_REQUEST['action'];
Musicas::$action();
