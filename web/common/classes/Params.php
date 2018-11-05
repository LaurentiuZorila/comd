<?php
/**
 * Created by PhpStorm.
 * User: onetwist
 * Date: 9/25/18
 * Time: 12:19 PM
 */

class Params
{
    /**
     * Allowed columns
     */
    const ALLOWED_COLUMNS   = ['id', 'name', 'offices_id', 'departments_id', 'supervisors_id', 'tables', 'rating', 'sum', 'average'];

    /**
     * Common tables
     */
    const TBL_COMMON        = ['furlough', 'absentees', 'unpaid'];

    /**
     * Common tables with prefix
     */
    const PREFIX_TBL_COMMON = ['cmd_furlough', 'cmd_absentees', 'cmd_unpaid'];

    /**
     * Tables prefix
     */
    const PREFIX            = 'cmd_';

    /**
     * Languages
     */
    const LANG              = [1 => 'en', 2 => 'it', 3 => 'ro'];

    /**
     * Changes table
     */
    const TBL_CHANGES       = 'cmd_changes';

    /**
     * Offices table
     */
    const TBL_OFFICE        = 'cmd_offices';

    /**
     * Department table
     */
    const TBL_DEPARTMENT    = 'cmd_departments';

    /**
     * Supervisors table
     */
    const TBL_SUPERVISORS   = 'cmd_supervisors';

    /**
     * Lead table
     */
    const TBL_TEAM_LEAD     = 'cmd_users';

    /**
     * Employees table
     */
    const TBL_EMPLOYEES     = 'cmd_employees';

    /**
     * Rating table
     */
    const TBL_RATING        = 'cmd_rating';

    /**
     * Furlough table
     */
    const TBL_FURLOUGH      = 'cmd_furlough';

    /**
     * Absentees table
     */
    const TBL_ABSENTEES     = 'cmd_absentees';

    /**
     * Unpaid table
     */
    const TBL_UNPAID        = 'cmd_unpaid';

    /**
     * Allowed files extensions
     */
    const EXTENSIONS        = ['csv'];

    /**
     * Min characters allowed
     */
    const MIN_INPUT         = 2;

    /**
     * Max characters allowed
     */
    const MAX_INPUT         = 50;

    const DASH              = [
                                'text' => ['dashtext-1', 'dashtext-2', 'dashtext-3', 'dashtext-4'],
                                'bg'  => ['dashbg-1', 'dashbg-2', 'dashbg-3', 'dashbg-4', 'dashbg-5']
                               ];

    const DATADISPLAY       = ['number', 'percentage'];

}