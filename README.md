# README

## overview

This is a WordPress [must-use plugin](https://wordpress.org/documentation/article/must-use-plugins/) containing constant definitions used across my development environments.

## installation

### copy

To install the plugin, copy `bzmndsgn-mu.php` to `/wp-content/mu-plugins`. Note that it must be manually updated due to the nature of must-use plugins.

### symlink

If you create a symbolic link, there's no need to manually update the plugin.

```shell
$ mkdir ~/www/site.test/wp-content/mu-plugins
$ ln -s ~/www/bzmndsgn-mu/bzmndsgn-mu.php ~/www/site.test/wp-content/mu-plugins/bzmndsgn-mu.php
``` 