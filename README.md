# DaFFy, the Dockerfile Factory

## Development

To start development services just run:
```
docker-compose up -d
```
It will run two services:
1) Apache server with the DaFFy application in development environment
2) webpack in watch mode

### Notes
To install additional libraries run `docker-compose exec assets bash -c "yarn add -D {package name}"`.

To run composer install (it is required after building containers) run `./dev/composer-install.sh`.

Apache is running as daffy user. To enter into container as *daffy* run `./dev/enter.sh`.

## Project building

To build project php container with the application run `docker build --no-cache -t {my_daffy_image_name}:{version}`
