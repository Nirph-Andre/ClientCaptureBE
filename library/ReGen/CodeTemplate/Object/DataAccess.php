<?php

/***
 * @type: CodeFile
 * @id: ValueObject
 */
/***
 * @type: ClassHeader
 * @id: ClassHeader
 * @requiredTags: className, classDescription, author, table_name
 */

/**
 * __classDescription__
 * @author __author__
 */
class Object___className__Data extends Struct_Abstract_DataAccess
{
	
	/**
	 * Table that this object provides convenience methods for.
	 * @var string
	 */
	protected $_table = array(/*table_name*/);
	
/***/
/***
 * @type: function
 * @id: linkItems
 * @requiredTags: ItemName, BaseItemName, item_table_name, base_table_name
 */
	
	/**
	 * Link __ItemName__ entry to __BaseItemName__ entry.
	 * @param  integer $id
	 * @param  integer $baseId
	 * @param  array   $extraData
	 * @return Struct_ActionFeedback
	 */
	public function link__ItemName__($id, $baseId, array $extraData = array())
	{
		$extraData['/*base_table_name*/_id'] = $baseId;
		$extraData['/*item_table_name*/_id'] = $id;
		return $this->_getTable($this->_table)
			->_updateSingle(
				array(
						"/*base_table_name*/_id = ?" => $baseId,
						"/*item_table_name*/_id = ?" => $id
						),
				$extraData
					);
	}
/***/
	
	
/***
 * @type: ClassFooter
 * @id: ClassFooter
 */
	
	
}

/***/
/***/
