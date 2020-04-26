# Laravel Owner

> A simple package that allows Eloquent models to "own" each other, or "be owned" by another model. Supports many-to-many relationships.

Examples could include: 

1. A user owning a blog post.
2. A user *and* a team owning multiple files.
3. Record being owned by many organizations.

## Installation

### Requirements

* Composer
* Laravel Framework 5.7+/6.0+/7.0+

### Installing Nexus

Run the following command in your console terminal:

```sh
$ composer require pandorga/laravel-owner
```

Publish the migrations and config files:

```sh
$ php artisan vendor:publish""
```

Run the migrations:

```sh
$ php artisan migrate
```

## Usage

### Add necessary traits your Eloquent models:

If the model can be an owner:

```php
use Pandorga\Owner\Traits\Owns;
	
class User extends Model
{
	use Owns;
}
```

If the model can be owned by another model:

```php
use Pandorga\Owner\Traits\HasOwner;
	
class Resource extends Model
{
	use HasOwner;
}
```

## Usage
### "Owner" model:

Create an ownership:

```php
$user->own($model);
```
Remove an ownership:

```php
$user->disown($model);
```

Return a collection of *all* the models owned by the parent model:

```php
$user->owns();
```

Does the user own this model?

```php
$user->ownsModel($model);
```

Which models of this type does the parent model own?
This method either takes a child model, or a name-spaced class name.

```php
$user->ownsModelType($model); // Use a model
$user->ownsModelType(‘App\Resource’); // Use class name
```

### "Owned" model:
Return a collection of all the model's owners:

```php
$model->owners();
```
Is the model is owned by another model?

```php
$model->isOwnedBy($owner);
```
Add an owner to the model:

```php
$model->addOwner($owner);
```
Remove an owner from the model

```php
$model->removeOwner($owner);
```

## Security

If you discover any security related issues, please use the issue tracker.

## Credits

- [Felix Ayala](http://felixaya.la)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.


