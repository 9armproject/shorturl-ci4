<?php
if (!function_exists("date_compare")) {
    function date_compare($a, $b)
    {
        return strtotime($a["date"]) - strtotime($b["date"]);
    }
}
