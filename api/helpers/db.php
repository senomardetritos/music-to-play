<?php
include './header.php';

class DB
{
    protected $mysql;

    function __construct()
    {
        $this->mysql = $this->connet();
    }

    public function connet()
    {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $mysql = new mysqli($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);
            /* Defina o conjunto de caracteres desejado após estabelecer uma conexão */
            $mysql->set_charset('utf8mb4');
            return $mysql;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function query($query)
    {
        $result = $this->mysql->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function execute($query)
    {
        $result = $this->mysql->query($query);
        return $result;
    }

    public static function data($value)
    {
        $chars = ['\'', '`', '"'];
        foreach ($chars as $char) {
            $value = implode('', explode($char, $value));
        }
        return '\'' . $value . '\'';
    }

    public function check_login()
    {
        $header = apache_request_headers();
        if (!isset($header['Authorization'])) {
            echo json_encode(['error' => 'Authorization não encontrado']);
            die();
        }
        try {
            $auth = $header['Authorization'];
            $auth = explode(' ', $auth)[1];
            $id = explode('_', $auth)[0];
            $token = explode('_', $auth)[1];

            $result = $this->select('usuarios', [
                'where' => ['id', '=', DB::data($id)]
            ]);

            $valid = crypt($result[0]['id'], $_ENV['CRYPT_SALT']);
            $valid .= crypt($result[0]['email'], $_ENV['CRYPT_SALT']);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Authorization inválido']);
            die();
        }
        if ($token == $valid) {
            return $id;
        } else {
            echo json_encode(['error' => 'Token inválido']);
            die();
        }
    }

    public function selectSQL($table, $params = array())
    {
        try {
            $query = ['SELECT'];
            if (isset($params['fields'])) $fields = implode(', ', $params['fields']);
            else $fields = '*';
            $query[] = $fields;
            $query[] = 'FROM';
            $query[] = $table;
            if (isset($params['where'])) {
                $query[] = 'WHERE';
                $query[] = $this->where($params['where']);
            }
            if (isset($params['group'])) {
                $query[] = 'GROUP BY';
                $query[] = implode(', ', $params['group']);
            }
            if (isset($params['order'])) {
                $query[] = 'ORDER BY';
                $query[] = implode(', ', $params['order']);
            }
            if (isset($params['orderDesc'])) {
                $query[] = 'ORDER BY';
                $query[] = implode(', ', $params['order']);
                $query[] = 'DESC';
            }
            if (isset($params['limit'])) {
                $query[] = 'LIMIT';
                $query[] = implode(', ', $params['limit']);
            }

            $query = implode(' ', $query);
            return $query;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            die();
        }
    }

    public function select($table, $params = array())
    {
        try {
            $query = $this->selectSQL($table, $params);
            return $this->query($query);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            die();
        }
    }

    private function where($where)
    {
        try {
            $query = [];
            foreach ($where as $k => $v) {
                if ($k == 'and') {
                    if (isset($query[0])) {
                        $query[] = 'and';
                    }
                    $query[] = '(';
                    $query[] = $this->where($v);
                    $query[] = ')';
                } else if ($k == 'or') {
                    if (isset($query[0])) {
                        $query[] = 'or';
                    }
                    $query[] = '(';
                    $query[] = $this->where($v);
                    $query[] = ')';
                } else {
                    if (is_array($v)) {
                        $query[] = implode(' ', $v);
                    } else {
                        $query[] = $v;
                    }
                }
            }
            $query = implode(' ', $query);
            return $query;
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            die();
        }
    }

    public function updateSQL($table, $data, $where)
    {
        $query = ['UPDATE'];
        $query[] = $table;
        $query[] = 'SET';
        $fields = [];
        foreach ($data as $k => $v) {
            $fields[] = $k . ' = ' . DB::data($v);
        }
        $query[] = implode(', ', $fields);
        $query[] = 'WHERE';
        $query[] = $this->where($where);

        $query = implode(' ', $query);
        return $query;
    }

    public function update($table, $data, $where)
    {
        $query = $this->updateSQL($table, $data, $where);
        return $this->execute($query);
    }

    public function insertSQL($table, $data)
    {
        $query = ['INSERT INTO'];
        $query[] = $table;
        $query[] = '(';
        $fields = [];
        foreach ($data as $k => $v) {
            $fields[] = $k;
        }
        $query[] = implode(', ', $fields);

        $query[] = ') VALUES (';

        $fields = [];
        foreach ($data as $k => $v) {
            $fields[] = DB::data($v);
        }
        $query[] = implode(', ', $fields);
        $query[] = ')';

        $query = implode(' ', $query);
        return $query;
    }

    public function insert($table, $data)
    {
        $query = $this->insertSQL($table, $data);
        return $this->execute($query);
    }
}
