<?php

/**
 * Table model for lib_attachment
 */
class Table_LibAttachment extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_attachment';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array();

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array('lib_newsletter' => 'Table_LibNewsletter');

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
            'TABLE_NAME' => 'lib_attachment',
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
        'filename' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_attachment',
            'COLUMN_NAME' => 'filename',
            'COLUMN_POSITION' => 2,
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
        'document' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_attachment',
            'COLUMN_NAME' => 'document',
            'COLUMN_POSITION' => 3,
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
            'TABLE_NAME' => 'lib_attachment',
            'COLUMN_NAME' => 'mime_type',
            'COLUMN_POSITION' => 4,
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
        'filename' => 'Filename',
        'document' => 'Document',
        'mime_type' => 'MimeType'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'filename' => null,
        'document' => null,
        'mime_type' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[filename]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_attachment_filename]';


}

