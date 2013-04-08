<?php

/**
 * Table model for lib_repeater_template
 */
class Table_LibRepeaterTemplate extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_repeater_template';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array('LibTemplate' => array(
            'columns' => 'lib_template_id',
            'refTableClass' => 'Table_LibTemplate',
            'refColumns' => 'id'
            ));

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array();

    /**
     * Data dependancy chain.
     */
    protected $dependancyChain = array();

    /**
     * Field meta data.
     */
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'id',
            'COLUMN_POSITION' => 1,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => true,
            'PRIMARY_POSITION' => 1,
            'IDENTITY' => true,
            'FLAGS' => 2051,
            'FLAG_LIST' => 'FIELD_AUTOKEY | FIELD_REQUIRED'
            ),
        'lib_template_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'lib_template_id',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'group_field' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'group_field',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '50',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'group_repeater' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'group_repeater',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'text',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'row_repeater_odd' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'row_repeater_odd',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'text',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'row_repeater_even' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_repeater_template',
            'COLUMN_NAME' => 'row_repeater_even',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'text',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            )
        );

    /**
     * No flags
     */
    protected $tableFlags = 0;

    /**
     * Field used to flag entry as archived.
     */
    protected $archiveField = false;

    /**
     * Field db-name to code-name mapping.
     */
    protected $fieldNames = array(
        'id' => 'Id',
        'lib_template_id' => 'LibTemplateId',
        'group_field' => 'GroupField',
        'group_repeater' => 'GroupRepeater',
        'row_repeater_odd' => 'RowRepeaterOdd',
        'row_repeater_even' => 'RowRepeaterEven'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'lib_template_id' => null,
        'group_field' => null,
        'group_repeater' => null,
        'row_repeater_odd' => null,
        'row_repeater_even' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[group_field]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_repeater_template_group_field]';


}

