Hive
====

Basic Foundation for Laravel apps.

The package includes the following other packages to get started with the development as quickly as possible.

- [Dingo API](https://github.com/dingo/api)
- [Laravel OAuth 2.0 Server Wrapper](https://github.com/lucadegasperi/oauth2-server-laravel)

Dingo API already depends upon the [Fractal](https://github.com/thephpleague/fractal), so transformations come out of the box.

Hive works with Laravel 4.2 as well as Laravel 5.

## Installation

You will need to require it in your `composer.json` file.

`"owlgrin/hive": "dev-master"`

And then, you will need to add the following service providers in your `app.php`.

```php
'Dingo\Api\ApiServiceProvider',
'LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider',
'Owlgrin\Hive\HiveServiceProvider',
```

## Validation

Hive comes with a custom validator with some extended features, which you can use instead of the Laravel's inbuilt one. (Custom Validator extends the Laravel's Validator, so you get everything that it has to offer.)

#### How to use custom validator

You will need to resolve it when the Validator is required. To do so, you can add the following code in any file that is autoloaded (eg. in `global.php`).

```php
Validator::resolver(function($translator, $data, $rules, $messages)
{
    return new Owlgrin\Hive\Validation\Validator($translator, $data, $rules, $messages);
});
```

#### How to use the validation

You will need to create a file, say `UserValidator.php`, which extends `Owlgrin\Hive\Validation\Runner`. Then, you can create various properties that hold the rules for various situations.

```php
<?php

use Owlgrin\Hive\Validation\Runner;

class UserValidator extends Runner {

	protected static $creating = [
		'name' => 'required',
		'email' => 'required|email'
	];
}
```

Once that done, you can pass the file as dependency in your controller/repository and then validate the data like so:

```php
public function store()
{
	$data = Input::all();

	$if( ! $this->userValidator->when('creating')->isValid($data)) return Redirect::back();

	// All good. Go ahead!
}
```

Please note that the argument passed in `when` method should be same as the static property in the class that extends `Runner`.

We recommend using lanuage files to specify the error messages, but you can create another static property with this format: `messagesWhen{situation}` (eg. `messagesWhenCreating`), and those custom messages will be passed to the the validator when validating.

#### Extra Rules

However custom validator makes validation super easy, it has some extra rules too, that would make validating any complex data structure easy.

##### call_method

Using, `call_method` rule, you can call a method on another class, which handles the complex validation. The called method should return `false` when validation fails and `true` otherwise.

**Usage:**

```php
protected static $creating = [
	'invoice_items' => 'array|min:1|call_method:Path\To\StockValidator@isEnoughStockInInventory'
];
```

You can specify the error messages for this rule in your language files. Add the following section in your `validation.php` at the end.

```php
'methods' => array(
    'Path\To\StockValidator@isEnoughStockInInventory' => 'The :attribute does not have enough stock.'
),
```

##### call_each

We sometimes, need to validate an array and each element in the array has to pass some validation. This rule can be used for such purposes.

For instance, you want to validate that the marks of student in each subject should be above 50 for it to be processed for the certificate.

The data might look like this:

```php
$student = [
	'name' => 'John Doe',
	'roll_number' => 1337,
	'marks' => [
		['subject' => 'Physics', 'marks' => 32],
		['subject' => 'Programming', 'marks' => 98]
	]
];
```

Now, we may have a student validator that will validate if the student data but in case we need to validate that each element in the `marks` array should pass certain rules, we can create a class `StudentMarksValidator`,

```php
<?php

use Owlgrin\Hive\Validation\Runner;

class StudentMarksValidator extends Runner {

	protected static $certifying = [
		'subject' => 'required',
		'marks' => 'required|min:50'
	];
}
```

And in `StudentValidator`, we can add the rules like so:

```php
<?php

use Owlgrin\Hive\Validation\Runner;

class StudentValidator extends Runner {

	protected static $certifying = [
		'name' => 'required',
		'roll_number' => 'required',
		'marks' => 'array|required|size:2|call_each:StudentMarksValidator@certifying'
	];
}
```

Once done, you will get error messages like following:

`[1st item in marks] The marks must be at least 50.`

We handle everything that is required to keep track of the position of the element and create proper and meaningful error messages.

The first part in the error message is called the specifier and the format can be controlled using the language files. To override the default format, create language file called `validation.php` in `app/lang/packages/{locale}/hive` with the following:

```php
<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Specifiers
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to create specifiers for the rules,
	| that are processed in nested fashion (eg. call_each).
	|
	*/
	'specifiers' => [
		'call_each' => ':position item in :parent_attribute'
	],
];
```

## Input

**If you already use Eloquent, you probably don't need this.**

Hive provides an easy way to transform and map the input, so that you get the consistent data to work upon.

Suppose you want to map the input data into something meaningful like this:

```php
$input = array(
	'name' => 'John Doe',
	'age' => 24, // note that this is integer,
	'gender' => null // user doesn't want to tell, hence mapped to null
);
```

To accomplish this, you need to create a trait, say `UserProperties`, which uses `Owlgrin\Hive\Input\Mapper` like this:

```php
<?php

trait UserProperties {
	use Owlgrin\Hive\Input\Mapper;

	protected $defaultProperties = array(
		'name' => 'string|null',
		'age' => 'int|0',
		'gender' => 'string|null'
	);
}
```

Now, use this trait in your controllers.

```php

// UserController.php
class UserController extends Controller {
	use UserProperties;

	public function store()
	{
		$input => $this->getInput(); // this will get you the properly mapped input
	}
}
```

You can also use this by creating a class instead of a trait, like so:

```php
<?php

class UserMapper {
	use Owlgrin\Hive\Input\Mapper;

	// properties here
}
```

Classes can be extended and also injected. Hence they are more handy sometimes.

#### Properties

You need to define the properties in an array where the key is the name of the property and the value is a string in the following format: `{type}|{default}`. The property will be typecasted into the defined type and is absent, will be replaced by the default value defined.

All the properties must be defined in the tair properties with following format: ${specifier}Properties. By default, Mapper looks for the 'defaultProperties', but you can look for different properties using `$this->getInput('updation')`, which will look for `$updationProperties` on the trait.

#### Defaults

You can define any default and they all will be typecasted into the specified type of the property. This gives you the consistent data throughout.

There are a few special default values, however.

**null**: This value will set the value as `null` no matter what the type is.

**unset**: This will remove the propert from the input if it is an falsey value.

#### Callbacks

There might be needs when you need to perform some actions after getting the data mapped. To do so, you simple need to create a method in the trait or the class using the trait in the following format:

```php
protected function {specifier}Callback($original, &$mapped)
{

}
```

The callback will receive two arguments, one is the original input that the request got and another is the reference to the mapped input. You can change the mapped input however you wish as it is passed as reference.

## Responses

We need to create meaningful responses and we provide you with a trait that makes it super simple to do. In your controller, you can use the trait with the following statement:

`use Owlgrin\Hive\Response\Responses;`

And then you can create responses such easily:

`return $this->respondWithData($data);`

Or for the responses without content (HTTP Code: 204), you can use this method:

`return $this->respondNoContent();`

You even get the following methods to send back error messages, with proper HTTP status, a custom code and a custom type.

```php
$this->respondBadRequest();
$this->respondUnauthorized();
$this->respondForbidden();
$this->respondNotFound();
$this->respondInvalidInput();
$this->respondInternalError();
```

Each of these method accepts two optional parameters: `$message` and `$code`, which you can pass to override the default ones.

The errors are generated of the following structure:

```js
{
	error: {
		code: 400,
		type: "invalid_input",
		message: "Invalid input."
	}
}
```

If you extend your controllers from the `Dingo\Api\Routing\Controller`, you can use transformers to transform the responses using Fractal. You can find more about it in the Fractal's documentation.

## Exceptions

Hive comes with custom exceptions, to make them easier to handle. These are the followin custom exceptions that you can use:

```php
Owlgrin\Hive\Exceptions\BadRequestException;
Owlgrin\Hive\Exceptions\UnauthorizedException;
Owlgrin\Hive\Exceptions\ForbiddenException;
Owlgrin\Hive\Exceptions\NotFoundException;
Owlgrin\Hive\Exceptions\InvalidInputException;
Owlgrin\Hive\Exceptions\InternalException;
```

Each of these extend an abstract class `Owlgrin\Hive\Exceptions\Exception`, which allows us parse the messages from validator or the language file keys and prepare the response.

You can use it like following:

```php
try
{
	if($this->validator->when('creating')->isInvalid($data))
	{
		throw new Owlgrin\Hive\Exceptions\InvalidInputException($this->validator->getErrors());
	}
}
catch(Owlgrin\Hive\Exceptions\InvalidInputException $e)
{
	return $this->respondInvalidInput($e->getMessage(), $e->getCode());
}
```

As a bonus, `Owlgrin\Hive\HiveServiceProvider` already registers various exception handlers for your app, so that you can focus on important work rather than catching exceptions. Hive automatically, catches and prepares the required response.

Try throwing an exception from your routes file. ;)

***

We are constantly working to improve the package and the pull requests are welcome. We want to make things super easy to get started with. We use this package in our own projects when starting anything new.
