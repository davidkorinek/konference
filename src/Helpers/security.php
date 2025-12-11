<?php
// src/Helpers/security.php

if (!function_exists('e')) {
    /**
     * Safe html escape helper
     * @param mixed $value
     * @return string
     */
    function e($value): string
    {
        if ($value === null) return '';
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
