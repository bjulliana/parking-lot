# Parking Lot Manager API

> REST API Application for managing a Parking Lot using Laravel. 

----------


## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)


Clone the repository

    git clone https://github.com/bjulliana/parking-lot.git

Switch to the repo folder

    cd parking-lot
    
Build the image data    
    
    docker-compose build

Start up the containers

    docker-compose up -d
    
Install all the dependencies using composer and npm

    docker-compose run --rm composer install && npm install

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    docker-compose exec php php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    docker-compose exec php php artisan migrate

Run the database seeder to create the Parking Lot spaces

    docker-compose exec php php artisan db:seed

You can now access the server at http://localhost:8080

----------

# Testing API

The api can now be tested using postman at

    http://localhost:8080/

Routes

| **Method**    | **URI**              	                | **Parameter**     | **Description** |
|---------------|---------------------------------------|-------------------|---------------|
| GET      	    | api/all      	                        | - 	            |Get all tickets.                |
| POST          | api/tickets 	                        | -   	            |Create new ticket and return the ticket number.               |
| GET 	        | api/tickets/{TICKET_NUMBER}           | -      	        |Get the ticket with this number informing the total the customer owes.               |
| POST      	| api/payments/{TICKET_NUMBER}      	| card `number` `required` 	            |Make a payment for the ticket with the ticket number and a credit card number.                |
| POST      	| api/search                        	| str `string` 	            |Run a query search for a ticket number containing the search string.                |

