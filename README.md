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

## Installation

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
