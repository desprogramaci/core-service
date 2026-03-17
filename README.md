📚 API de Gestión de Biblioteca
API REST para la administración de usuarios, libros y préstamos dentro de una biblioteca.
Desarrollada con Symfony 6, API Platform, Doctrine ORM y PostgreSQL, ejecutándose completamente en Docker.

🚀 Funcionalidad
La API permite gestionar:

👤 Usuarios
-Registrar nuevos usuarios
-Consultar usuarios existentes
-Obtener un usuario por ID
-Eliminar usuarios

📘 Libros
-Registrar libros
-Consultar libros
-Obtener un libro por ID
-Eliminar libros

🔄 Préstamos
-Crear préstamos asociados a un usuario y un libro
-Aplicar reglas de negocio:
-Máximo 3 préstamos activos por usuario
-La fecha debe recibirse en formato YYYY-MM-DD

📊 Estadísticas
Consultar número de préstamos por usuario en un rango de fechas
-Endpoint: /estadisticas/prestamos?desde=YYYY-MM-DD&hasta=YYYY-MM-DD

Implementado mediante un proveedor personalizado

🛠️ Tecnologías utilizadas
-PHP 8.2
-Symfony 6
-API Platform
-Doctrine ORM
-PostgreSQL
-Docker & Docker Compose

PHPUnit (pruebas)

▶️ Puesta en marcha
📌 Requisitos previos
-Docker
-Docker Compose

📥 Instalación
-Clonar el repositorio
bash
git clone <URL_DEL_REPOSITORIO>
cd core-service

-Construir y levantar los contenedores
bash
docker compose up -d --build

-Instalar dependencias
bash
docker exec -it core-service-php-1 composer install

-Crear base de datos y ejecutar migraciones
bash
docker exec -it core-service-php-1 php bin/console doctrine:database:create
docker exec -it core-service-php-1 php bin/console doctrine:migrations:migrate --no-interaction

🔗 Endpoints principales
👤 Usuarios
Método	  Endpoint	      Descripción
-GET	    /usuarios	      Listar usuarios
-POST	    /usuarios	      Crear usuario
-GET	    /usuarios/{id}	Obtener usuario
-DELETE	  /usuarios/{id}	Eliminar usuario

📘 Libros
Método	  Endpoint	    Descripción
-GET	    /libros	      Listar libros
-POST	    /libros	      Crear libro
-GET	    /libros/{id}	Obtener libro
-DELETE	  /libros/{id}	Eliminar libro

🔄 Préstamos
Método	Endpoint	  Descripción
-POST	  /prestamos	Crear préstamo

Reglas aplicadas:
Máximo 3 préstamos activos por usuario
Fecha en formato YYYY-MM-DD

📊 Estadísticas
Método	Endpoint	Descripción
-GET	/estadisticas/prestamos?desde=YYYY-MM-DD&hasta=YYYY-MM-DD	Préstamos por usuario

🧪 Ejecución de pruebas
Para ejecutar las pruebas en un entorno limpio:

bash
-docker exec -it core-service-php-1 sh -c "
  cd /var/www/html/api &&
  APP_ENV=test APP_DEBUG=1 php bin/console doctrine:database:drop --force &&
  APP_ENV=test APP_DEBUG=1 php bin/console doctrine:database:create &&
  APP_ENV=test APP_DEBUG=1 php bin/console doctrine:migrations:migrate --no-interaction &&
  APP_ENV=test APP_DEBUG=1 vendor/bin/phpunit
"

📁 Estructura del proyecto
Código
src/                Código fuente de la aplicación
migrations/         Migraciones de Doctrine
tests/              Pruebas funcionales
docker-compose.yml  Definición de contenedores
README.md           Documentación del proyecto

📌 Reglas de negocio
-Un usuario no puede tener más de 3 préstamos activos
-Las fechas de préstamo se almacenan como tipo DATE
-Los DNI son únicos
-Un préstamo requiere un usuario y libro válidos

📄 Documentación automática
API Platform genera documentación interactiva en:
👉 /docs

🧪 Ejemplos de solicitudes cURL
Crear un usuario
bash
curl -X POST http://localhost:8081/usuarios \
-H "Content-Type: application/json" \
-d '{"nombre":"Juan","apellidos":"Pérez","dni":"12345678A"}'

Listar usuarios
bash
curl -X GET http://localhost:8081/usuarios -H "Accept: application/ld+json"

Obtener usuario por ID
bash
curl -X GET http://localhost:8081/usuarios/1 -H "Accept: application/ld+json"

Crear un libro
bash
curl -X POST http://localhost:8081/libros \
-H "Content-Type: application/json" \
-d '{"titulo":"El Quijote","autor":"Miguel de Cervantes"}'

Crear un préstamo
bash
curl -X POST http://localhost:8081/prestamos \
-H "Content-Type: application/json" \
-d '{"usuario":"/usuarios/1","libro":"/libros/1","fechaPrestamo":"2024-05-10"}'

Consultar estadísticas
bash
curl -X GET "http://localhost:8081/estadisticas/prestamos?desde=2024-01-01&hasta=2024-12-31" \
-H "Accept: application/json"

✍️ Autor
Desarrollado por: Yonti Testa
