<?php

/**
 * Table model for content
 */
class Table_Content extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'content';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array();

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
            'TABLE_NAME' => 'content',
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
        'type' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'type',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '25',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'name',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '100',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'html' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'html',
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
        'js' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'js',
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
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'datetime',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 35,
            'FLAG_LIST' => 'FIELD_REQUIRED | FIELD_INSERT_DATETIME'
            ),
        'updated' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'updated',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'datetime',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 288,
            'FLAG_LIST' => 'FIELD_UPDATE_DATETIME'
            ),
        'archived' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'content',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => '0',
            'NULLABLE' => true,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => true,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            )
        );

    /**
     * TABLE_NO_DELETE | TABLE_PSEUDO_DELETE
     */
    protected $tableFlags = 12;

    /**
     * Field used to flag entry as archived.
     */
    protected $archiveField = 'archived';

    /**
     * Field db-name to code-name mapping.
     */
    protected $fieldNames = array(
        'id' => 'Id',
        'type' => 'Type',
        'name' => 'Name',
        'html' => 'Html',
        'js' => 'Js',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'type' => null,
        'name' => null,
        'html' => null,
        'js' => null,
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[content_name]';


}

