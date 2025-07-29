[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/9Li3lVq0)

# Proyecto Final 2DAW - LuisGarciaSTEM

Este es el proyecto final para el curso de 2DAW, desarrollado por Luis Garcia. El proyecto es una plataforma de crowdfunding.

## Características

- **Registro y autenticación de usuarios**: Los usuarios pueden registrarse y autenticarse en la plataforma.
- **Creación y gestión de proyectos**: Los emprendedores pueden crear y gestionar sus proyectos.
- **Visualización de proyectos**: Los usuarios no autenticados pueden ver todos los proyectos disponibles.
- **Aceptación o rechazo de proyectos**: Los administradores pueden aceptar o rechazar proyectos.
- **Limitación de proyectos activos**: Los emprendedores pueden tener un máximo de 2 proyectos activos.
- **Visualización de detalles del proyecto**: Incluye el estado del proyecto, el creador, y las inversiones actuales.
- **Inversiones**: Los inversores pueden invertir en proyectos y ver sus inversiones.
- **Comentarios**: Los usuarios pueden añadir, editar y eliminar comentarios en los proyectos.
- **Desactivación automática de proyectos**: Los proyectos se desactivan automáticamente al alcanzar el máximo de financiación o cuando expira la fecha límite sin alcanzar el mínimo.
- **Validación de IBAN**: Validación estricta de IBAN según países permitidos.
- **Paginación**: Paginación de proyectos para una mejor visualización.

## Requisitos

- PHP >= 7.3
- Composer
- Laravel >= 8.x
- MySQL
- Node.js y npm (para el front-end)

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/proyecto-final-2daw-LuisGarciaSTEM.git
   cd proyecto-final-2daw-LuisGarciaSTEM
   ```

2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```

3. Instala las dependencias de Node.js:
   ```bash
   npm install
   ```

4. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configura la base de datos en el archivo `.env` y luego ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

6. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   npm run dev
   ```

## Uso

- **Registro y autenticación**: Regístrate como usuario y autentícate.
- **Creación de proyectos**: Si eres un emprendedor, puedes crear nuevos proyectos desde tu perfil.
- **Gestión de proyectos**: Los administradores pueden aceptar o rechazar proyectos desde la página de administración.
- **Visualización de proyectos**: Los usuarios no autenticados pueden ver todos los proyectos disponibles.
- **Inversiones**: Los inversores pueden invertir en proyectos y ver sus inversiones.
- **Comentarios**: Los usuarios pueden añadir, editar y eliminar comentarios en los proyectos.

## API Endpoints

- **GET /api/projects**: Obtener todos los proyectos.
- **GET /api/projects/{id}**: Obtener un proyecto por ID.
- **POST /api/projects**: Crear un nuevo proyecto.
- **PUT /api/projects/{id}**: Actualizar un proyecto.
- **PUT /api/projects/{id}/inactivate**: Inactivar un proyecto.
- **GET /api/projects/{id}/investments**: Obtener las inversiones de un proyecto por su ID.
- **POST /api/projects/{id}/comments**: Añadir un comentario a un proyecto.
- **PUT /api/projects/{id}/comments/{index}**: Actualizar un comentario por su índice.
- **DELETE /api/projects/{id}/comments/{index}**: Eliminar un comentario por su índice.
- **GET /api/users/{id}**: Obtener los datos de un usuario por su ID.

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

## Contribuciones

Las contribuciones son bienvenidas. Por favor, abre un issue o envía un pull request para discutir cualquier cambio que desees realizar.

## Contacto

Para cualquier consulta, puedes contactarme a través de [mi correo electrónico](mailto:tu-email@example.com).
