# E-Learning Platform

Welcome to the E-Learning Platform, an online learning platform where instructors can upload courses, and students can enroll and learn. This platform is designed to provide a comprehensive learning experience for both instructors and students.

## Table of Contents

- [Features](#features)
- [Technical Requirements](#technical-requirements)
- [Installation](#installation)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Features

### User Registration and Authentication

- Instructors and students can create accounts.
- Secure user authentication to protect user data.

### Course Creation and Management

- Instructors can easily create, upload, and manage courses.
- Create, read, update, and delete (CRUD) functionality for course management.

### Enrollment, Learning Path, and Progress Tracking

- Students can enroll in courses.
- Track your learning progress as you complete course materials.
- Efficiently manage your learning path.

### Technical Requirements

- Symfony 6.3 Framework
- Twig for templates
- Symfony Maker Bundle for code generation
- Symfony Forms for user-friendly forms
- Composer for dependency management
- MySQL database for data storage
- Docker Compose for containerization
- Doctrine ORM for database migrations
- Configuration files (config/*.yaml)
- EasyAdmin Bundle for admin CRUD operations
- Codeception acceptance tests for login, registration, adding courses, and deleting courses

## XAMPP Installation SetUp

To run this project successfully, follow these installation instructions:

1. Install PHP 8.1 or higher and the required PHP extensions.
2. Install Composer: [https://getcomposer.org/download/](https://getcomposer.org/download/)
3. Download and install Symfony on your system if you haven't already: [https://symfony.com/download](https://symfony.com/download)
4. Clone this repository: `git clone https://github.com/lexiscode/e-learning-app.git`
5. Make sure your XAMPP Apache server is up and running.
6. Create a `.env.local` file and copy the contents of `.env` into it.
7. Run `composer update` to install and update all necessary packages for the web application.
8. Create the database by running: `symfony console doctrine:database:create`
9. Run the migration to update your database schema: `symfony console doctrine:migrations:migrate`
10. Install npm for assets compilation: `npm install`
11. Compile assets with: `npm run dev`
12. Clear cache files: `symfony console cache:clear`
13. Start the local web server: `symfony server:start -d`
14. Open the project in your web browser by running: `symfony open:local`


These below are for Docker installation instructions:

```markdown
## Docker Test Installation SetUp

If you prefer to use Docker for running the project, follow these steps:

1. In your `.env.local` file, make the following changes:
   - Comment out the XAMPP database URL.
   - Uncomment the Docker database URL configuration.

2. Run the following command to update Composer dependencies (if you've not done it before):

   ```bash
   composer update
   ```

3. Install npm for assets compilation (if you've not done it before):

   ```bash
   npm install
   ```

4. Compile assets using webpack (if you've not done it before):

   ```bash
   npm run dev
   ```

5. Ensure you have Docker Desktop installed and are logged in.

6. Build the Docker image and start the containers using the following command:

   ```bash
   docker-compose up --build
   ```

7. Once the containers are built and running, open another terminal and navigate to the project directory.

8. Get a bash shell inside the MariaDB container:

   ```bash
   docker exec -it <mariadb-container-id> bash
   ```

9. Inside the bash shell, log in to the MariaDB server:

   ```bash
   mariadb -u root -p
   ```

10. Enter "root" as the password when prompted to log in.

11. Exit the bash shell and get a bash shell inside the FPM (PHP) container:

    ```bash
    docker exec -it <fpm-container-id> bash
    ```

12. (Optional) Confirm the existence of your database, either by logging in to phpMyAdmin or running this command (it should give you an error message saying "database exists"):

    ```bash
    php /application/bin/console doctrine:database:create
    ```

13. Run the migration to apply the database schema:

    ```bash
    php /application/bin/console doctrine:migrations:migrate
    ```

14. Access the project using the following URL:

    [http://localhost:1001/](http://localhost:1001/)


## Getting Started

Once the installation is complete and the local web server is running, open your web browser and access the platform at the provided URL. You can now register, log in, and start using the E-Learning Platform.

## Usage

- Instructors can create, manage, and publish courses.
- Students can enroll in courses and track their progress.
- Explore the platform, enroll in courses, and enhance your knowledge.

## Contributing

Contributions to this project are welcome. Please follow the standard best practices for software development. To contribute, fork the repository, make your changes, and submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE). You can freely use, modify, and distribute it.
