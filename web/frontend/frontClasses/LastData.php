<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/30/18
 * Time: 5:31 PM
 */

class LastData
{

    private $_year;

    private $_month;

    private $_uId;

    private $_officeId;


    /**
     * LastData constructor.
     */
    public function __construct()
    {
        if (Input::existsName('get', 'userId')) {
            $this->_uId = Input::get('userId');
        }
        if (Input::existsName('get', 'officeId')) {
            $this->_officeId = Input::get('officeId');
        }
        $this->_month = date('n') - 1;
        $this->_year  = date('Y');
    }


    /**
     * @param bool $multiple
     * @return array
     */
    private function where($multiple = true)
    {
        if ($multiple) {
            return ActionCond::where([['id', $this->_uId], ['year', $this->_year], ['month', $this->_month]]);
        } else {
            return ActionCond::where(['id', $this->_officeId]);
        }
    }


    /**
     * @return array|mixed
     */
    private function getTables()
    {
        $tables = FrontendDB::getInstance()->get(Params::TBL_OFFICE, $this->where(false), ['tables'])->first();
        $tables = explode(',', $tables->tables);
        return $tables;
    }


    /**
     * @return mixed
     */
    public function getData($lang)
    {
        foreach ($this->getTables() as $tables) {
            $data[Translate::t($lang, $tables)] = FrontendDB::getInstance()->get(Params::PREFIX . $tables, $this->where(), ['quantity'])->first()->quantity;
        }
        return $data;
    }


    /**
     * @param $data
     * @return mixed|string
     */
    public function chartData($data, $lang)
    {
        $recordsExist = Common::checkValues($this->getData($lang));
        if ($recordsExist) {
            if ($data === 'key') {
                return Js::key($this->getData($lang), 'ucfirst');
            } elseif ($data === 'label') {
                return Js::values($this->getData($lang));
            }
        } else {
            Errors::setErrorType('info', Translate::t($lang, 'for', ['ucfirst' => true]) . ' ' . Common::numberToMonth($this->_month, $lang). ' ' . Translate::t($lang, 'not_found'). ' ' . Translate::t($lang, 'try_search_another'));
        }

    }

}