<?php
function escapeString($text)
{
    global $connect;
    return $connect->real_escape_string($text);
}
