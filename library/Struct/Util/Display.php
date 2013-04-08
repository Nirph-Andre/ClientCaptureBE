<?php


class Struct_Util_Display
{
	
	public function timeRemaining($until)
	{
		$timeDiff = is_numeric($until)
			? $until - time()
			: strtotime($until) - time();
		$days = floor($timeDiff / (60 * 60 * 24));
		$remainder = $timeDiff % (60 * 60 * 24);
		$hours = floor($remainder / (60 * 60));
		$remainder = $remainder % (60 * 60);
		$minutes = floor($remainder / 60);
		//$seconds = $remainder % 60;
		return $days > 0
			? $days . 'd, ' . $hours . 'h, ' . $minutes . 'm'
			: $hours . 'h, ' . $minutes . 'm';
	}
	
	public function buildRating($rating, $max = 5, $extra = '')
	{
		$html = '';
		for ($i = 1; $i <= $max; $i++) {
			$currExtra = str_replace('[rating]', $i, $extra);
			$html .= ($i <= $rating)
				? '<i class="icon-star"' . $currExtra . '></i>'
				: '<i class="icon-star-empty"' . $currExtra . '></i>';
		}
		return $html;
	}
	
	public function builDataTags(array $activeFlags, $container, $script)
	{
		$flags = Struct_Registry::getContext('dataContext');
		if(empty($flags))
		{
			return '';
		}
		$active = '';
		$inactive = '';
		$toCamel = new Zend_Filter_Word_UnderscoreToCamelCase();
		$toDash = new Zend_Filter_Word_CamelCaseToDash();
		foreach ($flags as $flag => $data)
		{
			(strpos($flag, ':'))
				? list(, $flagName) = explode(':', $flag)
				: $flagName = $flag;
			$flagName = $toDash->filter($toCamel->filter(
					str_replace(array('__', 'lu_', '_id'), array('_', '', ''), $flag)
					));
			if (isset($activeFlags[$flag]))
			{
				$active .= '<li class="handy" rel="tooltip" title="'.$data['label'].'" '
						.  'onClick="flagItem(\''.$flag.'\', '.$data['value'].', \''.$data['label'].'\', \''.$container.'\', \''.$script.'\')">'
						.  '<span><i class="icon-flag"></i> <b>' . $flagName . '</b> '
						.  '<i class="icon-remove"></i></span></li>' . "\n";
			}
			else
			{
				$inactive .= '<li class="handy" rel="tooltip" title="'.$data['label'].'" '
						.  'onClick="flagItem(\''.$flag.'\', '.$data['value'].', \''.$data['label'].'\', \''.$container.'\', \''.$script.'\')">'
						.  '<span>' . $flagName . ' '
						.  '<i class="icon-remove"></i></span></li>' . "\n";
			}
		}
		$active = empty($active)
			? ''// 'No active data flags for this dataset.'
			: '<ul class="tags">
									'.$active.'
								</ul>';
		$inactive = empty($inactive)
			? ''// 'No inactive data flags.'
			: '<ul class="tags">
									'.$inactive.'
								</ul>';
		return '
		<div class="row-fluid">
			<div id="dataTags" class="span12">
								'.$active.'
								'.$inactive.'
			</div>
		</div>';
	}
	
	public function buildPager($container, $script, $currPage, $lastPage, $numRecords)
	{
		$buffer = 5; #->>> Change this value of you want smaller/larger pagination set
		$maxShown = ($buffer * 2);
		$currPage = $currPage
			? $currPage
			: 1;
		$lastPage = $lastPage
			? $lastPage
			: 1;
		$minPage = ($currPage > $buffer)
			? $currPage - $buffer
			: 1;
		$maxPage = $lastPage > $minPage + $maxShown
			? $minPage + $maxShown
			: $lastPage;
		$minPage = ($minPage > 1 && $maxPage-$minPage < $maxShown)
			? $maxPage - $maxShown
			: $minPage;
		$minPage = ($minPage < 1)
			? 1
			: $minPage;
		$html = '
	        <ul>';
		$disabled = (1 == $currPage);
		// records per page
		$numRecOpts = array(5, 10, 15, 20, 25, 50);
		$numRecs = '
				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						Showing ' . $numRecords . ' per page <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">';
		foreach ($numRecOpts as $num)
		{
			if ($num != $numRecords)
			{
				$numRecs .= '
		          <li><a class="handy" onClick="pagerRecords(\''.$container.'\', \''.$script.'\', '.$num.');">Show <b>'.$num.'</b> records</a></li>';
			}
		}
		$numRecs .= '
					</ul>
				</div>';
		
		// first page
		if (1 == $currPage) {
			$html .= '
	          <a class="active""><b>&laquo;</b></a>';
		} else {
			$html .= '
	          <a class="handy" onClick="pagerPage(\''.$container.'\', \''.$script.'\', 1);"><b>&laquo;</b></a>';
		}
		for ($i = $minPage; $i <= $maxPage; $i++) {
			if ($i == $currPage) {
				$html .= '
		          <a class="active">'.$i.'</a>';
			} else {
				$html .= '
		          <a class="handy" onClick="pagerPage(\''.$container.'\', \''.$script.'\', '.$i.');">'.$i.'</a>';
			}
		}
		// last page
		if ($lastPage == $currPage) {
			$html .= '
	          <a class="active"><b>&raquo;</b></a>';
		} else {
			$html .= '
	          <a class="handy" onClick="pagerPage(\''.$container.'\', \''.$script.'\', '.$lastPage.');"><b>&raquo;</b></a>';
		}
		$html .= '
	        </ul>';
		return '<div class="row-fluid">
			<div class="span10 pagination">
			<center>' . $html . '</center>
			</div>
			<div class="span2 pagination-records">
				' . $numRecs . '
			</div>
		</div>';
	}
	
	public function buildDropdownOptions($input, $selected)
	{
		if (!is_array($input))
		{
			return '';
		}
		$html = '';
		foreach ($input as $value => $label) {
			if (is_array($label))
			{
				$value = $label['id'];
				$label = $label['name'];
			}
			$select = ($value == $selected)
				? 'selected'
				: '';
			$label = htmlentities($label);
			$html .= "
						<option value='$value' $select>$label</option>";
		}
		return $html;
	}
	
	public function ageFromDate($date)
	{
		if (empty($date)) {
			return 'Unknown';
		}
		list($year, $month, $day) = explode('-', $date);
		if (!function_exists('date_diff')) {
			return date('Y') - $year;
		}
		$interval = date_diff(new DateTime($date), new DateTime(date('Y-m-d')));
		return $interval->format('%y');
	}
	
	public function buildGroupedDropdownOptions($input, $selected)
	{
		$html = '';
		foreach ($input as $group => $data) {
			$label = htmlentities($group);
			$html .= "
						<optgroup label='$label'>";
			$html .= $this->buildDropdownOptions($data, $selected);
			$html .= "
						</optgroup>";
		}
		return $html;
	}
	
	public function fancyName($name, $surname = false)
	{
		if (!$name) {
			$name = 'Name';
			$surname = 'Not Provided';
		} elseif (!$surname) {
			list($name, $surname) = explode(' ', $name, 2);
		}
		$first = htmlentities($name);
		$rest = htmlentities($surname);
		return "<strong><span class='smaller'>$first</span><br />"
			   . "<span class='large'>$rest</span></strong>";
	}
	
	public function packOutData($data)
	{
		if (is_object($data)) {
			$data = $data->toArray();
		}
		$html = '&nbsp;<br />';
		foreach ($data as $key => $val) {
			$type = '';
			if (is_object($key)) {
				continue;
			}
			if (is_object($val)) {
				$type = "\n" . '(Class: ' . get_class($val) . ')';
				$val = $val->toArray();
			}
			if (is_array($val)) {
				$html .= $this->arrayToTable($val, $key . $type);
			} else {
				$html .= "<strong>$key => $val</strong><br />&nbsp;<br />";
			}
		}
		return $html;
	}
	
	public function arrayToTable($array, $title = null)
	{
		$html = '
		      <table class="table table-striped table-bordered">';
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
				$val = $this->arrayToTable($val, $type);
			} else {
				$val = htmlentities($val);
			}
			$html .= "
	            <td><strong>" . htmlentities($key) . ":</strong></td>
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