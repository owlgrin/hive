<?php namespace Owlgrin\Hive\Validation;

use Illuminate\Validation\Validator as IlluminateValidator;
use Illuminate\Support\MessageBag;
use Validator as MasterValidator;

class Validator extends IlluminateValidator {

	/**
	 * The format for the specifier
	 * @var string
	 */
	protected $specifierFormat = '[:specifier]';

	/**
	 * Method to validate call_each rule
	 * @param  string $attribute
	 * @param  mixed $value
	 * @param  array $parameters
	 * @return boolean
	 */
	protected function validateCallEach($attribute, $value, array $parameters)
	{
		list($class, $when) = explode('@', $parameters[0]);

		// Calling the required validator on each of the item
		// in the array.
		foreach($value as $index => $item)
		{
			$validator = $this->container->make($class);

			if(! $validator->when($when)->isValid($item))
			{
				// Merging with existing errors
				$this->addCallEachErrors($attribute, $index, $validator->getErrors());
			}
		}

		// Always returning true, because the invalid items in the array has already
		// trigerred the errors to be added in the errors
		return true;
	}

	/**
	 * Method to loop through errors and merge them into parent attribute
	 * @param string     $attribute
	 * @param integer     $index
	 * @param MessageBag $errors
	 */
	private function addCallEachErrors($attribute, $index, MessageBag $errors)
	{
		foreach($errors->getMessages() as $nestedAttribute => $messages)
		{
			foreach($messages as $message)
			{
				$specifier = $this->makeSpecifier('call_each');
				$specifier = str_replace(':position', number_ordinal($index + 1), $specifier);
				$specifier = str_replace(':parent_attribute', $attribute, $specifier);
				$this->messages->add($attribute, "$specifier $message");
			}
		}
	}

	/**
	 * Method to make specifier from the format
	 * @param  string $specifier
	 * @return string
	 */
	protected function makeSpecifier($rule)
	{
		return str_replace(
			':specifier',
			$this->translator->trans("hive::validation.specifiers.$rule"),
			$this->specifierFormat
		);
	}

	/**
	 * Method to set specifier format
	 * @param string $format
	 */
	private function setSpecifierFormat($format)
	{
		$this->specifierFormat = $format;
	}

	/**
	 * Method to get the specifier format
	 * @return string
	 */
	private function getSpecifierFormat()
	{
		return $this->specifierFormat;
	}

	/**
	 * Method to validate call_method rule
	 * @param  string $arrtibute
	 * @param  mixed $value
	 * @param  array $parameters
	 * @return boolean
	 */
	protected function validateCallMethod($arrtibute, $value, array $parameters)
	{
		list($class, $method) = explode('@', $parameters[0]);

		// Being awesome and doing it in one line ;)
		return $this->container->make($class)
			->$method($value);
	}

	/**
	 * Mehod to make replacements for call_method rule
	 * @param  string $message
	 * @param  string $attribute
	 * @param  string $rule
	 * @param  array $parameters
	 * @return string
	 */
	protected function replaceCallMethod($message, $attribute, $rule, array $parameters)
	{
		$message = $this->translator->trans("validation.methods.{$parameters[0]}");

		return str_replace(':attribute', $attribute, $message);
	}

	/**
	 * Method to validate call_another rule
	 * @param  string $attribute
	 * @param  mixed $value
	 * @param  array $parameters
	 * @return boolean
	 */
	protected function validateCallAnother($attribute, $value, array $parameters)
	{
		list($class, $when) = explode('@', $parameters[0]);

		$validator = $this->container->make($class);

		if(! $validator->when($when)->isValid($value))
		{
			// Merging with existing errors
			$this->addCallAnotherErrors($attribute, $validator->getErrors());
		}

		// Always returning true, because the errors are already handled above
		return true;
	}

	/**
	 * Method to loop through errors and merge them into parent attribute
	 * @param string     $attribute
	 * @param integer     $index
	 * @param MessageBag $errors
	 */
	private function addCallAnotherErrors($attribute, MessageBag $errors)
	{
		foreach($errors->getMessages() as $nestedAttribute => $messages)
		{
			foreach($messages as $message)
			{
				$specifier = $this->makeSpecifier('call_another');
				$specifier = str_replace(':attribute', $attribute, $specifier);
				$this->messages->add($attribute, "$specifier $message");
			}
		}
	}

	/**
	 * Method to validate call_another_with rule
	 * @param  string $attribute
	 * @param  mixed $value
	 * @param  array $parameters
	 * @return boolean
	 */
	protected function validateCallAnotherWith($attribute, $value, array $parameters)
	{
		list($class, $when) = explode('@', $parameters[0]);

		// fetch the entities
		// there could be multiple entities 
		// which is in format {entity1} {entity2}
		// fetching text between 2 parenthesis
		preg_match_all('/{(.*?)}/', $when, $replacers);

		//fetching the array of entities which would be replaced afterwords
		$entities = $replacers[1];
		
		//validating if entities exist in the data
		foreach($entities as $entity) 
		{
			$entityValidation = MasterValidator::make($this->data, [$entity => 'required']);

			if($entityValidation->fails())
			{
				// Merging with existing errors			
				$this->addCallAnotherErrors($attribute, $entityValidation->messages());
				return true;
			}
			else
			{
				//making validate function
				$when = str_replace('{'. $entity .'}', $this->data[$entity], $when);				
			}
		}

		$validator = $this->container->make($class);

		if(! $validator->when($when)->isValid($value))
		{
			// Merging with existing errors
			$this->addCallAnotherErrors($attribute, $validator->getErrors());
		}

		// Always returning true, because the errors are already handled above
		return true;
	}
}