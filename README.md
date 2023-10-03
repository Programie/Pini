# Pini

A simple PHP class for reading and writing ini files.

[![License](https://poser.pugx.org/programie/pini/license.svg)](https://packagist.org/packages/programie/pini)
[![Latest Stable Version](https://poser.pugx.org/programie/pini/v/stable.svg)](https://packagist.org/packages/programie/pini)
[![Latest Unstable Version](https://poser.pugx.org/programie/pini/v/unstable.svg)](https://packagist.org/packages/programie/pini)
[![Total Downloads](https://poser.pugx.org/programie/pini/downloads.svg)](https://packagist.org/packages/programie/pini)

## Installation

Add the composer package "programie/pini" to the required packages of your composer.json:

```bash
composer require programie/pini
```

## Examples

### Parse ini and read values

```php
$ini = new Pini("/path/to/your/file.ini");

$ini->getValue("section name", "key name");// Returns the value of the key "key name" in section "section name"
```

### Merge sections and values from another ini file

Sometimes you want to merge sections and their keys from multiple ini files into one (e.g. if you want to provide default values as an ini file).

This can be done using the merge() method. It will replace all keys with the keys from the given ini file.

```php
$ini1 = new Pini("/path/to/your/first.ini");

$ini2 = new Pini("/path/to/your/second.ini");

$ini1->merge($ini2);
```

$ini1 will now contain all sections and keys from $ini2. An already existing key will be replaced with the key from the second ini file.

You may also specify the section you want to merge.

```php
$ini1->merge($ini2, "section name");
```

This will only merge keys from the given section from $ini2 into $ini1.

### Write values and save ini

With Pini you are also able to write ini files.

```php
$ini = new Pini();

$ini->setValue("section name", "key name", "some value");

$ini->save("/path/to/your/file.ini");
```

This will add or replace the key "key name" in the section "section name" with the value "some value".

The save() method saves the whole content of the Pini instance to a file.

Note: The $filename parameter in the save() method is optional. The filename passed on instance creation is used by default.

```php
$ini = new Pini("/path/to/your/file.ini");

// ... Some other ini methods.

$ini->save();
```

This will save the content to the file passed on instance creation ("/path/to/your/file.ini").

Look at the examples folder for more examples.

## Structure

An ini file can has the following structure:

```ini
[section name]
property key = property value
another property = another value
array property[] = some value
array property[] = another value

[another section]
another property = even another value
```

Ini files contain multiple sections. Each section can contain multiple properties.

A property key ending with "[]" defines an array. Define multiple same named array properties (e.g. "property[]") with any value to add values to the array.

Non-array properties with the same key already defined in the section will replace the previously defined property.