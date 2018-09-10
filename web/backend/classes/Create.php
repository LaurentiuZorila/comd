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
    public function createTable($table)
    {

        $sql = " 
            CREATE TABLE IF NOT EXISTS {$table} (
                id              int(10) NOT NULL AUTO_INCREMENT,
                offices_id      int(11) NOT NULL,
                departments_id  int(11) NOT NULL,
                user_id         varchar(100) NOT NULL,
                employees_id    int(11) NOT NULL,
                customer_id     varchar(100) NOT NULL,
                name            varchar(100) NOT NULL,
                department      varchar(50) NOT NULL,
                year            year(4) NOT NULL,
                month           int(2) NOT NULL,
                quantity        int(4) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

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