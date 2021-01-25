# LetsPlanOrg
This repository is used for developing the LetsPlan.org application.

# Dev Environment Setup
Copy `.env.example` to `.env`

Change the web port from `7780` to something unsused if you have a port conflict.

Change the MailHog web port from `8725` to something unsused if you have a port conflict.

run `./vendor/bin/sail up -d`

run `./vendor/bin/sail artisan migrate`

run `./vendor/bin/sail yarn install`

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
