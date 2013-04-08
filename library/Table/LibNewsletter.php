<?php

/**
 * Table model for lib_newsletter
 */
class Table_LibNewsletter extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_newsletter';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array(
        'LibNewsletterTemplate' => array(
            'columns' => 'lib_newsletter_template_id',
            'refTableClass' => 'Table_LibNewsletterTemplate',
            'refColumns' => 'id'
            ),
        'LibAttachment' => array(
            'columns' => 'lib_attachment_id',
            'refTableClass' => 'Table_LibAttachment',
            'refColumns' => 'id'
            )
        );

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
            'TABLE_NAME' => 'lib_newsletter',
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
        'lib_newsletter_template_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'lib_newsletter_template_id',
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
        'subject' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'subject',
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
        'content' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'content',
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
        'lib_attachment_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'lib_attachment_id',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'int',
            'DEFAULT' => null,
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
            ),
        'status' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'status',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'enum(\'Draft\',\'Sent\',\'Test\')',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => null,
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'sent_to' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'sent_to',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'mediumint',
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
            ),
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 8,
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
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'updated',
            'COLUMN_POSITION' => 9,
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
            'TABLE_NAME' => 'lib_newsletter',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 10,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => '0',
            'NULLABLE' => false,
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
        'lib_newsletter_template_id' => 'LibNewsletterTemplateId',
        'subject' => 'Subject',
        'content' => 'Content',
        'lib_attachment_id' => 'LibAttachmentId',
        'status' => 'Status',
        'sent_to' => 'SentTo',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'lib_newsletter_template_id' => null,
        'subject' => null,
        'content' => null,
        'lib_attachment_id' => null,
        'status' => null,
        'sent_to' => '0',
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[subject]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_newsletter_subject]';


}

