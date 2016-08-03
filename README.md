# Conelanders

Website to handle the Conelanders racing league events and results

## Requirements

Tag-based cache that's NOT apc. APC can't be altered from the command line, so the dirt rally
 import scripts can't actually clear the cache. On redis now...

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

## Assetto Corsa server management

1. Add a user for running the assetto corsa server, e.g. `assettocorsa`
2. Install the AC server as this user - https://b.joaoubaldo.com/installing-assetto-corsa-dedicated-server-in-linux-post/
3. Get the linux script to control AC - https://github.com/p3t3c/AssettoCorsaLinuxScripts  
   Put it somewhere sensible - like /home/assettocorsa/
4. Add an entry to sudoers (perhaps as a file in /etc/sudoers.d/?) to allow the web user to run the control script without prompting for a password:  
   `www-data ALL = (assettocorsa) NOPASSWD: /home/assettocorsa/server.sh`
5. Set up the path to `server.sh` and the config files in `.env`

I think that was it? Hope so.