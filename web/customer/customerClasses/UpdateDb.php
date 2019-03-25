<?php
class UpdateDb
{
    public $filename;

    public $path;

    public $extensionsAllowed = [];

    public $months = 0;

    public $table;

    public $year;

    public $month;

    public $headers = [];

    public $fileError;

    private $_db;

    private $_userId;

    private $_officesId;

    private $_toDelete = false;

    private $_columnsError = false;

    private $_fileDir = 'filesToUpload/';



    public function __construct()
    {
        $user       = new CustomerUser();
        $this->_db  = CustomerDB::getInstance();
        $this->extensionsAllowed = Params::EXTENSIONS;
        $this->_userId    = $user->customerId();
        $this->_officesId = $user->officesId();
        if (Input::existsName('post', Tokens::getInputName())) {
            $this->year     = Input::post('year');
            $this->month    = Input::post('month');
            $this->table    = trim(Input::post('tables'));
            $this->filename = Session::get(Config::get('files/complete_dirFile'));
        }

    }


    /**
     * @return string
     */
    public function fileDir()
    {
        return $this->_fileDir;
    }


    /**
     * @param $items
     * @return bool
     */
    public function checkFileHeaders($items)
    {
        if ($this->checkCommonTables()) {
            return $items[0] == 'Id' && $items[1] == 'Name' && $items[2] == 'Quantity' && $items[3] == 'Days' ? true : false;
        }
        return $items[0] == 'Id' && $items[1] == 'Name' && $items[2] == 'Quantity' ? true : false;
    }



    /**
     * @return bool
     */
    public function toDelete()
    {
        return $this->setToDelete()->_toDelete;
    }


    /**
     * @return $this
     */
    private function setToDelete()
    {
        $months = $this->_db->get($this->setTable(), AC::where([['offices_id', $this->_officesId], ['year', $this->year]]), ['month'])->results();

        foreach ($months as $dbMonth) {
            $allDbMonths[] = $dbMonth->month;
        }
        if (count($allDbMonths) > 0) {
            foreach ($allDbMonths as $dbMonth) {
                $allMonths[] = $dbMonth;
                // Months from database
                $allMonths = array_unique($allMonths);
            }
        } else {
            $allMonths = [];
        }

        if (in_array($this->month, $allMonths)) {
            $this->_toDelete = true;
        }
        return $this;
    }


    /**
     * @return string
     */
    public function getTable()
    {
        return $this->setTable();
    }



    /**
     * @return bool
     */
    public function checkCommonTables()
    {
        return in_array($this->getTable(), Params::PREFIX_TBL_COMMON);
    }


    /**
     * @param $quantity
     * @param $days
     * @return $this
     */
    public function checkColumns($quantity, $days)
    {
        $days = explode(',',$days);
        if (!empty($quantity) && !empty($days)) {
            if ((int)$quantity !== count($days)) {
                $this->_columnsError = true;
                $this->fileError = 'columns_not_equal';
            }
        } else {
            $this->_columnsError = true;
            $this->fileError = 'empty_cells';
        }
        return $this;
    }


    /**
     * @param $fileId
     * @return bool
     */
    public function checkIds($fileId)
    {
        $ids = $this->_db->get(Params::TBL_EMPLOYEES, AC::where(['offices_id', $this->_userId]), ['id'])->results();
        foreach ($ids as $id) {
            $allowedIds[] = $id->id;
        }
        return in_array($fileId, $allowedIds);
    }


    /**
     * @return bool
     */
    public function columnsError()
    {
        return $this->_columnsError;
    }


    /**
     * @return string
     */
    private function setTable()
    {
        return $this->checkPrefix() ? $this->table : Params::PREFIX . $this->table;
    }


    /**
     * @return bool
     */
    private function checkPrefix()
    {
        if (strpos($this->table, '_') > 0 && substr($this->table, 0, 4) == 'cmd_') {
            return true;
        }
        return false;
    }
}