<?php
// funce e() escapuje HTML - brani proti XSS utokum
if (!function_exists('e')) {
    function e($value): string
    {
        if ($value === null) return '';
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
