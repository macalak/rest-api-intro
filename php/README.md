* Create podman network: `podman network create my-api-net`
* Run HTTP server: `MSYS_NO_PATHCONV=1 podman run -d --name my-php-server --network my-api-net -p 8000:8000 -v "$(pwd)":/usr/src/myapp -w /usr/src/myapp php:8.3-cli php -S 0.0.0.0:8000`
* Run test: `MSYS_NO_PATHCONV=1 podman run --rm --network my-api-net -v "$(pwd)":/usr/src/myapp -w /usr/src/myapp php:8.3-cli php test.php`