# queue processing example

## Setup and Run

1.  Clone the repository
2.  Copy environment file `cp .env.example .env`
3.  Configure `.env` file set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` for MySQL database
4.  Run `composer install` to install dependencies
5.  Run `php artisan migrate:fresh --seed` to set up the database.
6.  Run `php artisan queue:work --tries=3 --timeout=10` to start the queue worker.
7.  (Optional) Run `php artisan horizon` to start Horizon for monitoring.
8.  Run queue worker `php artisan queue:work`

## Running Tests

* Run `php artisan test --unit` to execute the unit tests.

## Issues and Handling

* **Installing Redis:** You may need to install Redis server `sudo apt install redis-server`

* **Random Failures:** The payment simulation uses `(rand() % 2)` to randomly mark orders as failed. This makes it challenging to write deterministic tests.
    * **Handling:** Implemented retry logic using `$this->release(10)` in the job.
* **Queue Driver:** The database queue driver is Redis.