<?php

/**
 * Data services for tablet devices.
 */
class Lookup extends Struct_Abstract_AmfService
{

    /**
     * Find existing LibCity entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findLibCity($authToken, $data)
    {
        return $this->synch($authToken, 'LibCity', 'Find', $data);
    }

    /**
     * key > value list of LibCity entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listLibCity($authToken, $options)
    {
        return $this->synch($authToken, 'LibCity', 'List', array(), $options);
    }

    /**
     * Full data grid of LibCity entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridLibCity($authToken, $options)
    {
        return $this->synch($authToken, 'LibCity', 'Grid', array(), $options);
    }

    /**
     * Find existing LibRegion entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findLibRegion($authToken, $data)
    {
        return $this->synch($authToken, 'LibRegion', 'Find', $data);
    }

    /**
     * key > value list of LibRegion entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listLibRegion($authToken, $options)
    {
        return $this->synch($authToken, 'LibRegion', 'List', array(), $options);
    }

    /**
     * Full data grid of LibRegion entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridLibRegion($authToken, $options)
    {
        return $this->synch($authToken, 'LibRegion', 'Grid', array(), $options);
    }

    /**
     * Find existing LibCountry entry by id.
     *
     * @param string $authToken
     * @param array $data
     * @return array
     */
    public function findLibCountry($authToken, $data)
    {
        return $this->synch($authToken, 'LibCountry', 'Find', $data);
    }

    /**
     * key > value list of LibCountry entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function listLibCountry($authToken, $options)
    {
        return $this->synch($authToken, 'LibCountry', 'List', array(), $options);
    }

    /**
     * Full data grid of LibCountry entries.
     *
     * @param string $authToken
     * @param array $options
     * @return array
     */
    public function gridLibCountry($authToken, $options)
    {
        return $this->synch($authToken, 'LibCountry', 'Grid', array(), $options);
    }


}

