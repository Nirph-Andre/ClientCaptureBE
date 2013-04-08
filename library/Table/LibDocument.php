<?php

/**
 * Table model for lib_document
 */
class Table_LibDocument extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_document';

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
            'TABLE_NAME' => 'lib_document',
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
        'document' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_document',
            'COLUMN_NAME' => 'document',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'longblob',
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
        'mime_type' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_document',
            'COLUMN_NAME' => 'mime_type',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '200',
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
        'document' => 'Document',
        'mime_type' => 'MimeType'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'document' => null,
        'mime_type' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[mime_type]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_document_mime_type]';


}

