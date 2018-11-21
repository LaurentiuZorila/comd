<?php

/**
 * Database Class
 */
class BackendDB
{
    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * @var PDO
     */
    private $_pdo;

    /**
     * @var
     */
    private $_query;

    /**
     * @var bool
     */
    private $_error = false;

    /**
     * @var
     */
    private $_results;


    /**
     * @var int
     */
    private $_count = 0;


    /**
     * DB constructor.
     */
    private function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    /**
     * @return BackendDB|null
     */
    public static function getInstance()
    {

        if (!isset(self::$_instance)) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }
    /**
     * @return PDO
     */
    public function getPdo()
    {
        return $this->_pdo;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return call_user_func_array([$this->getPdo(), 'exec'], func_get_args());
    }

    /**
     * @param $sql
     * @param array $params
     * @param array $conditions
     * @return $this
     */
    public function query($sql, $params = [], $conditions = [])
    {
        $this->_error = false;

        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
                foreach ($conditions as $condition) {
                    $this->_query->bindValue($x, $condition);
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }


    /**
     * @param $action
     * @param $table
     * @param array $where
     * @return $this|bool
     */
    public function action($action, $table, $where = [])
    {
        // if value is: $where = ['field', '=', 'value']
        if (is_string($where[0])) {
            $where = [$where];
        }

        $operators = array('=', '>', '<', '>=', '<=');
        $condition = [];
        $params    = [];
        foreach ($where as $item) {
            if (is_array($item) && count($item) === 3) {
                list($field, $operator, $value) = $item;
                if (!in_array($operator, $operators)) {
                    continue;
                }
                $condition[] = sprintf('`%s` %s ?', $field, $operator);
                $params[]    = $value;
                continue;
            }
            if (is_string($item) && in_array(strtoupper($item), ['AND', 'OR'])) {
                $condition[] = $item;
                continue;
            }
        }

        if (empty($condition)) {
            $condition = ['1 = 1'];
        }

        $condition = implode(' ', $condition);
        $sql = sprintf("%s FROM %s WHERE %s", $action, $table, $condition);

        if (!$this->query($sql, $params)->error()) {
            return $this;
        }

        return false;
    }


    /**
     * @param $table
     * @param $where
     * @param array $columns
     * @return bool|BackendDB
     */
    public function get($table, $where, $columns = ['*'])
    {
        return $this->action('SELECT ' . implode(', ', $columns), $table, $where);
    }


    /**
     * @param $table
     * @param $where
     * @return bool|BackendDB
     */
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }


    /**
     * @param $table
     * @param array $fields
     * @return bool
     */
    public function insert($table, $fields = [])
    {
        $keys = array_keys($fields);

        foreach ($fields as $field) {
            $values[] = '?';
        }

        $columns = '`' . implode('`,`', $keys) . '`';
        $values  = sprintf(' VALUES (%s)', implode(',', $values));
        $insert  = sprintf('INSERT INTO %s (%s)', $table, $columns);

        $sql = $insert;
        $sql .= $values;

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }


    /**
     * @param $table
     * @param array $fields
     * @param array $conditions
     * @return bool
     */
    public function update($table, array $fields, array $conditions)
    {
        $set = [];
        foreach ($fields as $name => $value) {
            $set[] = $name . ' = ?';
        }
        $set = implode(', ', $set);

        $where = [];
        foreach ($conditions as $name => $value) {
            $where[] = $name . '= ?';
        }
        $where = implode(', ', $where);

        $sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $set, $where);

        if (!$this->query($sql, $fields, $conditions)->error()) {
            return true;
        }

        return false;
    }


    /**
     * @param $table
     * @param $where
     * @param string $column
     * @return BackendDB|bool
     */
    public function average($table, $where, $column = '')
    {
        return $this->action(sprintf('SELECT AVG(%s) as average', $column), $table, $where);
    }


    /**
     * @param $table
     * @param $where
     * @param string $column
     * @return bool|BackendDB
     */
    public function sum($table, $where, $column = '')
    {
        return $this->action(sprintf('SELECT SUM(%s) as sum', $column), $table, $where);
    }


    /**
     * @return mixed
     */
    public function results()
    {
        return $this->_results;
    }


    /**
     * @return mixed
     */
    public function first()
    {
        return ($results = $this->results()) && !empty($results[0]) ? $results[0] : null;
    }


    /**
     * @return bool
     */
    public function error()
    {
        return $this->_error;
    }


    /**
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }


    /**
     * @param $item
     * @return string
     */
    public function number($item)
    {
        return number_format($item, 2);
    }
}
