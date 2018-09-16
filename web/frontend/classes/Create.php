<?php
class Create
{
    /**
     * @var DB|null
     */
    private $_db;


    public $table;


    public $exists = false;

    /**
     * Create constructor.
     */
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }


    /**
     * @param array $columns
     * @param array $types
     * @return mixed
     */
    public function createTable($table = '')
    {

//        $params = array_combine($columns, $types);
//        $conditions = [];
//
//        foreach ($params as $column => $type) {
//            $conditions[] = sprintf("%s %s NOT NULL", $column, $type);
//        }
//        $conditions = implode(', ', $conditions);

        $sql = " 
            CREATE TABLE IF NOT EXISTS {$table} (
                id            INT AUTO_INCREMENT PRIMARY KEY, 
                user_id       VARCHAR(100),
                customer_id   VARCHAR(100),
                year          YEAR(4),
                month         INT(2),
                quantity      VARCHAR(5)
            )";

        return $this->_db->execute($sql);
    }


    /**
     * @param array $columns
     * @param array $types
     * @return bool|PDOStatement
     */
    public function addColumns($columns = array(), $types = array())
    {
        $params = array_combine($columns, $types);
        $conditions = [];

        foreach ($params as $column => $type) {
            $conditions[] = sprintf("ADD %s %s ", $column, $type);
        }
        $conditions = implode(', ', $conditions);

        $sql = " 
            ALTER TABLE  {$this->table} (
                {$conditions}
            )";

        if ($this->ckeckTable()) {
            try {
                return $this->_db->getPdo()->query($sql);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }
    }


    /**
     * @return bool
     */
    public function ckeckTable()
    {
        $sql = "DESCRIBE {$this->table}";

        if ($this->_db->getPdo()->query($sql)) {
            return true;
        }
        return false;
    }



}