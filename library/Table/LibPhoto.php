<?php

/**
 * Table model for lib_photo
 */
class Table_LibPhoto extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_photo';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array();

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array('lib_newsletter_template' => 'Table_LibNewsletterTemplate');

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
            'TABLE_NAME' => 'lib_photo',
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
        'photo' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_photo',
            'COLUMN_NAME' => 'photo',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'mediumblob',
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
        'thumbnail' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_photo',
            'COLUMN_NAME' => 'thumbnail',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'mediumblob',
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
            'TABLE_NAME' => 'lib_photo',
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
        'photo' => 'Photo',
        'thumbnail' => 'Thumbnail',
        'mime_type' => 'MimeType'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'photo' => null,
        'thumbnail' => null,
        'mime_type' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[mime_type]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_photo_mime_type]';


}

