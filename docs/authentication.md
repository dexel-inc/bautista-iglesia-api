# Documentación de Autenticación - Bautista Iglesia API

## Resumen

Esta API utiliza **Laravel Sanctum** para autenticación basada en tokens. Sanctum proporciona un sistema de autenticación simple y ligero para aplicaciones SPA, APIs móviles y tokens de API simples.

## Cómo Funciona

### 1. Sistema de Tokens
- **Tokens personales**: Cada usuario puede generar tokens de acceso personal
- **Tokens de API**: Los tokens se almacenan en la base de datos y se pueden revocar
- **Stateless**: Los tokens no dependen de sesiones, perfectos para APIs REST

### 2. Protección de Rutas
- Las rutas protegidas requieren el middleware `auth:sanctum`
- El token debe enviarse en el header `Authorization: Bearer {token}`
- Si el token es inválido o falta, se retorna un error 401

## Endpoints de Autenticación

### Registro de Usuario
Crear una nueva cuenta de usuario en el sistema.

**Endpoint:** `POST /api/auth/register`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Datos Requeridos:**
```json
{
    "name": "Juan",
    "surname": "Pérez",
    "email": "juan@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validaciones:**
- `name`: Requerido, string, máximo 255 caracteres
- `surname`: Requerido, string, máximo 255 caracteres
- `email`: Requerido, email válido, único en la base de datos
- `phone`: Requerido, string, máximo 20 caracteres, único en la base de datos
- `password`: Requerido, mínimo 8 caracteres, debe coincidir con confirmación

**Respuesta Exitosa (201):**
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

### Login de Usuario
Autenticar usuario y obtener token de acceso.

**Endpoint:** `POST /api/auth/login`

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Datos Requeridos:**
```json
{
    "email": "juan@example.com",
    "password": "password123"
}
```

**Validaciones:**
- `email`: Requerido, formato email válido
- `password`: Requerido, string

**Respuesta Exitosa (200):**
```json
{
    "status": {
        "status": "OK"
    },
    "data": {
        "access_token": "1|abcdef123456789...",
        "token_type": "Bearer",
        "user": {
            "id": 1,
            "name": "Juan",
            "surname": "Pérez",
            "email": "juan@example.com",
            "phone": "+1234567890",
            "created_at": "2024-01-20T10:30:00.000000Z",
            "updated_at": "2024-01-20T10:30:00.000000Z"
        }
    }
}
```

### Información del Usuario Autenticado
Obtener los datos del usuario actualmente autenticado.

**Endpoint:** `GET /api/auth/me`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Respuesta Exitosa (200):**
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

### Logout de Usuario
Cerrar sesión y revocar el token actual.

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Respuesta Exitosa (200):**
```json
{
    "status": {
        "status": "OK"
    }
}
```

## Manejo de Tokens

### Obtener Token
1. Realizar login en `/api/auth/login`
2. Guardar el `access_token` de la respuesta
3. Usar el token en todas las peticiones subsiguientes

### Usar Token
Incluir el token en el header `Authorization` de todas las peticiones:

```javascript
// Ejemplo con fetch
fetch('/api/users', {
    headers: {
        'Authorization': 'Bearer ' + token,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
})

// Ejemplo con axios
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
```

### Revocar Token
- Llamar a `/api/auth/logout` para revocar el token actual
- El token queda invalidado inmediatamente

## Rutas Protegidas

Todas las rutas CRUD de recursos requieren autenticación:

### Recursos Protegidos
- **Usuarios**: `/api/users/*`
- **Contenidos**: `/api/contents/*`
- **Misioneros**: `/api/missionaries/*`
- **Testimonios**: `/api/testimonies/*`
- **Suscripciones**: `/api/subscriptions/*`

### Rutas Públicas
- `POST /api/auth/register` - Registro de usuarios
- `POST /api/auth/login` - Inicio de sesión

## Errores de Autenticación

### 401 - No Autorizado
Cuando el token es inválido, falta o ha expirado:

```json
{
    "message": "Unauthenticated."
}
```

### 422 - Errores de Validación
Cuando los datos de login/registro son inválidos:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email field is required."
        ],
        "password": [
            "The password field is required."
        ]
    }
}
```

### 403 - Credenciales Incorrectas
Cuando las credenciales de login son incorrectas:

```json
{
    "message": "The provided credentials are incorrect."
}
```

## Mejores Prácticas

### Seguridad
1. **Almacenamiento Seguro**: Guarda los tokens en lugares seguros (no en localStorage para aplicaciones web)
2. **HTTPS**: Usa siempre HTTPS en producción
3. **Expiración**: Los tokens no expiran automáticamente, revócalos manualmente
4. **Manejo de Errores**: Maneja apropiadamente los errores 401 y redirige al login

### Implementación
1. **Interceptors**: Usa interceptors para manejar tokens automáticamente
2. **Refresh Strategy**: Implementa una estrategia para renovar tokens
3. **Logout Global**: Implementa logout que limpie todos los tokens almacenados

## Ejemplo de Flujo Completo

```javascript
// 1. Registro
const registerResponse = await fetch('/api/auth/register', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        name: 'Juan',
        surname: 'Pérez',
        email: 'juan@example.com',
        phone: '+1234567890',
        password: 'password123',
        password_confirmation: 'password123'
    })
});

// 2. Login
const loginResponse = await fetch('/api/auth/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'juan@example.com',
        password: 'password123'
    })
});

const loginData = await loginResponse.json();
const token = loginData.data.access_token;

// 3. Usar el token para acceder a recursos protegidos
const usersResponse = await fetch('/api/users', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});

// 4. Logout
await fetch('/api/auth/logout', {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});
```

## Configuración Adicional

### Variables de Entorno
Asegúrate de tener configuradas las siguientes variables en tu `.env`:

```
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
SESSION_DRIVER=database
```

### Middleware
El middleware `auth:sanctum` está configurado automáticamente en las rutas protegidas.

---

Esta documentación cubre todos los aspectos esenciales de la autenticación en la API. Para más detalles sobre endpoints específicos, consulta la documentación de endpoints. 