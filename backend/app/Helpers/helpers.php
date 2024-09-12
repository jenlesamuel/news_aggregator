<?php

if (!function_exists('formatDate')) {
    /**
     * Formats a given date into 'Y-m-d H:i:s' format.
     * Returns null if the input date is an empty string.
     *
     * @param  string|DateTime|null  $date
     * @return string|null
     */
    function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}