<?php


class Component_Verify
{
	
	static public function verifyIdNumber($idNumber)
	{
		if (13 != strlen($idNumber))
		{
			return false;
		}
		$odd = substr($idNumber, 0, 1)
				 + substr($idNumber, 2, 1)
				 + substr($idNumber, 4, 1)
				 + substr($idNumber, 6, 1)
				 + substr($idNumber, 8, 1)
				 + substr($idNumber, 10, 1);
		$even = substr($idNumber, 1, 1)
					. substr($idNumber, 3, 1)
					. substr($idNumber, 5, 1)
					. substr($idNumber, 7, 1)
					. substr($idNumber, 9, 1)
					. substr($idNumber, 11, 1);
		$even *= 2;
		$num = 0;
		for ($i = 0; $i < strlen($even); $i++)
		{
			$num += substr($even, $i, 1);
		}
		$total = $odd + $num;
		$control = 10 - substr($total, 1, 1);
		return substr($control, -1, 1) == substr($idNumber, -1, 1);
	}
	
	static public function extractIdNumberData($idNumber)
	{
		if (13 != strlen($idNumber))
		{
			return false;
		}
		return array(
				'Date of Birth'  => '19' . substr($idNumber, 0, 2)
														. '-' . substr($idNumber, 2, 2)
														. '-' . substr($idNumber, 4, 2),
				'Gender'         => substr($idNumber, 6, 1) < 5 ? 'Female' : 'Male',
				'Citizenship'    => substr($idNumber, 10, 1) == 0 ? 'South African' : 'Other',
				'Valid Checksum' => self::verifyIdNumber($idNumber)
				);
	}
	
}

