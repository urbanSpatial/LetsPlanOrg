# LetsPlanOrg
This repository is used for developing the LetsPlan.org application.

# Dev Environment Setup
Copy `.env.example` to `.env`

Change the web port from `7780` to something unsused if you have a port conflict.

Change the MailHog web port from `8725` to something unsused if you have a port conflict.

If you have another Postgres running on 5432, add a line to your .env like
`FORWARD_DB_PORT=5433`

Composer install with your host computer's PHP/Composer.
`composer install`

OR

Use docker to run initial composer install
`docker run -ti --rm -v $(pwd):/app -w /app composer:2.0 composer install`

run `./vendor/bin/sail up -d`

run `./vendor/bin/sail artisan key:generate`

run `./vendor/bin/sail artisan migrate`

run `./vendor/bin/sail yarn install`

# Importing data

Import PWD Parcel definitions.  A file will be saved into `./storage/app/` and imported into the `parcel` table.

run `./vendor/bin/sail artisan lp:import-parcels`

Copy your alteration permits and new construction permits CSV files into `./storage/app/`

run `./vendor/bin/sail artisan lp:import-bldg-permits alteration_permits.csv`

run `./vendor/bin/sail artisan lp:import-bldg-permits new_construction_permits.csv`

run `./vendor/bin/sail artisan lp:import-rco`

Import Atlas API data to connect OPA and PWD parcel IDs

run `./vendor/bin/sail artisan lp:scrape-atlas --key={your gatekeeper key}`


#Creating a view of just 1 community

```sql
CREATE MATERIALIZED VIEW project_parcels_1
AS
SELECT p.*

 FROM parcel AS p 
   INNER JOIN rco AS n 
    ON ST_Intersects(ST_GeomFromGeoJSON(p.geo_json), ST_GeomFromGeoJSON(n.geo_json))

WHERE n.object_id = '23641'
```


# Running Commands in Dev Environment

### Artisan / Laravel

You can pass **artisan** commands into the container like this:

run `./vendor/bin/sail artisan tinker`

run `./vendor/bin/sail artisan migrate:status`

### Yarn

You can pass **yarn** commands into the container like this:

run `./vendor/bin/sail yarn install`

run `./vendor/bin/sail yarn add pkg`

### Postgres

You run an interactive **psql** shell like this:

run `./vendor/bin/sail psql`


### PHP / Composer

You run **composer**, **php** and **root-shell** commands:

run `./vendor/bin/sail composer require --dev pkg`

run `./vendor/bin/sail php -v`

run `./vendor/bin/sail root-shell`
