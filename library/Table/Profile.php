<?php

/**
 * Table model for profile
 */
class Table_Profile extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'profile';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array();

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array(
        'app_link_request' => 'Table_AppLinkRequest',
        'lib_authentication_log' => 'Table_LibAuthenticationLog'
        );

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
            'TABLE_NAME' => 'profile',
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
        'first_name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'first_name',
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
        'family_name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'family_name',
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
        'id_number' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'id_number',
            'COLUMN_POSITION' => 4,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '13',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'date_of_birth' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'date_of_birth',
            'COLUMN_POSITION' => 5,
            'DATA_TYPE' => 'date',
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
        'mobile' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'mobile',
            'COLUMN_POSITION' => 6,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '20',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'email' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'email',
            'COLUMN_POSITION' => 7,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => true,
            'LENGTH' => '255',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'username' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'username',
            'COLUMN_POSITION' => 8,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '40',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'password' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'password',
            'COLUMN_POSITION' => 9,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '40',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'password_salt' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'password_salt',
            'COLUMN_POSITION' => 10,
            'DATA_TYPE' => 'varchar',
            'DEFAULT' => null,
            'NULLABLE' => false,
            'LENGTH' => '40',
            'SCALE' => null,
            'PRECISION' => null,
            'UNSIGNED' => null,
            'PRIMARY' => false,
            'PRIMARY_POSITION' => null,
            'IDENTITY' => false,
            'FLAGS' => 3,
            'FLAG_LIST' => 'FIELD_REQUIRED'
            ),
        'user_type' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'user_type',
            'COLUMN_POSITION' => 11,
            'DATA_TYPE' => 'enum(\'User\',\'Administrator\')',
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
        'status' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'status',
            'COLUMN_POSITION' => 12,
            'DATA_TYPE' => 'enum(\'Active\',\'Suspended\')',
            'DEFAULT' => 'Active',
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
        'subscribe_newsletter' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'subscribe_newsletter',
            'COLUMN_POSITION' => 13,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => '1',
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
        'subscribe_reminders' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'subscribe_reminders',
            'COLUMN_POSITION' => 14,
            'DATA_TYPE' => 'tinyint',
            'DEFAULT' => '1',
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
        'last_login' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'last_login',
            'COLUMN_POSITION' => 15,
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
            'FLAGS' => 0,
            'FLAG_LIST' => ''
            ),
        'created' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'created',
            'COLUMN_POSITION' => 16,
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
        'archived' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'profile',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 17,
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
        'first_name' => 'FirstName',
        'family_name' => 'FamilyName',
        'id_number' => 'IdNumber',
        'date_of_birth' => 'DateOfBirth',
        'mobile' => 'Mobile',
        'email' => 'Email',
        'username' => 'Username',
        'password' => 'Password',
        'password_salt' => 'PasswordSalt',
        'user_type' => 'UserType',
        'status' => 'Status',
        'subscribe_newsletter' => 'SubscribeNewsletter',
        'subscribe_reminders' => 'SubscribeReminders',
        'last_login' => 'LastLogin',
        'created' => 'Created',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'first_name' => null,
        'family_name' => null,
        'id_number' => null,
        'date_of_birth' => null,
        'mobile' => null,
        'email' => null,
        'username' => null,
        'password' => null,
        'password_salt' => null,
        'user_type' => null,
        'status' => 'Active',
        'subscribe_newsletter' => '1',
        'subscribe_reminders' => '1',
        'last_login' => null,
        'created' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[first_name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[profile_first_name]';


}

