<?php
/**
* Database CommonDB
*/
class CommonDB
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
     * CommonDB constructor.
     */
    private function __construct()
    {
        $host       = CommonConfig::get('mysql/host');
        $dbName     = CommonConfig::get('mysql/db');
        $userName   = CommonConfig::get('mysql/username');
        $psw        = CommonConfig::get('mysql/password');
        try {
            $this->_pdo = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $userName, $psw);
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
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = array(), $conditions = array())
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
    public function get($table, $where, array $columns = ['*'])
    {
        return $this->action('SELECT ' . implode(', ', $columns), $table, $where);
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
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }


    /**
     * @return bool
     */
    public function error()
    {
        return $this->_error;
    }

}
