<?php
function sanitize_input($s)
{
        $s = strip_tags($s);
        $s = addslashes($s);
        return $s;
}
?>
