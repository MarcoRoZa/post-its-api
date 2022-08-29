# Post Its API

## Instalación

Ejecute los siguiente comandos para instalar el proyecto:

```
git clone https://github.com/MarcoRoZa/post-its-api.git post-its
cd post-its
composer install
cp .env.example .env
php artisan key:generate
```

Cree una base de datos en MySQL llamada <b>post-its</b> usando: <b>character set: utf8mb4</b>, <b>collation: utf8mb4_unicode_ci</b>, y configure los valores en el archivo <em>.env</em>:

```
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```


Configure los valores en el archivo <em>.env</em> con las credenciales de su servidor de correo:

```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_NAME=
MAIL_FROM_ADDRESS=
```

### Ejecutando pruebas

Ejecute los siguiente comandos (Es requerido tener instalado el módulo <em>php-sqlite3</em>):

```
php artisan test
```

### Ejecutando localmente

Ejecute los siguiente comandos:

```
php artisan migrate
php artisan db:seed
php artisan l5-swagger:generate
php artisan serve
```

Ejecute en paralelo el siguiente comando para atender las notificacions en cola (O podría configurar un <a href="https://laravel.com/docs/7.x/queues#supervisor-configuration"> supervisor</a>):

```
php artisan queue:work
```

Abra en su navegador el siguiente enlace: <a href="http://post-its.localhost:8000/api/documentation">http://post-its.localhost:8000/api/documentation </a>
