Link dependencies to a local clone. Heavily inspired by Symfony's [`link`](https://github.com/symfony/symfony/blob/master/link) script.

Install
-------

Install this as any other (dev) Composer package:
```sh
$ composer require --dev ro0nl/link
```

You can also install it once for all your local projects:
```sh
$ composer global require ro0nl/link
```

Usage
-----

```sh
$ composer link /path/to/project
```

This will find all of your Composer packages in the current directory and links dependencies in your project to it.

Symfony Example
---------------

```sh
git clone git@github.com:symfony/symfony.git symfony
git clone git@github.com:company/symfony-project.git project

cd symfony
composer link ../project
```

Original idea by Kévin Dunglas.
