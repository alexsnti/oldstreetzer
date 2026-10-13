<?php
// ponytail: mysql_* removed in PHP 7. Maps the calls this app actually uses onto mysqli.
// Ceiling: one global link, no multi-result queries. Upgrade path: mysqli in the callers.
if (!function_exists('mysql_connect')) {
	mysqli_report(MYSQLI_REPORT_OFF);
	define('MYSQL_ASSOC', MYSQLI_ASSOC);
	define('MYSQL_NUM', MYSQLI_NUM);
	define('MYSQL_BOTH', MYSQLI_BOTH);

	$GLOBALS['__mysql_link'] = null;

	function mysql_connect($host = null, $user = null, $pass = null, $new_link = false, $flags = 0) {
		$port = 3306;
		if (is_string($host) && strpos($host, ':') !== false) {
			list($host, $port) = explode(':', $host, 2);
		}
		$link = @mysqli_connect($host, $user, $pass, '', (int) $port);
		if ($link) {
			$GLOBALS['__mysql_link'] = $link;
		}
		return $link;
	}

	function mysql_select_db($db, $link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_select_db($link, $db) : false;
	}

	function mysql_selectdb($db, $link = null) {
		return mysql_select_db($db, $link);
	}

	function mysql_query($query, $link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_query($link, $query) : false;
	}

	function mysql_error($link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_error($link) : 'no connection';
	}

	function mysql_errno($link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_errno($link) : 0;
	}

	function mysql_fetch_array($result, $type = MYSQLI_BOTH) {
		if (!$result) return false;
		return mysqli_fetch_array($result, $type);
	}

	function mysql_fetch_assoc($result) {
		if (!$result) return false;
		return mysqli_fetch_assoc($result);
	}

	function mysql_num_rows($result) {
		if (!$result) return 0;
		return mysqli_num_rows($result);
	}

	function mysql_real_escape_string($string, $link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		$string = (string) $string;
		return $link ? mysqli_real_escape_string($link, $string) : addslashes($string);
	}

	function mysql_insert_id($link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_insert_id($link) : 0;
	}

	function mysql_close($link = null) {
		$link = $link ?: $GLOBALS['__mysql_link'];
		return $link ? mysqli_close($link) : false;
	}
}
