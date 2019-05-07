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
    const TBL_COMMON        = ['furlough', 'absentees', 'unpaid', 'medical', 'unpaidHours', 'hoursToRecover'];


    const TBL_COMMON_DISPLAY = ['furlough' => 'day', 'absentees' => 'day', 'unpaid' => 'day', 'medical' => 'day', 'unpaidHours' => 'hours', 'hoursToRecover' => 'hours'];

    /**
     * Common tables with prefix
     */
    const PREFIX_TBL_COMMON = ['cmd_furlough', 'cmd_absentees', 'cmd_unpaid', 'cmd_medical', 'cmd_unpaidHours', 'cmd_hoursToRecover'];


    const ASSOC_PREFIX_TBL = ['furlough' => 'cmd_furlough', 'absentees' => 'cmd_absentees', 'unpaid' => 'cmd_unpaid', 'medical' => 'cmd_medical', 'unpaidHours' => 'cmd_unpaidHours', 'hoursToRecover' => 'cmd_hoursToRecover'];

    /**
     * Tables prefix
     */
    const PREFIX            = 'cmd_';

    /**
     * Languages
     */
    const LANG              = [1 => 'en', 2 => 'it', 3 => 'ro'];

    /**
     * Default Language
     */
    const DEFAULTLANG       = 1;

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


    const EMPLOYEESTBL      = 'employees';

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
     * Medical leave table
     */
    const TBL_MEDICAL       = 'cmd_medical';


    /**
     * Events table
     */
    const TBL_EVENTS            = 'cmd_events';

    const TBL_UNPAIDHOURS       = 'cmd_unpaidHours';

    const TBL_HOURSTORECOVER    =  'cmd_hoursToRecover';

    /**
     * Notification table
     */
    const TBL_NOTIFICATION      = 'cmd_notification';


    /**
     * Status table
     */
    const TBL_STATS = 'cmd_status';

    const TBL_CITY = 'cmd_city';


    /**
     * Allowed files extensions
     */
    const EXTENSIONS    = ['csv'];

    /**
     * Min characters allowed
     */
    const MIN_INPUT         = 2;

    /**
     * Max characters allowed
     */
    const MAX_INPUT         = 50;


    /**
     * Dash text and bg
     */
    const DASH              = [
                                'text' => ['dashtext-1', 'dashtext-2', 'dashtext-3', 'dashtext-4'],
                                'bg'  => ['dashbg-1', 'dashbg-2', 'dashbg-3', 'dashbg-4', 'dashbg-5']
                               ];

    const COMMONTBLSDASHTEXT = [
        'furlough'          => 'dashtext-4',
        'absentees'         => 'dashtext-3',
        'medical'           => 'dashtext-1',
        'unpaid'            => 'dashtext-2',
        'unpaidHours'       => 'dashtext-3',
        'hoursToRecover'    => 'dashtext-3'
    ];

    /**
     * How to display data
     */
    const DATADISPLAY       = ['number', 'percentage'];


    /**
     * Event statuses
     */
    const EVENTS_STATUS         = ['1' => 'Accepted', '2' => 'Pending', '3' => 'Denied', '4' => 'Deleted'];


    /**
     * Events status color
     */
    const EVENTS_STATUS_COLORS  = ['1' => 'success', '2' => 'secondary', '3' => 'danger', '4' => 'danger'];


    /**
     * Events color for calendar
     */
    const EVENTS_COLORS     = ['1' => '#28a745', '2' => '#8a8d93', '3' => '#bb414d'];


    /**
     * Events title colors for calendar
     */
    const EVENTS_TITLE_COLORS = [
        'Furlough' => ['1' => '#864dd9', '2' => '#8a8d93', '3' => '#bb414d'],
            'Absentees' => ['1' => '#bb414d', '2' => '#bb414d', '3' => '#bb414d'],
                'Unpaid' => ['1' => '#e58080', '2' => '#8a8d93'],
                    'Medical' => ['1' => '#cf53f9', '2' => '#8a8d93'],
    ];


    /**
     * Insert type to DB form file or from calendar
     */
    const INSERT_TYPE       = ['file' => 0, 'calendar' => 1];


    const CHART_COLORS_VIOLET   = ['#391d61', '#442275', '#4f2888', '#5b2e9c', '#6634af', '#723ac3',
                                        '#804dc9', '#8e61cf', '#9c75d5', '#aa88db', '#b89ce1', '#c6b0e7'];

    const CHART_COLORS_RED      = ['#8B323E', '#AE3E4D', '#DA4D60', '#E17180', '#E78D99', '#ECA4AD',
                                        '#F0B6BD', '#F3C5CA', '#F5D1D5', '#F7DADD', '#F9E1E4', '#FAE7E9'];

}