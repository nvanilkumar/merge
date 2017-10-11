<?php

namespace App\Helpers;

class CustomHelper
{
    /**
     * Comapers two arrays return unquie user ids as a array
     * @param type $value
     * @return type
     */
    public static function removeDuplicateRecords($usersArray, $assignArray)
    {
        $newUsersList = [];
        //compare two arrays keep record in new array 
        //which exist in two arrays
        if (count($assignArray) > 0) {
            foreach ($assignArray as $assign) {
                if (in_array($assign->user_id, $usersArray)) {
                    array_push($newUsersList, $assign->user_id);
                }
            }
        }
        $mainUsersList = [];
        $mainUsersList = array_diff($usersArray, $newUsersList);
        return $mainUsersList;
    }

}
