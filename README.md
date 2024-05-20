# Book Scanner

A Symfony-based PWA that allows registered users to scan the ISBN from their books.

## Demo



https://commons.wikimedia.org/wiki/File:Hello_(97564)_-_The_Noun_Project.svg

https://gist.github.com/subbe/94ba128e4560b50484eb6aa2556b7559

## Developer Installation

```bash
git clone git@github.com:survos-sites/scanner.git && cd scanner
composer install
bin/console doctrine:schema:update --force --complete
bin/create-admins.sh
symfony server:start -d
symfony open:local
```
