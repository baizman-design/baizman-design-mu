# README

## overview

This is a WordPress [must-use plugin](https://wordpress.org/documentation/article/must-use-plugins/) containing constant definitions and general configuration settings used across my development environments.

## installation

First, create the must-use plugin folder inside `wp-content`:

```shell
$ mkdir path/to/wordpress/wp-content/mu-plugins
```

### option 1: copy

To install the plugin, download the repository and copy `baizman-design-mu.php` to `path/to/wordpress/wp-content/mu-plugins`. Note that it must be manually updated due to the nature of must-use plugins.

### option 2: symlink

Download or clone the repository and create a symbolic link to `baizman-design-mu.php`:

```shell
$ ln -s path/to/repository/baizman-design-mu.php path/to/wordpress/wp-content/mu-plugins/baizman-design-mu.php
```

If you clone the repository, the plugin can be updated easily via `git pull`.

### option 3: composer

Require the package in `composer.json`:
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:baizmandesign/baizman-design-mu.git"
        }
    ],
    "require-dev": {
        "baizmandesign/bzmndsgn-mu": "dev-production"
    },
    "scripts": {
        "post-package-install": [
          "$(composer config vendor-dir)/baizmandesign/baizman-design-mu/bin/make-symlink.sh"
        ]
    }
}
```

Install the package:
```
$ composer install
```

The post-install script will automatically create the `wp-content/mu-plugins` folder, if needed, and a symbolic link to `baizman-design-mu.php`.

## configuration

### disable plugins

The plugin can forcibly disable other plugins. This is useful for plugins that should only be run in a production environment, such as backup, firewall, and caching plugins. 

To disable a plugin, add a slug, one per line, to a file named `.baizman-design-mu-disabled-plugins` in the root directory of your WordPress instance.
