# Twitter by MarosAware

Project of simple twitter similar application.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You will need local environment with configured:

- PHP
- Apache/Nginx
- MySQL

### Installing

All you have to do is follow this five simple steps:

1. Clone or download this project.

2. Put it on folder where you run all of your local project. For example, if you using XAMPP put it on: "C:\xampp\htdocs".

3. Next, you need to create database for this application, there is two way of doing it:

- phpmyadmin

- linux bash shell

3.1 phpmyadmin: 

You simply login to your phpmyadmin dashboard on your local environment, usually: localhost/phpmyadmin.

Then, you create new Database, if you want to immediately run this project, name your new database "Twitter".

Next, all you have to do is to click tab "import" and import "Twitter.sql" file from this project. You will find this file inside src/SQL directory.

Now, you are ready to go :) Go to step 4 of the installation guide.

3.2 linux bash shell:

Open your bash console and type this command:

mysql -h localhost -u your_username -p

You can use localhost or 127.0.0.1 or other route that you configured earlier on your local environment.
your_username refers to your username (usually root) used to login mysql.
Next you need to create new database and then use it by typing:

CREATE DATABASE IF NOT EXISTS Twitter;
use Twitter;

Then, you need to import "Twitter.sql" file from src/SQL. To do this, you need to know path to this file on your local machine. Then you type:

source your/path/to/file/Twitter.sql;

For example:

source home/yourusername/yourworkspace/Twitter/src/SQL/Twitter.sql;

Then, check if database exists and has appropriate tables by typing:

show databases;
use Twitter;
show tables;

If you see 4 tables: 
- Comments
- Messages
- Tweets
- Users

You are good to go :) Check next step.

4. Open file: Twitter/src/Database.php and change following properties according to your MySQL data and save it:

$username = 'root',
$password = 'yourPassword',
$host = 'localhost',
$database = 'Twitter'

5. Run browser and type route to this project for example: http://localhost/Twitter/public/
You can login to this application by using:

email: testuser@gmail.com
password: 123

Or you can sign up using your own email and password :)

Done!

## Running and test

You can test and enjoy functionality, by making tweets, sending messages to other users and comment your, and other tweets.
If you want to, on profile page you can change your username, password, email and delete your account.

## Built With

- PHP OOP
- Simple bootstrap and styles

## Purpose

This project was created as a workshop in a programming school that I'm a participant in.

## Authors

**MarosAware**

