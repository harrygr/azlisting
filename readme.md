# A-Z Programme Listing

Lists BBC programmes by their letter

## Installation (using composer)

- Clone the repository
- run `composer install` to install the dependencies
- copy `.env.example` to `.env` and populate it with your environment's settings
- serve it up

You can run the tests simply by running `phpunit` from the project root.

## How it works

The front end is powered by Vue. The Vue component handles the sending an receiving of http requests and responses and renders the resulting content.

Clicking a letter button on the front page sends a request to an internal api which uses service classes to perform the external api request and parse the result into Eloquent models for consistancy in the application. The view component then renders the json representation of these models.

In addition the decorator pattern has been used to add a caching layer on top of the BBC api client. This prevents querying the same external url many times as well as remembering the actual parsing of the result significantly speeding up the app. Many cache drivers can be used including memcached, redis etc as set in the .env config.