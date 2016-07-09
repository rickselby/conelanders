# Conelanders

Website to handle the Conelanders racing league events and results

## Requirements

Tag-based cache. I'm just using APC, it seems fine for now. Don't use files

## Installation

    composer install
    bower install
    chmod -R 777 storage bootstrap/cache

    cd public/bower/Sortable
    npm install
    node_modules/grunt/bin/grunt jquery:min
    rm -rf node_modules

... set up .env ...

    ./artisan migrate --database=migrate
    ./artisan permissions:update

## Cron

    * * * * * /path/to/artisan schedule:run >> /dev/null 2>&1

## Queue listener

Dirt imports can be slow, so we need a long timeout

    screen
    ./artisan queue:listen --timeout=600
