<?php
//From Dr. Alan Hunt
//function for basic field validation.
function IsNullOrEmpty($question)
{
    return (!isset($question) || trim($question)==='');
}

//Function to validate the date. Checks that $date is between $startddate and $enddate
function isDateBetweenDates(DateTime $date, DateTime $startDate, DateTime $endDate)
{
    return $date > $startDate && $date < $endDate;
}

//Function to validate that a .json object is valid.
function isValidJSON($str)
{
    json_decode($str);
    return json_last_error() == JSON_ERROR_NONE;
}

//Function that will return the string if it is not null.
function formatNull($str)
{
    if (IsNullOrEmpty($str)) {
        return null;
    }
    return $str;
}

?>