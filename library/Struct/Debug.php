<?php


class Struct_Debug
{
	
	static private $mode = 'html';
	
	static public function mode($mode = 'html')
	{
		self::$mode = $mode;
	}
	
	static public function dieWith($output)
	{
		if ('html' == self::$mode) {
			echo '<pre>';
			var_dump($output);
			echo '</pre>';
		} else {
			error_log(var_dump($output));
		}
		exit();
	}
	
	static public function dieIf($evaluation, $output)
	{
		if ($evaluation) {
			self::dieWith($output);
		}
	}
	
	static public function log($label, $output)
	{
		if ('html' == self::$mode) {
			echo "$label:<br />";
			echo '<pre>';
			var_dump($output);
			echo '</pre>';
		} else {
			error_log("$label:");
			error_log(print_r($output, true));
		}
	}
	
	static public function errorLog($label, $output)
	{
		error_log("$label: ".print_r($output, true));
	}
	
	static public function logBacktrace()
	{
		error_log(print_r(debug_backtrace(), true));
	}
	
	static public function arrayToTable($array, $title = null)
	{
		$html = '
		<table border="1">';
		if (!empty($title)) {
			$html .= '
			<thead>
			<tr>
			<th colspan="2">' . htmlentities($title) . '</th>
			</tr>
			</thead>';
		}
		$html .= '
		<tbody>';
		foreach ($array as $key => $val) {
			$type = '';
			if (is_object($val)) {
				$type = "\n" . '(Class: ' . get_class($val) . ')';
				$val = $val->toArray();
			}
			$html .= '
			<tr>';
			if (is_array($val)) {
				$val = self::arrayToTable($val, $type);
			} else {
				$val = htmlentities($val);
			}
			$html .= "
			<td><b>" . htmlentities($key) . ":</b></td>
			<td>" . $val . " </td>";
			$html .= '
			</tr>';
		}
	
		$html .= '
		</tbody>
		</table>';
		return $html;
	}
	
}