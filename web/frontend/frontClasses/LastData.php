<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 10/29/18
 * Time: 7:22 PM
 */

class LastData extends FrontendProfile
{
    public function getData()
    {
        if (Input::existsName('get', 'lastdata')) {
            $data = Input::get('lastdata');
        }
        return $data;
    }


    public function getRecords()
    {

    }
}