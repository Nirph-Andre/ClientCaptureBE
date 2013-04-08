<?php


/**
 * Location identification service.
 * @author andre.fourie
 */
class IpLocation extends Struct_Abstract_AmfService
{
	
	/**
	 * Return country according to ip.
	 * @return string
	 */
	public function getCountry()
	{
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$table = new Zend_Db_Table('lib_ip_country');
		$country_code = $table->getAdapter()
			->select()
			->from('lib_ip_country', 'country_name')
			->where("$ip BETWEEN ip_from AND ip_to")
			->query()
			->fetchColumn(0);
		return $country_code;
	}
	
	
}

