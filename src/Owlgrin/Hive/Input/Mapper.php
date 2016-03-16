<?php namespace Owlgrin\Hive\Input;

use Illuminate\Support\Facades\Input;
use Owlgrin\Hive\Exceptions\InvalidPropertyException;

trait Mapper {

	/**
	 * Defaults when not specified in the properties
	 * @var array
	 */
	private $propertyDefaults = array(
		'integer' => 0,
		'float' =>  0,
		'boolean' => false,
		'string' => ''
	);

	/**
	 * Method to fetch input for a situation
	 * @param  string $when
	 * @return array
	 */
	public function getInput($when = 'default', $input = null)
	{
		$properties = $this->getProperties($when);
		if(is_null($input)) $input = Input::all();
		
		// only if properties exists
		if( ! is_null($properties))
		{

			$mapped = input_only($input, array_keys($properties));

			foreach($mapped as $property => $value)
			{
				list($type, $default) = $this->parseProperty(array_get($properties, $property));

				// Now, we will first trim the value to remove any leading or trailing whitespace
				$value = is_array($value) ? $value : trim($value);

				// If the default was to unset the property itself, we will simply
				// unset it and continue on other properties
				if(empty($value) and $default === 'unset')
				{
					unset($mapped[$property]);
					continue;
				}

				// We will set the proper types on the value
				settype($value, $type);

				// If default was specified to be null, then we will set its type to null,
				// otherwise, we will simply typecast it to property's type
				settype($default, $default === 'null' ? 'null' : $type);

				// Setting the value back in the input
				$mapped[$property] = empty($value) ? $default : $value;
			}
		}

		// Run callback, if any
		if(method_exists($this, $when . 'Callback'))
		{
			call_user_func_array(array($this, $when . 'Callback'), array($input, &$mapped));
		}

		return $mapped;
	}

	/**
	 * Method to get the list of properties
	 * @param  string $when
	 * @return array
	 */
	private function getProperties($when = 'default')
	{
		if( ! isset($this->{$when . 'Properties'}))
		{
			return null;
		}

		return $this->{$when . 'Properties'};
	}

	/**
	 * Method to parse a property
	 * @param  string $property
	 * @return array
	 */
	private function parseProperty($property)
	{
		$parsedProperty = explode('|', $property);

		// If no default was specified, we will fill it with the sensible default
		// for that particular type
		if( ! isset($parsedProperty[1]))
		{
			// but even if there's no default found, we will error out
			if( ! isset($this->propertyDefaults[$parsedProperty[0]]))
			{
				throw new InvalidPropertyException("Property: [$parsedProperty[0]] not supported.");
			}
			$parsedProperty[1] = $this->propertyDefaults[$parsedProperty[0]];
		}

		return $parsedProperty;
	}
}