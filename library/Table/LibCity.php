<?php

/**
 * Table model for lib_city
 */
class Table_LibCity extends Struct_Abstract_ModelTable
{

    /**
     * Database table name.
     */
    protected $_name = 'lib_city';

    /**
     * Data associations to other tables.
     */
    protected $_referenceMap = array('LibRegion' => array(
            'columns' => 'lib_region_id',
            'refTableClass' => 'Table_LibRegion',
            'refColumns' => 'id'
            ));

    /**
     * Tables dependant on this one.
     */
    protected $_dependentTables = array('lib_address' => 'Table_LibAddress');

    /**
     * Data dependancy chain.
     */
    protected $dependancyChain = array('LibRegion' => array(array(
                'columns' => 'lib_country_id',
                'refTableClass' => 'Table_LibCountry',
                'refColumns' => 'id'
                )));

    /**
     * Field meta data.
     */
    protected $_metadata = array(
        'id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
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
        'lib_region_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'lib_region_id',
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
        'lib_timezone_id' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'lib_timezone_id',
            'COLUMN_POSITION' => 3,
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
        'name' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'name',
            'COLUMN_POSITION' => 4,
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
        'latitude' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'latitude',
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
        'longitude' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'longitude',
            'COLUMN_POSITION' => 6,
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
        'archived' => array(
            'SCHEMA_NAME' => null,
            'TABLE_NAME' => 'lib_city',
            'COLUMN_NAME' => 'archived',
            'COLUMN_POSITION' => 7,
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
        'lib_region_id' => 'LibRegionId',
        'lib_timezone_id' => 'LibTimezoneId',
        'name' => 'Name',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'archived' => 'Archived'
        );

    /**
     * Default values for new data entry.
     */
    protected $newRow = array(
        'id' => null,
        'lib_region_id' => null,
        'lib_timezone_id' => null,
        'name' => null,
        'latitude' => null,
        'longitude' => null,
        'archived' => '0'
        );

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormat = '[name]';

    /**
     * Label format for list/dropdown display.
     */
    protected $labelFormatForeign = '[lib_city_name]';


}

