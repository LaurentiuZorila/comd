<?php
interface tablesTemplate
{
    public function getTables($id, $all);
    public function where($office_id);
}