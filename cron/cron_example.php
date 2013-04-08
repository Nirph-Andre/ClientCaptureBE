<?php

/**
 * Purpose: Cleanup crappy data.
 */

#-> Bootstrap.
require_once(realpath(dirname(__FILE__) . '/bootstrap.php'));

#-> Do stuff.
$data = file_get_contents(APPLICATION_PATH . '\..\data\20121221.csv');
$data = explode("\r", $data);
foreach ($data as $id => $line)
{
	if ($id == 0)
	{
		continue;
	}
	$data[$id] = str_replace('""', '"', substr($line, 1, -2));
}

file_put_contents(APPLICATION_PATH . '\..\data\20121221.clean.csv', implode("\r", $data));