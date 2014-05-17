<?php

if ( ! function_exists('number_ordinal'))
{
	/**
	 * Get ordinal version of a number
	 *
	 * @param  integer  $number
	 * @return string
	 */
	function number_ordinal($number)
	{
		if( ! is_numeric($number)) return null;

		$suffixes = array(1 => 'st', 2 => 'nd', 3 => 'rd');

		// If last two digits are 11, 12, 13; return with 'th' suffix
		$lastTwoDigits = ((int) $number) % 100;
		if($lastTwoDigits >= 11 and $lastTwoDigits <= 13)
		{
			return $number . 'th';
		}

		// If lat digit is either 1, 2 or 3; return with respective suffix
		$lastDigit = ((int) $number) % 10;
		if($lastDigit >= 1 and $lastDigit <= 3)
		{
			return $number . $suffixes[$lastDigit];
		}

		// Otherwise, return with 'th'
		return $number . 'th';
	}
}