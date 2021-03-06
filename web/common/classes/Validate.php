<?php

/**
 * Class Validate
 */
class Validate
{
    /**
     * @var bool
     */
    private $_passed = false;

    /**
     * @var array
     */
    private $_errors = [];

    /**
     * @var BackendDB|null
     */
    private $_db = null;


    /**
     * @var mixed
     */
    private $_lang;

    /**
     * @var array
     */
    private $_ruleColumn = ['table', 'id'];


    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->_db = CommonDB::getInstance();
        $this->_lang = Session::exists('lang') ? Session::get('lang') : 'en';
    }


    /**
     * @param $source
     * @param array $items
     * @param bool $hasErrors
     * @return $this
     */
    public function check($source, $items = [], $hasErrors = false)
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value      = trim($source[$item]); // Input POST
                $item       = escape($item);

                if (strpos($item, '_') > 0) {
                    $needlePosition = strpos($item, '_');
                    $firstItem      = substr($item, 0, $needlePosition);
                    $secondItem     = substr($item, $needlePosition + 1);
                    $errorItem      = ucfirst($firstItem) . ' ' . strtolower($secondItem);
                    $errorItem      = escape($errorItem);
                } else {
                    $errorItem = ucfirst($item);
                    $errorItem = escape($errorItem);
                }

                if ($rule === 'required' && empty($value)) {
                    Errors::setErrorType('danger', Translate::t('all_required', ['ucfirst' => true]));
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (trim(strlen($value)) < $rule_value) {
                                Errors::setErrorType('danger', sprintf("%s %s %d %s!", $errorItem, Translate::t('validation_minimum'), $rule_value, Translate::t('characters')));
                            }
                            break;
                        case 'max':
                            if (trim(strlen($value)) > $rule_value) {
                                Errors::setErrorType('danger', sprintf("%s %s %d %s!", $errorItem, Translate::t('validation_max'), $rule_value, Translate::t('characters')));
                            }
                            break;
                        case 'matches':
                            if ($value !== $source[$rule_value]) {
                                if ($hasErrors) {
                                    Session::put($item, 'has-error');
                                }
                                Errors::setErrorType('danger', Translate::t('pass_no_match'));
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, [$item, '=', $value], [$item]);
                            if ($check->count()) {
                                Errors::setErrorType('danger', sprintf("%s: %s %s", $errorItem, $value, Translate::t('already_exists')));
                            }
                            break;
                        case 'matches_db':
                            foreach ($rule_value as $key => $rule) {
                                if (!in_array($key, $this->_ruleColumn)) {
                                    Errors::setErrorType('danger', sprintf("%s", Translate::t('rules_not_allowed' )));
                                } else {
                                    $getP = $this->_db->get($rule_value['table'], ['id', $rule_value['id']], [$item])->first()->$item;
                                    if (!password_verify($value, $getP)) {
                                        Errors::setErrorType('danger', sprintf("%s", Translate::t('wrong_password')));
                                    }
                                }
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                Errors::setErrorType('danger', 'You need to insert a valid email!');
                            }
                            break;
                        case 'letters':
                            if (is_numeric($value)) {
                                Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t($this->_lang, 'only_letters')));
                            }
                            break;
                        case 'characters':
                            if (is_numeric($value) && ctype_alpha($value)) {
                                Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t($this->_lang, 'only_char')));
                            }
                            break;
                        case 'extension':
                            $path = $_FILES['fileToUpload']['name'];
                            $extension  = pathinfo($path, PATHINFO_EXTENSION);
                            if (in_array($extension, $rule_value)) {
                                Errors::setErrorType('danger', Translate::t('Csv_extension'));
                            }
                            break;
                        case 'fileError':
                            $path = $_FILES["fileToUpload"]["error"];
                            if ($path > $rule_value) {
                                Errors::setErrorType('danger', $_FILES["fileToUpload"]["error"]);
                            }
                            break;
                        case 'fileRequired':
                            if ($rule_value && !is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
                                Errors::setErrorType('danger', Translate::t('file_required'));
                            }
                            break;
                        case 'equals':
                            $check    = Common::checkLastCharacter($value);
                            $matched  = Common::checkLastCharacter($source[$rule_value]);
                            $check    = explode(',', $check);
                            $matched  = explode(',', $matched);
                            if (count($check) !== count($matched)) {
                                if ($hasErrors) {
                                    Session::put($item, 'has-error');
                                    Errors::setErrorType('danger', sprintf("You must have same number of %s as tables", strtolower($errorItem)));
                                } else {
                                    Errors::setErrorType('danger', sprintf("You must have same number of %s as tables", strtolower($errorItem)));
                                }
                            }
                            break;
                        case 'numbers':
                            $pos = strpos($value, ',');
                            if ($pos > 0) {
                                $values = explode(',', $value);
                                foreach ($values as $v) {
                                    if (!is_numeric($v) && $hasErrors) {
                                        Session::put($item, 'has-error');
                                        Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t('only_numbers')));
                                    } elseif (!is_numeric($v)) {
                                        Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t('only_numbers')));
                                    }
                                }
                            } elseif (!is_numeric($value) && $hasErrors) {
                                Session::put($item, 'has-error');
                                Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t('only_numbers')));
                            } elseif (!is_numeric($value)) {
                                Errors::setErrorType('danger', sprintf("%s %s", $errorItem, Translate::t('only_numbers')));
                            }
                            break;
                        case 'dataType':
                            $pos = strpos($value, ',');
                            if ($pos > 0) {
                                $values = explode(',', strtolower($value));
                                foreach ($values as $v) {
                                    if (!in_array($v, Params::DATADISPLAY) && $hasErrors) {
                                        Session::put($item, 'has-error');
                                        Errors::setErrorType('danger', sprintf("Data allowed to insert in field %s are: NUMBER OR PERCENTAGE", $errorItem));
                                    } elseif (!in_array($v, Params::DATADISPLAY)) {
                                        Errors::setErrorType('danger', sprintf("Data allowed to insert in field %s are: NUMBER OR PERCENTAGE", $errorItem));
                                    }
                                }
                            }
                            break;
                        case 'blanks':
                            if (strpos($value, ' ') > 0 && $hasErrors) {
                                Session::put($item, 'has-error');
                                Errors::setErrorType('danger', sprintf("%s doesn't need contain blank spaces", $errorItem));
                            } elseif (strpos($value, ' ')) {
                                Errors::setErrorType('danger', sprintf("%s doesn't need contain blank spaces", $errorItem));
                            }
                        }
                }
            }
        }

        if (!Errors::countAllErrors()) {
            $this->_passed = true;
        }

        return $this;
    }



    /***
     * @return array
     */
    public function errors()
    {
        return array_unique($this->_errors);
    }

    /***
     * @return bool
     */
    public function countErrors()
    {
        if (count($this->errors()) > 0) {
            return true;
        } elseif (count($this->errors()) === 0) {
            return false;
        }

    }

    /**
     * @return bool
     */
    public function passed()
    {
        return $this->_passed;
    }
}