# How to run the project

To get started, clone the repository and initialize the environment with:
```make init```.
All dependencies and containers will be set up automatically.

## Access to PHP Bash
You can use a ```make bash``` command.
To exit interactive mode you need to send keyboard sequence:
<kbd>ctrl</kbd> + <kbd>p</kbd> and after <kbd>ctrl</kbd> + <kbd>q</kbd>.

## Useful commands

#### Run environment
You can use a ```make up``` or ```make stop``` command to run or stop containers.

#### Laravel Pint
Type a ```make pint``` to run Laravel Pint

#### Larastan
Type a ```make larastan``` to run Larastan

#### PHPUnit
Type a ```make test``` to run PHPUnit tests

## Basic urls
```
localhost/docs/api - endpoint documentation
localhost:8080 - phpmyadmin
localhost:8025 - mailhog
```
User CRUD endpoints:
- GET    localhost/api/user           - List users
- POST   localhost/api/user           - Create user
- GET    localhost/api/user/{user}    - Get user details
- PUT    localhost/api/user/{user}    - Update user
- DELETE localhost/api/user/{user}    - Delete user

## DB Connection
```
Connection:
localhost:3306

MYSQL:
      username: root
      password: example
      MYSQL_ROOT_PASSWORD: example
      MYSQL_DATABASE: api
```
