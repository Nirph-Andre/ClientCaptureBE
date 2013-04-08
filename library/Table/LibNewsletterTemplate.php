<?php

/**
 * Table model for lib_newsletter_template
 */
class Table_LibNewsletterTemplate extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_newsletter_template';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array(
        'HeaderLibPhoto' => array(
            'columns' => 'header_lib_photo_id',
            'refTableClass' => 'Table_LibPhoto',
            'refColumns' => 'id'
            ),
        'FooterLibPhoto' => array(
            'columns' => 'footer_lib_photo_id',
            'refTableClass' => 'Table_LibPhoto',
            'refColumns' => 'id'
            )
        );

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
            'TABLE_NAME' => 'lib_newsletter_template',
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
        'name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter_template',
            'COLUMN_NAME' => 'name',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '50',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'header_lib_photo_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter_template',
            'COLUMN_NAME' => 'header_lib_photo_id',
            'COLUMN_POSITION' => 3,
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
        'footer_lib_photo_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter_template',
            'COLUMN_NAME' => 'footer_lib_photo_id',
            'COLUMN_POSITION' => 4,
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
        'name' => 'Name',
        'header_lib_photo_id' => 'HeaderLibPhotoId',
        'footer_lib_photo_id' => 'FooterLibPhotoId'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'name' => null,
        'header_lib_photo_id' => null,
        'footer_lib_photo_id' => null
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_newsletter_template_name]';


}

