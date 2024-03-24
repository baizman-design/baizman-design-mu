# README

## overview

This is a WordPress [must-use plugin](https://wordpress.org/documentation/article/must-use-plugins/) containing constant definitions and general configuration settings used across my development environments.

## installation

First, create the must-use plugin folder inside `wp-content`:

```shell
$ mkdir path/to/wordpress/wp-content/mu-plugins
```

### option 1: copy

To install the plugin, download the repository and copy `bzmndsgn-mu.php` to `/wp-content/mu-plugins`. Note that it must be manually updated due to the nature of must-use plugins.

### option 2: symlink

If you clone the repository and create a symbolic link to `bzmndsgn-mu.php`, the plugin can be updated via `git pull`.

```shell
$ ln -s path/to/repository/bzmndsgn-mu.php path/to/wordpress/wp-content/mu-plugins/bzmndsgn-mu.php
```

### option 3: composer

Require the package in `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:baizmandesign/bzmndsgn-mu.git"
        }
    ],
    "require-dev": {
        "baizmandesign/bzmndsgn-mu": "dev-production"
    }
}
```

Install the package:
```
$ composer update
```

Then create a symbolic link:

```shell
$ ln -s vendor/baizmandesign/bzmndsgn-mu/bzmndsgn-mu.php path/to/wordpress/wp-content/mu-plugins/bzmndsgn-mu.php
```

## configuration

### disable plugins

The plugin can forcibly disable other plugins. This is useful for plugins that should only be run in a production environment, such as backup, firewall, and caching plugins. To disable a plugin, add a slug to the `$disabled_plugins` array at the top of `bzmndsgn-mu.php`.
