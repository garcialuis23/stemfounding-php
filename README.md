# Proyecto stemfounding-php

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

- PHP >= 8.2
- Composer
- Laravel >= 11.x
- SQLite o MySQL
- Git

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/garcialuis23/stemfounding-php.git
   cd stemfounding-php
   ```

2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```

3. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configura la base de datos en el archivo `.env` y luego ejecuta las migraciones:
   ```bash
   php artisan migrate:refresh --seed
   ```
   **Nota:** `migrate:refresh` recreará todas las tablas y ejecutará los seeders para datos de prueba.

5. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

🎉 **¡Listo!** Visita `http://localhost:8000` para ver la aplicación.

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

## 📄 Licencia

Este proyecto está licenciado bajo la **Licencia MIT**. Consulta el archivo [LICENSE](LICENSE) para más detalles.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Contacto

**Luis García**  
📧 Email: [garciadiazluis23@gmail.com](mailto:garciadiazluis23@gmail.com)  
🔗 GitHub: [@garcialuis23](https://github.com/garcialuis23)  
🌐 Proyecto: [STEMFounding](https://github.com/garcialuis23/stemfounding-php)

---

## 🙏 Agradecimientos

- **[Laravel](https://laravel.com)** - Framework PHP excepcional
- **[Bootstrap](https://getbootstrap.com)** - Framework CSS para interfaces responsivas
- **[Laravel Sanctum](https://laravel.com/docs/sanctum)** - Sistema de autenticación para APIs
- **Comunidad de Laravel** - Por el excelente soporte y documentación

---

<div align="center">
  <strong>🎓 Desarrollado con ❤️ por Luis García para el Proyecto Final 2DAW</strong><br>
  <em>Plataforma de Crowdfunding STEMFounding</em>
</div>

---

*Este proyecto fue creado para un proyecto para mi escuela STEM.*
