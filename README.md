# Broken Blades and Falling Leaves

Broken Blades and Falling Leaves (Also called Broken Blades) is an online multiplayer game run via an API written with PHP using Symfony. Interaction with the API and game is handled via Vue.

## Release Information

This game has not been released for public use yet, as it is still a heavy work in progress. Once it is released, the link to the site to play will be shown here.

## Installation

This game requires the following installed on your computer if you wish to run it locally:
- Git
- PHP 7
- MySQL
- Composer

The following tutorial assumes knowledge of the above tools.

### Steps

- Download the project via:
```
git clone https://github.com/Alex-Design/BrokenBlades.git
```
- Create a database in MySQL called BrokenBlades, and leave it empty.
- Note down your MySQL username and password.
- cd into the project via the command line and then run:
```
composer install
```
- Once prompted, put the following information in:
```
database_host: 127.0.0.1
    database_port: null
    database_name: BrokenBlades
    database_user: [YourDatabaseUsernameHere]
    database_password: [YourDatabasePasswordHere]
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    secret: [MashTheKeyboardToGenerateARandomStringHere]
```
- If installation is successful at this point, you should see a prompt in green.
- Synchronize the database with the repositories defined in the code by typing:
```
php bin/console doctrine:schema:update --force
```
- Run the game's php server by typing in:
```
php bin/console server:start localhost:8001
```
- If you get an error at this stage, please check out the following link: [Installing pcntl](https://stackoverflow.com/questions/33036773/how-to-enable-pcntl-in-php-while-using-a-framework-like-symfony2)
- Visit localhost:8001 in your browser.

## Quick Start

- To view the game mechanics quickly, enter the following command in your console:
```
php bin/console manage:initialize-project
```
- This will create an account and some locations. Next, visit the following url on your localhost to log in with the test account's details - (user: test-account) (pass: test-account)
```
localhost:8001/login
```
- Once logged in, visit the following url:
```
localhost:8001/game
```
- Please keep in mind that all you see is a work in progress and may not represent the final result.

### Vue Gameplay

The Vue side of the project is still a heavy work in progress, but can be viewed if you visit /game. If you get errors at this stage, it may be due to a bug or hardcoded value (used in testing with my database) - in which case, please feel free to submit a pull request to fix the bug, or change the hardcoded value(s) in src/GameBundle/Handler/APIHandler.php to fit your database :)

## Contributing

* Backend: src/GameBundle/Handler/APIHandler.php
(and anything within src/)
* Frontend: web/GameFrontend/gameFrontend.html
(and anything within web/)

Contributions to the project would be greatly appreciated. However, the initial stages will be tightly controlled (the lore and gameplay need to be established) and only after the game is live, will it be easy to contribute. At that point, there will be a contribution guide including tutorials on how to make new parts of the game such as dungeons, quests, puzzles or even features. In the meantime, please watch and/or star this project and return to it when it is ready for major contributions! Thank you :)

## Reporting Issues

If there is a problem with the project or any part of the code, including this readme, please submit an issue or pull request and I'll have a look at it.
