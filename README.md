# Parking Lot Manager API

> REST API Application for managing a Parking Lot using Laravel. 

----------


## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone https://github.com/bjulliana/parking-lot.git

Switch to the repo folder

    cd parking-lot

Install all the dependencies using composer and npm

    composer install && npm install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Run the database seeder to create the Parking Lot spaces

    php artisan db:seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains all the api controllers
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php and web.php file
- `resources/views` - Contains the views files served by the application 
- `resources/sass` - Contains the stylesheet files for the application 


----------

# Testing API

Run the laravel development server

    php artisan serve

The api can now be tested using postman at

    http://localhost:8000/

Routes

| **Method**    | **URI**              	                | **Parameter**     | **Description** |
|---------------|---------------------------------------|-------------------|---------------|
| GET      	    | api/all      	                        | - 	            |Get all tickets.                |
| POST          | api/tickets 	                        | -   	            |Create new ticket and return the ticket number.               |
| GET 	        | api/tickets/{TICKET_NUMBER}           | -      	        |Get the ticket with this number informing the total the customer owes.               |
| POST      	| api/payments/{TICKET_NUMBER}      	| card `number` `required` 	            |Make a payment for the ticket with the ticket number and a credit card number.                |

