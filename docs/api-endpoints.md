# Documentación de Endpoints - Bautista Iglesia API

## Resumen

Esta API proporciona endpoints CRUD completos para todos los recursos de la aplicación. Todos los endpoints (excepto autenticación) requieren autenticación con token Bearer.

## Estructura de Respuestas

### Respuestas Exitosas

#### Para INDEX, SHOW, STORE:
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        // Datos del recurso o array de recursos
    }
}
```

#### Para UPDATE:
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "id": 1
    }
}
```

#### Para DELETE:
```json
{
    "status": {
        "status": "OK"
    }
}
```

### Códigos de Estado HTTP
- `200 OK`: Operación exitosa
- `201 Created`: Recurso creado exitosamente
- `401 Unauthorized`: Token inválido o faltante
- `422 Unprocessable Entity`: Errores de validación
- `404 Not Found`: Recurso no encontrado

## Headers Requeridos

Para todos los endpoints protegidos:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json (para datos JSON)
Content-Type: multipart/form-data (para archivos)
```

## Endpoints por Recurso

---

## 1. USUARIOS (Users)

### Listar Usuarios
**Endpoint:** `GET /api/users`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": [
        {
            "id": 1,
            "name": "Juan",
            "surname": "Pérez",
            "email": "juan@example.com",
            "phone": "+1234567890",
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    ]
}
```

### Mostrar Usuario
**Endpoint:** `GET /api/users/{id}`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "id": 1,
        "name": "Juan",
        "surname": "Pérez",
        "email": "juan@example.com",
        "phone": "+1234567890",
        "created_at": "2024-01-20T10:30:00.000000Z",
        "updated_at": "2024-01-20T10:30:00.000000Z"
    }
}
```

### Crear Usuario
**Endpoint:** `POST /api/users`

**Datos Requeridos:**
```json
{
    "name": "María",
    "surname": "García",
    "email": "maria@example.com",
    "phone": "+0987654321",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validaciones:**
- `name`: Requerido, string, máximo 255 caracteres
- `surname`: Requerido, string, máximo 255 caracteres
- `email`: Requerido, email válido, único
- `phone`: Requerido, string, máximo 20 caracteres, único
- `password`: Requerido, mínimo 8 caracteres, confirmación requerida

### Actualizar Usuario
**Endpoint:** `PUT /api/users/{id}`

**Datos Requeridos:**
```json
{
    "name": "María Actualizada",
    "surname": "García López",
    "email": "maria.nueva@example.com",
    "phone": "+0987654322"
}
```

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "id": 1
    }
}
```

### Eliminar Usuario
**Endpoint:** `DELETE /api/users/{id}`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    }
}
```

---

## 2. CONTENIDOS (Contents)

### Listar Contenidos
**Endpoint:** `GET /api/contents`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": [
        {
            "id": 1,
            "title": "Sermón Dominical",
            "type": "sermon",
            "description": "Mensaje inspirador para la comunidad",
            "image": "content/images/hashed-filename.jpg",
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    ]
}
```

### Mostrar Contenido
**Endpoint:** `GET /api/contents/{id}`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "id": 1,
        "title": "Sermón Dominical",
        "type": "sermon",
        "description": "Mensaje inspirador para la comunidad",
        "image": "content/images/hashed-filename.jpg",
        "created_at": "2024-01-20T10:30:00.000000Z",
        "updated_at": "2024-01-20T10:30:00.000000Z"
    }
}
```

### Crear Contenido
**Endpoint:** `POST /api/contents`

**Content-Type:** `multipart/form-data`

**Datos Requeridos:**
```javascript
const formData = new FormData();
formData.append('title', 'Nuevo Sermón');
formData.append('type', 'sermon');
formData.append('description', 'Descripción del contenido');
formData.append('image', fileInput.files[0]); // Archivo de imagen
```

**Validaciones:**
- `title`: Requerido, string, máximo 255 caracteres
- `type`: Requerido, string, máximo 255 caracteres
- `description`: Requerido, string, máximo 2000 caracteres
- `image`: Requerido, archivo de imagen (jpeg,png,jpg,gif,svg), máximo 2MB

### Actualizar Contenido
**Endpoint:** `PUT /api/contents/{id}`

**Content-Type:** `multipart/form-data`

**Datos Requeridos:**
```javascript
const formData = new FormData();
formData.append('title', 'Sermón Actualizado');
formData.append('type', 'sermon');
formData.append('description', 'Nueva descripción');
formData.append('image', fileInput.files[0]); // Archivo de imagen
formData.append('_method', 'PUT'); // Método HTTP override
```

### Eliminar Contenido
**Endpoint:** `DELETE /api/contents/{id}`

---

## 3. MISIONEROS (Missionaries)

### Listar Misioneros
**Endpoint:** `GET /api/missionaries`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": [
        {
            "id": 1,
            "title": "Misionero en África",
            "message": "Compartiendo el evangelio en comunidades rurales",
            "image": "missionary/images/hashed-filename.jpg",
            "disable_at": null,
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    ]
}
```

### Mostrar Misionero
**Endpoint:** `GET /api/missionaries/{id}`

### Crear Misionero
**Endpoint:** `POST /api/missionaries`

**Content-Type:** `multipart/form-data`

**Datos Requeridos:**
```javascript
const formData = new FormData();
formData.append('title', 'Nuevo Misionero');
formData.append('message', 'Mensaje del misionero');
formData.append('image', fileInput.files[0]); // Archivo de imagen
formData.append('disable_at', '2024-12-31'); // Opcional
```

**Validaciones:**
- `title`: Requerido, string, máximo 255 caracteres
- `message`: Requerido, string, máximo 2000 caracteres
- `image`: Requerido, archivo de imagen (jpeg,png,jpg,gif,svg), máximo 2MB
- `disable_at`: Opcional, fecha válida

### Actualizar Misionero
**Endpoint:** `PUT /api/missionaries/{id}`

**Content-Type:** `multipart/form-data`

**Datos Requeridos:**
```javascript
const formData = new FormData();
formData.append('title', 'Misionero Actualizado');
formData.append('message', 'Mensaje actualizado');
formData.append('image', fileInput.files[0]); // Archivo de imagen
formData.append('_method', 'PUT'); // Método HTTP override
```

### Eliminar Misionero
**Endpoint:** `DELETE /api/missionaries/{id}`

---

## 4. TESTIMONIOS (Testimonies)

### Listar Testimonios
**Endpoint:** `GET /api/testimonies`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": [
        {
            "id": 1,
            "name": "Ana López",
            "message": "Mi testimonio de fe y transformación",
            "image": "testimony/images/hashed-filename.jpg",
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    ]
}
```

### Mostrar Testimonio
**Endpoint:** `GET /api/testimonies/{id}`

### Crear Testimonio
**Endpoint:** `POST /api/testimonies`

**Content-Type:** `multipart/form-data`

**Datos Requeridos:**
```javascript
const formData = new FormData();
formData.append('name', 'Carlos Ruiz');
formData.append('message', 'Mi testimonio personal');
formData.append('image', fileInput.files[0]); // Archivo de imagen
```

**Validaciones:**
- `name`: Requerido, string, máximo 255 caracteres
- `message`: Requerido, string, máximo 2000 caracteres
- `image`: Requerido, archivo de imagen (jpeg,png,jpg,gif,svg), máximo 2MB

### Actualizar Testimonio
**Endpoint:** `PUT /api/testimonies/{id}`

**Content-Type:** `multipart/form-data`

### Eliminar Testimonio
**Endpoint:** `DELETE /api/testimonies/{id}`

---

## 5. SUSCRIPCIONES (Subscriptions)

### Listar Suscripciones
**Endpoint:** `GET /api/subscriptions`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": [
        {
            "id": 1,
            "email": "suscriptor@example.com",
            "phone": "+1234567890",
            "name": "Juan Suscriptor",
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    ]
}
```

### Mostrar Suscripción
**Endpoint:** `GET /api/subscriptions/{id}`

### Crear Suscripción
**Endpoint:** `POST /api/subscriptions`

**Datos Requeridos:**
```json
{
    "email": "nuevo@example.com",
    "phone": "+1234567890",
    "name": "Nuevo Suscriptor"
}
```

**Validaciones:**
- `email`: Requerido, email válido, único
- `phone`: Requerido, string
- `name`: Requerido, string, máximo 255 caracteres

### Actualizar Suscripción
**Endpoint:** `PUT /api/subscriptions/{id}`

**Datos Requeridos:**
```json
{
    "email": "actualizado@example.com",
    "phone": "+0987654321",
    "name": "Nombre Actualizado"
}
```

### Eliminar Suscripción
**Endpoint:** `DELETE /api/subscriptions/{id}`

### Alternar Estado de Suscripción
**Endpoint:** `PATCH /api/subscriptions/{id}/toggle`

**Respuesta:**
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "id": 1
    }
}
```

---

## Manejo de Archivos

### Subida de Archivos
Todos los endpoints que requieren archivos (`contents`, `missionaries`, `testimonies`) utilizan:

- **Content-Type:** `multipart/form-data`
- **Formatos permitidos:** jpeg, png, jpg, gif, svg
- **Tamaño máximo:** 2MB
- **Almacenamiento:** Configurado en `config/filesystems.php`

### Ejemplo con JavaScript (Fetch)
```javascript
const fileInput = document.getElementById('image');
const formData = new FormData();

formData.append('title', 'Mi Título');
formData.append('description', 'Mi descripción');
formData.append('image', fileInput.files[0]);

fetch('/api/contents', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
        // NO incluir Content-Type para multipart/form-data
    },
    body: formData
})
.then(response => response.json())
.then(data => console.log(data));
```

### Ejemplo con JavaScript (Axios)
```javascript
const formData = new FormData();
formData.append('title', 'Mi Título');
formData.append('image', fileInput.files[0]);

axios.post('/api/contents', formData, {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
    }
})
.then(response => console.log(response.data));
```

## Manejo de Errores

### 422 - Errores de Validación
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "image": [
            "The image field is required.",
            "The image must be an image."
        ]
    }
}
```

### 401 - No Autorizado
```json
{
    "message": "Unauthenticated."
}
```

### 404 - No Encontrado
```json
{
    "message": "No query results for model [App\\Models\\User] 999"
}
```

## Ejemplos de Uso Completos

### Ejemplo 1: Crear Contenido Completo
```javascript
async function createContent(token, title, type, description, imageFile) {
    const formData = new FormData();
    formData.append('title', title);
    formData.append('type', type);
    formData.append('description', description);
    formData.append('image', imageFile);

    try {
        const response = await fetch('/api/contents', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error creating content:', error);
        throw error;
    }
}
```

### Ejemplo 2: Listar y Filtrar Recursos
```javascript
async function getAllUsers(token) {
    try {
        const response = await fetch('/api/users', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();
        return data.data; // Array de usuarios
    } catch (error) {
        console.error('Error fetching users:', error);
        throw error;
    }
}
```

### Ejemplo 3: Actualizar Recurso
```javascript
async function updateUser(token, userId, userData) {
    try {
        const response = await fetch(`/api/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error updating user:', error);
        throw error;
    }
}
```

## Configuración del Cliente

### Configuración Axios Global
```javascript
// Configurar axios con interceptors
axios.defaults.baseURL = 'http://localhost:8000/api';
axios.defaults.headers.common['Accept'] = 'application/json';

// Interceptor para añadir token automáticamente
axios.interceptors.request.use(
    config => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    error => Promise.reject(error)
);

// Interceptor para manejar errores de autenticación
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            // Redirigir al login
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);
```

## Notas Importantes

1. **Autenticación**: Todos los endpoints excepto `auth/register` y `auth/login` requieren autenticación
2. **Archivos**: Los archivos se almacenan según la configuración de `filesystems.default`
3. **Validación**: Todas las validaciones son servidor-side y retornan errores detallados
4. **CORS**: Configura CORS apropiadamente para tu dominio frontend
5. **Rate Limiting**: Considera implementar rate limiting para proteger la API

---

Esta documentación cubre todos los endpoints disponibles en la API. Para implementar autenticación, consulta la documentación de autenticación. 