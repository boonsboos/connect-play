<?php

/**
 * Check if a variable exists (is set) and is not empty.
 */
function exists($var): bool
{
    return isset($var) && !empty($var);
}
