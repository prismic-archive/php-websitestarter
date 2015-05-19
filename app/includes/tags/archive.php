<?php

function is_day()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['date'])) {
        $date = $WPGLOBAL['date'];

        return $date['day'] != null;
    }

    return false;
}

function is_month()
{
    global $WPGLOBAL;
    if (isset($WPGLOBAL['date'])) {
        $date = $WPGLOBAL['date'];

        return $date['month'] != null && !is_day();
    }

    return false;
}

function is_year()
{
    return !is_day() && !is_month();
}

function archive_date()
{
    global $WPGLOBAL;
    if (!isset($WPGLOBAL['date'])) {
        return;
    }
    $date = $WPGLOBAL['date'];
    $year = $date['year'];
    $month = $date['month'];
    $day = $date['day'];
    if ($day != null) {
        $dt = DateTime::createFromFormat('!Y-m-d', $year.'-'.$month.'-'.$day);

        return $dt->format('F jS, Y');
    } elseif ($month != null) {
        $dt = DateTime::createFromFormat('!Y-m', $year.'-'.$month);

        return $dt->format('F Y');
    } else {
        return $year;
    }
}

function archive_link($year, $month = null, $day = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];

    return $prismic->archive_link($year, $month, $day);
}
