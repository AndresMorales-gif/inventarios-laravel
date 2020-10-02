Proyecto inventarios con laravel

Para arrancar la aplicacion siga los siguientes pasos.

1. Clone el repositorio.
2. configure su archivo .env, use el archivo .env.example como referencia.
3. Configure los datos de la base de datos que va a usar en el archivo .env.
4. Ejecute el comando composer install --no-dev
5. Ejecute el comando npm install
6. Ejecute el comando npm run production
7. Ejecute el comando php artisan migrate. Esto migrara las tablas a la base de datos
8. Para iniciar un servidor y arracar la aplicacion, desde la consola ubiquese en la carpeta public y corra el comando php -S localhost:8000


Modo de uso de la app.

Para usar la app debes crear una cuenta segun tu funcion.
La cuenta de admin es la que se encarga de establecer los productos que estaran en venta, si es la primera vez que inicia la aplicacion, es necesario crear productos con esta cuenta.
La cuenta de proveedor indica el numero de lote del producto, producto y fecha de vencimiento asi como su cantidad para el inventario.
El cliente añade productos segun lote para comprar, al final recibira una factura en pdf, el cliente puede ver el inventario para conocer los productos disponibles.