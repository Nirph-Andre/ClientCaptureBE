<?php

/**
 * Table model for lib_notification_log
 */
class Table_LibNotificationLog extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_notification_log';

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
            'TABLE_NAME' => 'lib_notification_log',
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
        'email_to' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'email_to',
            'COLUMN_POSITION' => 2,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '250',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'email_subject' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'email_subject',
            'COLUMN_POSITION' => 3,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '250',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'email_body' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'email_body',
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
        'sms_to' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'sms_to',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'sms_body' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'sms_body',
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
            ),
        'api_msg_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'api_msg_id',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '32',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'sms_status' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'sms_status',
            'COLUMN_POSITION' => 8,
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
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 9,
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
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'updated',
            'COLUMN_POSITION' => 10,
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
            'TABLE_NAME' => 'lib_notification_log',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 11,
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
        'email_to' => 'EmailTo',
        'email_subject' => 'EmailSubject',
        'email_body' => 'EmailBody',
        'sms_to' => 'SmsTo',
        'sms_body' => 'SmsBody',
        'api_msg_id' => 'ApiMsgId',
        'sms_status' => 'SmsStatus',
        'created' => 'Created',
        'updated' => 'Updated',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'email_to' => null,
        'email_subject' => null,
        'email_body' => null,
        'sms_to' => null,
        'sms_body' => null,
        'api_msg_id' => null,
        'sms_status' => null,
        'created' => null,
        'updated' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[email_to]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_notification_log_email_to]';


}

