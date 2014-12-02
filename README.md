# PHPIniParser

A simple OOP alternative to PHP's parse_ini_file

## Installation

Add the composer package "programie/phpiniparser" to the required packages of your composer.json.

## Usage

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
