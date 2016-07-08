# Validator of domain names

By Denis Mitrofanov, 2016

## Installation
    composer require denismitr/real-domain-validator

## Usage
If you are using Laravel, than you can extend the Laravel validator in `AppServiceProvider@boot` like so:

```php
Validator::extend('real_domain', 'Denismitr\Validators\RealDomainValidator@validationCallback');
```
Otherwise just install, instantiate and use `isValidDomain` method like that:

```php
$validator = new RealDomainValidator();
$result = $validator->isValidDomain("google.com");
```

Http or https prefixes not a problem - will work as well.