# PHPIniParser

A simple OOP alternative to PHP's parse_ini_file

## Installation

Add the composer package "programie/phpiniparser" to the required packages of your composer.json.

## Usage

### Methods

#### getData

```php
$ini->getData()
```

Returns the internal array containing all sections and their keys.

Each array element represents a section which is another array containing the key value pairs.

Example structure:

```
array
(
	"section1" => array
	(
		"key" => "some value",
		"another key" => "another value"
	),
	"another section" => array
	(
		"key" => "value of another section",
		"yet another key" => "value"
	)
)
```

#### getValue

Returns the value of the specified key in the specified section.

```php
$ini->getValue($section, $key)
```

#### merge

Merges the data of the given Ini instance into the instance from which you are calling this method.

The optional $section parameter allows you to only merge a specified section.

```php
$ini->merge(Ini $otherInstance, $section = null)
```

### Parse ini and read values

```php
$ini = new Ini("/path/to/your/file.ini");

$ini->getValue("section name", "key name");// Returns the value of the key "key name" in section "section name"
```

### Merge sections and values from another ini file

Sometimes you want to merge sections and their keys from multiple ini files into one (e.g. if you want to provide default values as an ini file).

This can be done using the merge() method. It will replace all keys with the keys from the given ini file.

```php
$ini1 = new Ini("/path/to/your/first.ini");

$ini2 = new Ini("/path/to/your/second.ini");

$ini1->merge($ini2);
```

$ini1 will now contain all sections and keys from $ini2. An already existing key will be replaced with the key from the second ini file.

You may also specify the section you want to merge.

```php
$ini1->merge($ini2, "section name");
```

This will only merge keys from the given section from $ini2 into $ini1.
