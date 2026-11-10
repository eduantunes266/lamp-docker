# LAMP Stack with Docker Compose

PHP application (guestbook) running in two containers:

- **web**: Apache + PHP 8.3 (image built from the `Dockerfile`), exposed at http://localhost:8080
- **db**: MariaDB 11, only reachable through the internal `backend` network, with its data in the `db_data` volume

## Requirements

- Docker Desktop (includes Docker Compose)

## Getting started

```
cp .env.example .env      # only if .env does not exist; then change the passwords
docker compose up -d --build
```

Open http://localhost:8080

## Useful commands

| Goal | Command |
| --- | --- |
| Check the status of the services | `docker compose ps` |
| View the logs | `docker compose logs -f` |
| Stop (keeps the data) | `docker compose down` |
| Stop and delete the data | `docker compose down -v` |
| Inspect the internal network | `docker network inspect lamp-docker_backend` |
| Inspect the volume | `docker volume inspect lamp-docker_db_data` |

## Structure

```
docker-compose.yml   services, volume and network
Dockerfile           PHP + Apache with pdo_mysql
.env / .env.example  passwords and names
app/                 PHP code (bind mount)
db/init.sql          creates the table on the first start
docs/screenshots/    test evidence
```
