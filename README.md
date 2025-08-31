## What do you NEED to run this application?

1. You need to install XAMPP for MySQL database and php.

2. You need to install Node.js with npm (they come bundled)

3. Setup MySQL database

4. Install all the required packages for Node.js

## How to INSTALL XAMPP?

1. Open XAMPP website - https://www.apachefriends.org/download.html.

2. Download the XAMPP and follow the instructions given.

## How to INSTALL Node.js

1. Open Node.js website - https://nodejs.org/en/download/.

2. Download Node.js and follow the instructions given.

## Setup the environment variable for php and Node.js?

1. Locate all the .exe files of php and Node.js

2. Open the "Edit System Environment Variable"

3. Add new path to the php and Node.js

4. Open terminal and try running these commands:
   php -v
   node -v
   npm -v

If you see the version downloaded for each package, you did everything correct!

If you see something like - "npm/php/node is not defined" - you must had some problems during installation!

p.s. you can watch on YouTube, how to download and setup them

## How to setup MySQL database?

1. Open XAMPP

2. Enable Apache and MySQL by pressing Start

3. Press Admin button for MySQl, which will open the database browser (phpmyadmin).

4. Upload there the database.

5. Check if all the tables were succesfully created with the required columns.

## How to install all required packages for Node.js

1. Open terminal and locate the path for the project

2. You can just try to run the server_chatbot.js with the command:
   node nodejs/server_chatbot.js

3. If it is not running it is probably because you need to install the package for it.

4. Just run:
   npm install package-name

e.g.

npm install express

## How to RUN this application?

1. Download and extract this project into the device (remember the path where you extract).

2. Make sure you running the database before running the files.

3. Locate the path for this project in a terminal (use cd command).

4. Run the php server (frontend) with the following command:
   php -S localhost:8000

(port can be adjusted according to your needs)

5. Open new terminal to run backend Node.js

6. Locate the path for the project and run the following command:
   node nodejs/server_chatbot.js

(adjust the path to the file according to the project)

7. Open in browser localhost:8000 it will open the home page.
