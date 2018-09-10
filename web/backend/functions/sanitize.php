<?php
/**
 * @param $content
 * @param bool $doubleEncode
 * @return string
 */
function escape($content, $doubleEncode = true) {
    return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
}

/**
 * @param $content
 * @return string
 */
function decode($content) {
    return htmlspecialchars_decode($content, ENT_QUOTES);
}