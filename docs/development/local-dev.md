# Local development

## Initial setup


- Copy `.env.example` to `.env`
- Change the web port from `7780` to something unsused if you have a port conflict.
- Change the MailHog web port from `8725` to something unsused if you have a port conflict.
- If you have another Postgres running on 5432, add a line to your `.env` like `FORWARD_DB_PORT=5433`
- Composer install with your host computer's PHP/Composer.

    ```bash
    composer install
    ```

    OR, use docker to run initial composer install:

    ```bash
    docker run -ti --rm -v $(pwd):/app -w /app composer:2.0 composer install
    ```

- Bring up containers:

    ```bash
    ./vendor/bin/sail up -d
    ```

- Generate unique app key:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

- Run all database migrations:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

- Build frontend:

    ```bash
    ./vendor/bin/sail yarn install
    ```

## MapBox setup

- Edit the `.env` and add a vector (pbf) source URL to `MBTILE_URL`:

    ```bash
    MBTILE_URL="https://api.maptiler.com/tiles/contours/11/599/770.pbf?key=yd4rAVOD6ZdfBCcbKnIE"
    ```

    !!! tip

        You might change `fill-color` to `fill-outline-color` in `resources/js/Shared/MapboxMap.vue` for this example.

## Importing data

- Import PWD Parcel definitions. A file will be saved into `./storage/app/` and imported into the `parcel` table.
- Run:

    ```bash
    ./vendor/bin/sail artisan lp:import-parcels
    ```

- Copy your alteration permits and new construction permits CSV files into `./storage/app/`
- Run:

    ```bash
    ./vendor/bin/sail artisan lp:import-bldg-permits alteration_permits.csv
    ```

- Run:

    ```bash
    ./vendor/bin/sail artisan lp:import-bldg-permits new_construction_permits.csv
    ```

- Run:

    ```bash
    ./vendor/bin/sail artisan lp:import-rco
    ```

- Import Atlas API data to connect OPA and PWD parcel IDs:

    ```bash
    ./vendor/bin/sail artisan lp:scrape-atlas --key=${your gatekeeper key}
    ```

## Creating a view of just 1 community

```sql
CREATE MATERIALIZED VIEW project_parcels_1
AS
SELECT p.*

 FROM parcel AS p
   INNER JOIN rco AS n
    ON ST_Intersects(ST_GeomFromGeoJSON(p.geo_json), ST_GeomFromGeoJSON(n.geo_json))

WHERE n.object_id = '23641'
```

Extract the parcels for one community and compile them into MapBox tiles:

```bash
./vendor/bin/sail artisan lp:stream-parcel-geo-json project_parcels_2 > project1.geojson

docker run --rm -ti \
  -v $(pwd):/app \
  -w /app \
  strikehawk/tippecanoe \
  tippecanoe -z20 -Z8 -f --name=urban-areas -l urban-areas --output=storage/app/urban_area.mbtiles project1.geojson
```

## Running Commands in Dev Environment

### Artisan / Laravel

You can pass **artisan** commands into the container like this:

```bash
./vendor/bin/sail artisan tinker
```

```bash
./vendor/bin/sail artisan migrate:status
```

### Yarn

You can pass **yarn** commands into the container like this:

```bash
./vendor/bin/sail yarn install
```

```bash
./vendor/bin/sail yarn add pkg
```

### Postgres

You run an interactive **psql** shell like this:

```bash
./vendor/bin/sail psql
```

### PHP / Composer

You run **composer**, **php** and **root-shell** commands:

```bash
./vendor/bin/sail composer require --dev pkg
```

```bash
./vendor/bin/sail php -v
```

```bash
./vendor/bin/sail root-shell
```
