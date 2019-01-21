<?php
class Create
{
    /**
     * @var CustomerDB|null
     */
    private $_db;

    /**
     * @var bool
     */
    private $_toCreate  = true;

    /***
     * @var
     */
    public $errors      = [];


    /**
     * Create constructor.
     */
    public function __construct()
    {
        $this->_db = CustomerDB::getInstance();
    }


    /**
     * @param $table
     * @param string $quantityType
     * @param bool $prefix
     * @return mixed
     */
    public function createTable($table, $quantityType = 'int', $prefix = false)
    {
        $table  = trim(strtolower($table));
        if ($prefix) {
            $table    = Params::PREFIX . $table;
        }

        switch ($quantityType) {
            case 'int':
                $sql = " 
            CREATE TABLE IF NOT EXISTS {$table} (
                id                    INT AUTO_INCREMENT PRIMARY KEY, 
                offices_id            INT(11),
                departments_id        INT(11),
                employees_id          INT(11),
                employees_average_id  VARCHAR(255),
                event_id              INT(11),
                year                  YEAR(4),
                month                 TINYINT(2),
                quantity              INT(11),
                days                  VARCHAR(100)
            )";
                break;
            case 'float':
                $sql = " 
            CREATE TABLE IF NOT EXISTS {$table} (
                id                    INT AUTO_INCREMENT PRIMARY KEY, 
                offices_id            INT(11),
                departments_id        INT(11),
                employees_id          INT(11),
                employees_average_id  VARCHAR(255),
                event_id              INT(11),
                year                  YEAR(4),
                month                 TINYINT(2),
                quantity              FLOAT(5),
                days                  VARCHAR(100)
            )";
                break;
        }

            try {
                return $this->_db->execute($sql);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
    }


    /**
     * @param $table
     * @param array $columns
     * @param array $types
     * @return bool|PDOStatement
     */
    public function addColumns($table, $columns = array(), $types = array())
    {
        $params = array_combine($columns, $types);
        $conditions = [];

        foreach ($params as $column => $type) {
            $conditions[] = sprintf("ADD %s %s ", $column, $type);
        }
        $conditions = implode(', ', $conditions);

        $sql = " 
            ALTER TABLE  {$table} (
                {$conditions}
            )";

        if (!$this->_toCreate) {
            try {
                return $this->_db->getPdo()->query($sql);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }


    /**
     * @param $table
     * @param bool $prefix
     * @return $this
     */
    public function ifExist($table, $prefix = false)
    {
        if ($prefix) {
            $tbl = Params::PREFIX . $table;
            $sql = "DESCRIBE {$tbl}";
        } else {
            $sql = "DESCRIBE {$table}";
        }

        if ($this->_db->getPdo()->query($sql)) {
            $this->addError("{$table} table already exist.");
        }

        if (count($this->errors) > 0) {
            $this->_toCreate = false;
        }
    }


    /**
     * @param $error
     * @return mixed
     */
    public function addError($error)
    {
        $this->errors[] = $error;
    }


    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function toCreate()
    {
        return $this->_toCreate;
    }

}