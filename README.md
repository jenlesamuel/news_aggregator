
# News Aggregator

News Aggregator scrapes news from NewsAPI, The New York Times, and The Guardian and displays them in a clean,
easy-to-read format. The frontend is built using React, and the backend is developed with Laravel. The application uses JWT authentication and is fully dockerized for easy setup and deployment.

## Features

- **Frontend**: React.js
- **Backend**: Laravel
- **Authentication**: JWT Authentication
- **News Sources**: NewsAPI.org, The New York Times, The Guardian API
- **Dockerized**: The application uses Docker for containerization

## Prerequisites

Make sure you have the following installed on your machine:

- **Docker**: [Install Docker](https://docs.docker.com/get-docker/)
- **Docker Compose**: [Install Docker Compose](https://docs.docker.com/compose/install/)

## Project Setup

### 1. Clone the Repository

```bash
git clone https://github.com/jenlesamuel/news_aggregator.git
cd news_aggregator
```

### 2. Copy `.env.example` to `.env`

```bash
cp backend/.env.example backend/.env
```
The `.env.example` file contains all the environment variables that can be used to run the application. Some of these values are secrets, but they are included in this repo solely for the sake of this demo project.


### 3. Build and Start the Docker Containers

Make sure Docker is running, then build and start the containers using Docker Compose:

```bash
docker-compose up --build
```

This will set up the following docker services:

- **backend** (Laravel): Accessible at `http://localhost:9000`
- **frontend** (React): Accessible at `http://localhost:3000`
- **db** (MySQL): Accessible at `localhost:3306`
- **webserver** (Nginx): Accessible at `http://localhost:8000`

### 4. Install Laravel Dependencies

After the containers are up, run the Laravel migrations to set up the database schema.

```bash
docker-compose exec backend composer install
```

### 5. Run Migrations

After the containers are up, run the Laravel migrations to set up the database schema.

```bash
docker-compose exec backend php artisan migrate
```

### 6. JWT Secret Generation (Optional)

The `.env.example` file already contains a JWT secret which would suffice to run the application. However, you can generate a new JWT secret for the Laravel application by running the command below:

```bash
docker-compose exec backend php artisan jwt:secret
```

### 7. Accessing the Application
Access the application at : `http://localhost:3000`

### 8. Populate News Articles by Running The Laravel Scheduler 

The `backend` service already has cron configured inside its container and a cron entry to run Laravel's scheduled tasks to scrape news. However, to run the Laravel scheduler manually, run the command below: 

```bash
docker-compose exec backend php artisan schedule:run
```


### 9. Stopping the Application

To stop all running containers:

```bash
docker-compose down
```

## Troubleshooting

- **Database Issues**: Ensure that the MySQL service is running, and the `.env` database credentials match the ones in your `docker-compose.yml`file. The file is located at the root of the project folder.


