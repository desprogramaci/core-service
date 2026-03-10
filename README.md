API de Gestión de Biblioteca
Este proyecto implementa una API REST para gestionar usuarios, libros y préstamos dentro de una biblioteca. Está desarrollada con Symfony 6, API Platform, Doctrine ORM y PostgreSQL, y se ejecuta mediante Docker.

Funcionalidad
La API permite:
Registrar y consultar usuarios.
Registrar y consultar libros.
Crear préstamos asociados a un usuario y un libro.
Aplicar reglas de negocio (máximo de 3 préstamos activos por usuario).
Consultar estadísticas agregadas mediante un proveedor personalizado.

Tecnologías

PHP 8.2
Symfony 6
API Platform
Doctrine ORM
PostgreSQL
Docker / Docker Compose

PHPUnit

Puesta en marcha
Requisitos previos:

Docker y Docker Compose instalados.

Instalación
Clonar el repositorio:
git clone <URL_DEL_REPOSITORIO>
cd core-service

Construir y levantar los contenedores:
docker compose up -d --build

Instalar dependencias:
docker exec -it core-service-php-1 composer install

Crear la base de datos y ejecutar migraciones:
docker exec -it core-service-php-1 php bin/console doctrine:database:create
docker exec -it core-service-php-1 php bin/console doctrine:migrations:migrate --no-interaction

Endpoints principales

Usuarios

GET /usuarios
POST /usuarios
GET /usuarios/{id}
DELETE /usuarios/{id}

Libros

GET /libros
POST /libros
GET /libros/{id}
DELETE /libros/{id}

Préstamos

POST /prestamos
Reglas aplicadas:

Máximo 3 préstamos activos por usuario.

La fecha debe recibirse en formato Y-m-d.

Estadísticas

GET /estadisticas/prestamos?desde=YYYY-MM-DD&hasta=YYYY-MM-DD
Devuelve el número de préstamos por usuario mediante un provider personalizado.

Ejecución de tests
El proyecto incluye una suite de tests funcionales. Para ejecutarlos en un entorno limpio:
docker exec -it core-service-php-1 sh -c "cd /var/www/html/api && APP_ENV=test APP_DEBUG=1 php bin/console doctrine:database:drop --force && APP_ENV=test APP_DEBUG=1 php bin/console doctrine:database:create && APP_ENV=test APP_DEBUG=1 php bin/console doctrine:migrations:migrate --no-interaction && APP_ENV=test APP_DEBUG=1 vendor/bin/phpunit"

Estructura del proyecto

src/ — Código fuente de la aplicación
migrations/ — Migraciones de Doctrine
tests/ — Tests funcionales
docker-compose.yml — Definición de contenedores
README.md — Documentación del proyecto

Reglas de negocio
Un usuario no puede tener más de tres préstamos activos.
Las fechas de préstamo se almacenan como tipo DATE.
Los DNI de los usuarios son únicos.
Un préstamo requiere usuario y libro válidos.

Notas adicionales
La API expone documentación automática mediante API Platform en /docs.
El proyecto está preparado para ejecutarse íntegramente dentro de Docker.
Ejemplos de peticiones cURL

Crear un usuario
curl -X POST http://localhost:8081/usuarios -H "Content-Type: application/json" -d '{"nombre":"Juan","apellidos":"Pérez","dni":"12345678A"}'

Listar usuarios
curl -X GET http://localhost:8081/usuarios -H "Accept: application/ld+json"

Obtener un usuario por ID
curl -X GET http://localhost:8081/usuarios/1 -H "Accept: application/ld+json"

Crear un libro
curl -X POST http://localhost:8081/libros -H "Content-Type: application/json" -d '{"titulo":"El Quijote","autor":"Miguel de Cervantes"}'

Listar libros
curl -X GET http://localhost:8081/libros -H "Accept: application/ld+json"

Crear un préstamo
curl -X POST http://localhost:8081/prestamos -H "Content-Type: application/json" -d '{"usuario":"/usuarios/1","libro":"/libros/1","fechaPrestamo":"2024-05-10"}'

Consultar un préstamo
curl -X GET http://localhost:8081/prestamos/1 -H "Accept: application/ld+json"

Consultar estadísticas de préstamos por usuario
curl -X GET "http://localhost:8081/estadisticas/prestamos?desde=2024-01-01&hasta=2024-12-31" -H "Accept: application/json"

Eliminar un usuario
curl -X DELETE http://localhost:8081/usuarios/1

Eliminar un libro
curl -X DELETE http://localhost:8081/libros/1