<?php

class Struct_Grid_Twitter
{
	
	
	
	static public function buildIndex($viewTheme, $listAction, $tableName, $fields,
			Struct_ActionFeedback $result,
			Struct_Form $form = null)
	{
		$grid = self::buildGrid($viewTheme, $listAction, $tableName, $fields, $result);
		$params = self::getBaseParams($viewTheme, $listAction, $fields, $form);
		$params['Search']['[grid]'] = '[grid]';
		$params['Replace']['[grid]'] = $grid['Html'] . $grid['Script'];
		$html = str_replace($params['Search'], $params['Replace'], self::$codeIndexHtml);
		$js = str_replace($params['Search'], $params['Replace'], self::$codeIndexJs);
		return array(
				'Html' => $html,
				'Script' => $js
				);
	}
	
	static public function buildGrid($viewTheme, $listAction, $tableName, $fields,
			Struct_ActionFeedback $result,
			Struct_Form $form = null)
	{
		#-> Base params.
		$params = self::getBaseParams($viewTheme, $listAction, $fields, $form);
		$html = '';
		
		#-> Header
		$utilDisplay = Struct_Registry::get("Util.Display");
		$html .= $utilDisplay->builDataTags($result->result["DataFlags"], $params['Replace']['[ItemName]'] . "Grid", $listAction);
		$html .= $utilDisplay->buildPager(
				$params['Replace']['[ItemName]'] . "Grid",
				$listAction,
				$result->result["Paging"]["CurrentPage"],
				$result->result["Paging"]["TotalPages"],
				$result->result["Paging"]["RecordsPerPage"]
				);
		list($order, $direction) = each($result->result["Order"]);
		$html .= str_replace($params['Search'], $params['Replace'], self::$codeGridHeader);
		$scoreToCamel = new Zend_Filter_Word_UnderscoreToCamelCase();
		$camelToSep = new Zend_Filter_Word_CamelCaseToSeparator();
		
		#-> Column Headers.
		$i = 0;
		foreach ($fields as $field => $meta)
		{
			$i++;
			$fldParams = self::getFieldParams($i, $field, $meta, $params);
			$fldParams['Search']['[arrow-type-asc]'] = '[arrow-type-asc]';
			$fldParams['Replace']['[arrow-type-asc]'] = $field == $order && "ASC" == $direction 
				? ""
				: "circle-";
			$fldParams['Search']['[arrow-type-desc]'] = '[arrow-type-desc]';
			$fldParams['Replace']['[arrow-type-desc]'] = $field == $order && "DESC" == $direction 
				? ""
				: "circle-";
			$html .= str_replace($fldParams['Search'], $fldParams['Replace'], self::$codeGridColumnHeader);
		}
		
		#-> Search functionality.
		$html .= str_replace($params['Search'], $params['Replace'], self::$codeGridSearchPrep);
		$i = 0;
		$firstField  = null;
		$secondField = null;
		foreach ($fields as $field => $meta)
		{
			// [search.value]
			$i++;
			is_null($secondField)
				&& !is_null($firstField)
				&& strpos($tableName, '__')
				&& $secondField = $field;
			is_null($firstField)
				&& $firstField = $field;
			$value = isset($result->result['Search'][$meta['SearchField']]) 
				? $result->result['Search'][$meta['SearchField']]
				: "";
			$fldParams = self::getFieldParams($i, $field, $meta, $params, $value);
			$html .= str_replace($fldParams['Search'], $fldParams['Replace'], self::$codeGridColumnSearch);
		}
		
		#-> Data.
		$idFilterField = $tableName . '_id';
		$dataContext = Struct_Registry::getContext('dataContext');
		$html .= str_replace($params['Search'], $params['Replace'], self::$codeGridRowPrep);
		if (empty($result->data))
		{
			$html .= str_replace($params['Search'], $params['Replace'], self::$codeGridNoData);
		}
		else 
		{
			foreach ($result->data as $record)
			{
				$flagged = isset($dataContext[$idFilterField])
							&& $record['id'] == $dataContext[$idFilterField]['value'];
				$fieldParams = $params;
				$fieldParams['Search']['[record_id]'] = '[record_id]';
				$fieldParams['Replace']['[record_id]'] = $record['id'];
				$fieldParams['Search']['[field_name]'] = '[field_name]';
				$fieldParams['Replace']['[field_name]'] = $idFilterField;
				$fieldParams['Search']['[icon]'] = '[icon]';
				$fieldParams['Replace']['[icon]'] = $flagged ? 'flag' : 'tag';
				$fieldParams['Search']['[icon-fade]'] = '[icon-fade]';
				$fieldParams['Replace']['[icon-fade]'] = $flagged ? '' : 'icon-fade';
				$fieldParams['Search']['[data_label]'] = '[data_label]';
				$fieldParams['Replace']['[data_label]'] = $record[$firstField];
				if (!is_null($secondField))
				{
					$fieldParams['Replace']['[data_label]'] .= ' - ' . $record[$secondField];
				}
				$html .= str_replace($fieldParams['Search'], $fieldParams['Replace'], self::$codeGridRowHeader);
				$i = 0;
				foreach ($fields as $field => $meta)
				{
					$i++;
					$fldParams = self::getFieldParams($i, $field, $meta, $fieldParams, $record[$field]);
					$html .= str_replace($fldParams['Search'], $fldParams['Replace'], self::$codeGridRowField);
				}
				$html .= self::$codeGridRowFooter;
			}
		}
		
		#-> Footer.
		$params['Search']['[json-data]'] = '[json-data]';
		$params['Replace']['[json-data]'] = Zend_Json::encode($result->data);
		$html .= str_replace($params['Search'], $params['Replace'], self::$codeGridFooter);
		$js = str_replace($params['Search'], $params['Replace'], self::$codeGridJs);
		
		#-> Fin.
		return array(
				'Html' => $html,
				'Script' => $js
				);
	}
	
	static private function getFieldParams($i, $field, $fieldMeta, $params, $value = null)
	{
		$class = '';
		switch ($i)
		{
			case 3:
			case 4:
				$class = 'hidden-phone';
				break;
			case 5:
				$class = 'visible-desktop';
				break;
		}
		$params['Search']['[field_name]'] = '[field_name]';
		$params['Replace']['[field_name]'] = $field;
		$params['Search']['[search.field]'] = '[search.field]';
		$params['Replace']['[search.field]'] = $fieldMeta['SearchField'];
		$params['Search']['[Field Label]'] = '[Field Label]';
		$params['Replace']['[Field Label]'] = str_replace('Lu ', '', $fieldMeta['Label']);
		$params['Search']['[class]'] = '[class]';
		$params['Replace']['[class]'] = $class;
		$params['Search']['[value]'] = '[value]';
		$params['Replace']['[value]'] = $value;
		$params['Search']['[search.value]'] = '[search.value]';
		$params['Replace']['[search.value]'] = $value;
		return $params;
	}
	
	static private function getBaseParams($viewTheme, $listAction, $fields, Struct_Form $form = null)
	{
		$dashToScore  = new Zend_Filter_Word_DashToUnderscore();
		$dashToCamel  = new Zend_Filter_Word_DashToCamelCase();
		$camelToSpace = new Zend_Filter_Word_CamelCaseToSeparator();
		list($module, $itemName) = explode('/', $viewTheme);
		
		$search  = array();
		$search['[ModalHtml]'] = '[ModalHtml]';
		$search['[ModalJs]'] = '[ModalJs]';
		$search['[theme]'] = '[theme]';
		$search['[list-action]'] = '[list-action]';
		$search['[item_name]'] = '[item_name]';
		$search['[item-name]'] = '[item-name]';
		$search['[ItemName]'] = '[ItemName]';
		$search['[itemName]'] = '[itemName]';
		$search['[Item Name]'] = '[Item Name]';
		$search['[columns]'] = '[columns]';
		
		$replace = array();
		$replace['[ModalHtml]'] = !is_null($form)
			? $form->buildModalHtml()
			: '';
		$replace['[ModalJs]'] = !is_null($form)
			? $form->buildModalJs()
			: '';
		$replace['[theme]'] = $viewTheme;
		$replace['[list-action]'] = $listAction;
		$replace['[item_name]'] = $dashToScore->filter($itemName);
		$replace['[item-name]'] = $itemName;
		$replace['[ItemName]'] = $dashToCamel->filter($itemName);
		$replace['[itemName]'] = lcfirst($dashToCamel->filter($itemName));
		$replace['[Item Name]'] = $camelToSpace->filter($dashToCamel->filter(
				str_replace('lu-', '', $itemName)
				));
		$replace['[columns]'] = count($fields);
		
		return array('Search' => $search, 'Replace' => $replace);
	}
	
	static private $codeIndexHtml = '
<div class="page-header">
	<h2>[Item Name] Manager</h2>
	<div id="[ItemName]Grid">
	<!-- [ItemName]: Grid -->
	[grid]
	<!-- End [ItemName]: Grid -->
	</div>
</div>

[ModalHtml]
';
	
	static private $codeIndexJs = '


<script type="text/javascript">

	var theme = "[theme]";

	$(document).ready(function() {
		$("#nav[ItemName]").addClass("active");
		searchHandler["srch-[itemName]"] = {
				action: "[list-action]",
				container: "[ItemName]Grid"
		};
        searchStack["srch-[itemName]"] = wrapFunction(search, this, ["srch-[itemName]"]);
	});
	
	[ModalJs]

</script>';
	
	
	
	
	static private $codeGridHeader = '
<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<td width="25px" class="hidden-phone">
						&nbsp;
						<i class="icon-plus handy" rel="tooltip" title="Add New Entry" onClick="add[ItemName]();"></i>
					</td>
					<td width="15px">
						<i class="icon-tags" rel="tooltip" title="Data Flags. Displayed data will be filtered by flagged data entries. Click on tag to flag an entry. Click on flag to unflag an entry."></i>
					</td>';
	static private $codeGridColumnHeader = '
					<td class="[class]">
						<i class="icon-[arrow-type-asc]arrow-down handy" rel="tooltip" title="Order Ascending" onClick="order(\'[itemName]\', \'[field_name]\', \'asc\');"></i>
						<i class="icon-[arrow-type-desc]arrow-up handy" rel="tooltip" title="Order Descending" onClick="order(\'[itemName]\', \'[field_name]\', \'desc\');"></i>
						&nbsp;<b>[Field Label]</b>
					</td>';
	static private $codeGridSearchPrep = '
				</tr>
				<tr class="hidden-phone">
					<td width="25px" class="hidden-phone">
						&nbsp;
						<i class="icon-search handy" rel="tooltip" title="Search" onClick="search(\'srch-[itemName]\');"></i>
					</td>
					<td width="15px">
						&nbsp;
					</td>';
	static private $codeGridColumnSearch = '
					<td class="[class]"><div class="row-fluid"><input 
						id="a" name="srch_[itemName]_[search.field]" type="text" class="span12 srch-[itemName]"
						value="[search.value]"></div></td>';
	static private $codeGridRowPrep = '
				</tr>
			</thead>
			<tbody>';
	static private $codeGridRowHeader = '
				<tr>
					<td class="hidden-phone">
						<div class="btn-group">
							<button class="btn dropdown-toggle btn-mini" data-toggle="dropdown">
								<i class="icon-cog" rel="tooltip" title="Edit / Delete Entry"></i>
							</button>
							<ul class="dropdown-menu">
								<li><a class="handy" onClick="edit[ItemName]([record_id]);"><i class="icon-edit"></i>&nbsp;&nbsp;&nbsp;Edit</a></li>
								<li><a class="handy" onClick="confirmDelete(\'[item-name]-delete\', [record_id], \'[ItemName]Grid\', \'[list-action]\');"><i class="icon-remove"></i>&nbsp;&nbsp;&nbsp;Delete</a></li>
							</ul>
						</div>
					</td>
					<td>
						<i class="icon-[icon] [icon-fade] handy" onClick="flagItem(\'[field_name]\', [record_id], \'[data_label]\', \'[ItemName]Grid\', \'[list-action]\');" rel="tooltip" title="Flag / unflag this entry."></i>
					</td>';
	static private $codeGridRowField = '
					<td class="[class]">[value]</td>';
	static private $codeGridRowFooter = '
				</tr>';
	static private $codeGridNoData = '
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="[columns]">No items to display</td>
				</tr>';
	static private $codeGridFooter = '
			</tbody>
		</table>
	</div>
</div>';
	static private $codeGridJs = '
<script type="text/javascript">
	var [itemName]Data = [json-data];
	$(document).ready(function() {
		$(".srch-[itemName]").keypress(function(e) {
		    if(e.which == 13) {
		        for (index in searchStack) {
		        	searchStack["srch-[itemName]"]();
		        }
		    }
		});
		resetTooltips();
	});
</script>';
	
}
