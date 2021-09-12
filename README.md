# WordPress Network Search Replace

Run WP CLI search and replace on WordPress Network sites, using a primary domain if available.

### Installation

This project is designed to be included from a site's `composer.json` file, and placed in its appropriate installation directory by [Composer Installers](https://github.com/composer/installers).

In order for this to work, you should have the following in your composer.json file:

```json
{
  "require": {
    "composer/installers": "^1 || ^2"
  },
  "extra": {
    "installer-paths": {
      "web/private/scripts/quicksilver": ["type:quicksilver-script"]
    }
  }
}
```

The project can be included by using the command:

`composer require pantheon-quicksilver/wp-network-search-replace`

### Example `pantheon.yml`

```yaml
api_version: 1

workflows:
  # Clone Database
  clone_database:
    after:
      - description: "WP Network Search and Replace"
        script: private/scripts/quicksilver/wp-network-search-replace/wp-network-search-replace.php
        type: webphp
```
