<?php

namespace App\Helpers;

class DateHelper
{

    /**
     * To get the present date
     * @return type
     */
    public static function todayDate()
    {

        //  return "2018-05-01";
        return date('Y-m-d');
    }

    /**
     * To return the current date & time
     * @return type
     */
    public static function todayDateTime()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * To convert the string to date format
     * @param type $value
     */
    public static function convertToDate($value)
    {
        return date('Y-m-d', strtotime($value));
    }
    
    /**
     * To convert the string to date format
     * @param type $value
     */
    public static function convertToDateTime($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }
    
    /**
     * To convert the string to date format
     * @param type $value
     */
    public static function uiDateTime($value)
    {
        return date('m/d/y H:i', strtotime($value));
    }
    
    /**
     * To convert the string to date format
     * @param type $value
     */
    public static function uiDate($value)
    {
        return date('m/d/y', strtotime($value));
    }

}
