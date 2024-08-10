<?php

include '../helpers/header.php';
include '../helpers/db.php';
include '../helpers/mail.php';

class Usuarios
{

    public static function login()
    {
        $post = json_decode(file_get_contents('php://input'));

        $db = new DB();

        $data = $db->select('usuarios', [
            'where' => [
                ['email', '=', DB::data($post->email)],
            ]
        ]);

        if ($data) {
            $pass = crypt($post->password, $_ENV['CRYPT_SALT']);
            if ($data[0]['password'] == $pass) {
                $token = crypt($data[0]['id'], $_ENV['CRYPT_SALT']);
                $token .= crypt($data[0]['email'], $_ENV['CRYPT_SALT']);
                echo json_encode(['data' => [
                    'token' => $token,
                    'id' => $data[0]['id'],
                    'nome' => $data[0]['nome'],
                    'email' => $data[0]['email'],
                ]]);
            } else {
                echo json_encode(['error' => 'Senha inválida']);
            }
        } else {
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }

    public static function cadastrar()
    {
        $post = json_decode(file_get_contents('php://input'));

        $db = new DB();
        $pass = crypt($post->password, $_ENV['CRYPT_SALT']);

        $data = [
            'nome' => DB::data($post->nome),
            'email' => DB::data($post->email),
            'password' => DB::data($pass),
        ];

        $data = $db->insert('usuarios', $data);

        if ($data) {
            echo json_encode(['data' => 'Cadastrado']);
        } else {
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }

    public static function alterarSenha()
    {
        $post = json_decode(file_get_contents('php://input'));

        $db = new DB();
        $usuarios_id = $db->check_login();

        $data = $db->select('usuarios', [
            'where' => [
                ['id', '=', DB::data($usuarios_id)],
            ]
        ]);

        if ($data) {
            $pass = crypt($post->password, $_ENV['CRYPT_SALT']);
            if ($data[0]['password'] == $pass) {
                $newpass = crypt($post->newpassword, $_ENV['CRYPT_SALT']);
                $db->update('usuarios', [
                    'password' => $newpass
                ], ['id', '=', DB::data($usuarios_id)]);
            } else {
                echo json_encode(['error' => 'Senha inválida']);
            }
        } else {
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }

    public static function enviarRecuperar()
    {
        $post = json_decode(file_get_contents('php://input'));

        $db = new DB();

        if (!isset($post->email)) {
            echo json_encode(['error' => 'Email obrigatório']);
            die();
        }

        $data = $db->select('usuarios', [
            'where' => [
                ['email', '=', DB::data($post->email)],
            ]
        ]);

        if ($data) {
            try {
                $code = rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9);
                $to      = $post->email;
                $subject = 'Recuperação de Senha';
                $message = '
                    <h3>Recuperação de Senha</h3>
                    <h5>Seu código para recuerar a senha:</h5>
                    <h1>' . $code . '</h1>
                ';
                $db->update('usuarios', [
                    'code' => $code,
                ], ['email', '=', DB::data($post->email)]);
                $mail = new Mail();
                $mail->send($to, $subject, $message);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Usuário não encontrado']);
        }
    }
}

$action = $_REQUEST['action'];
Usuarios::$action();
