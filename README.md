# Tipkin

Welcome to the tipkin source code.

Please follow the projet here : https://projet.soletic.org/projects/tipkin/wiki/Wiki

## What is Tipkin ?

### Install

* Copy dist/htaccess.txt to web/.htaccess
* Copy dist/database.config.php.dist to apps/database.config.php and setup with your database credentials.
* Create a database and import dist/db/schema.sql
* Import dist/db/seed.sql
* You can also import dist/db/sample-data.sql
* Copy config/app.xml.sample to config/app.xml and change values to fit your needs

### Update database

In case you change database schema, provide the following changes in the repository:

* Create dist/db/migrations/[date]-TOPIC.sql ([date] being the current date in sql format yyyy-mm-dd and topic a short explanation of what you changed)
* Re-export your database schema and replace the apps/dist/db/schema.sql
* Re-export seed data to dist/db/seed.sql
* Re-export sample-data to dist/db/sample-data.sql

### Configuration

The platform is composed of two apps : frontend and backend. Both application have their own configuration file (app/[APP_NAME]/config/app.xml).

### Testing

No automated tests currently implemented, but please try to write some feature tests as close as possible to [Cucumber tests syntax](https://cukes.info/) in  tests/features, to give some directions for manual testing.
If you feel like including real tests, any PR will be warmly welcome !

## Licence

Tipkin is distributed under ... licence

## Contribute

Fork, branch, make changes and submit a pull request.
