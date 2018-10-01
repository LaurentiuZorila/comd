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
     * @return mixed
     */
    public function createTable($table)
    {
        $table  = trim(strtolower($table));
        $tbl    = Params::PREFIX . $table;
        $sql = " 
            CREATE TABLE IF NOT EXISTS {$tbl} (
                id               INT AUTO_INCREMENT PRIMARY KEY, 
                offices_id       INT(),
                departments_id   INT(),
                employees_id     INT(11),
                year             YEAR(4),
                month            TINYINT(2),
                quantity         SMALLINT(5)
            )";

        if ($this->_toCreate) {
            try {
                return $this->_db->execute($sql);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
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