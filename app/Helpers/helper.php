<?php

use Carbon\Carbon;


function calculateHoursBetweenDates($to,$from){
    $toDateTime = Carbon::parse($to);
    $fromDateTime = Carbon::parse($from);
    return (!empty($to) && !empty($from))?$toDateTime->diffInHours($fromDateTime) : "0";
}


function calculateMinutesBetweenDates($to,$from){
    $toDateTime = Carbon::parse($to);
    $fromDateTime = Carbon::parse($from);
    return (!empty($to) && !empty($from))?$toDateTime->diffInMinutes($fromDateTime) : "0";
}
