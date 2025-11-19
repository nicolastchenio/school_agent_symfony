# Project Overview

This is a Symfony web application that appears to be a school management system. It's built with PHP 8.2 and Symfony 7.3, using Doctrine for the database layer and Twig for templating. The application manages users (students), agents (teachers or advisors), school levels, subjects, conversations, and messages.

## Key Technologies

*   **Backend:** PHP 8.2, Symfony 7.3
*   **Database:** Doctrine ORM
*   **Templating:** Twig
*   **Dependency Management:** Composer

# Building and Running

## Running the Application

To run the application in a development environment, you can use the Symfony CLI:

```bash
symfony server:start
```

## Running Tests

To run the test suite, you can use the following command:

```bash
php bin/phpunit
```

# Development Conventions

## Routing

The application uses attribute-based routing. Routes are defined as attributes on controller classes in the `src/Controller/` directory.

## Coding Style

The project follows the PSR-12 coding style, which is standard for Symfony applications.

## Database

The application uses Doctrine ORM for database interactions. Database migrations are managed with the `doctrine/doctrine-migrations-bundle`. To run migrations, use the following command:

```bash
php bin/console doctrine:migrations:migrate
```
