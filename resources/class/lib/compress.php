<?php

Class Compress{

function compressor($css){


/**
 * Ideally, you wouldn't need to change any code beyond this point.
 */


$buffer = file_get_contents($css);
// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
 
// Remove space after colons
$buffer = str_replace(': ', ':', $buffer);
 
// Remove whitespace
$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
 
// Enable GZip encoding.
ob_start("ob_gzhandler");
 
// Enable caching
header('Cache-Control: public');
 
// Expire in one day
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
 
// Write everything out
echo file_put_contents('../css/style.css', $buffer);

}

}



?>