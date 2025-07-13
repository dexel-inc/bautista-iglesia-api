# API de Autenticación - Bautista Iglesia

## Endpoints Disponibles

### Registro de Usuario
```http
POST /api/auth/register
```

**Parámetros:**
```json
{
    "name": "string (requerido, max:255)",
    "email": "string (requerido, email, único)",
    "password": "string (requerido, min:8, confirmado)",
    "password_confirmation": "string (requerido)"
}
```

**Respuesta exitosa (201):**
```json
{
    "message": "User registered successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "updated_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

### Inicio de Sesión
```http
POST /api/auth/login
```

**Parámetros:**
```json
{
    "email": "string (requerido, email)",
    "password": "string (requerido)"
}
```

**Respuesta exitosa (200):**
```json
{
    "access_token": "1|token_string_here",
    "token_type": "Bearer",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "updated_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

### Obtener Perfil de Usuario (Autenticado)
```http
GET /api/auth/me
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "updated_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

### Cerrar Sesión (Autenticado)
```http
POST /api/auth/logout
```

**Headers:**
```
Authorization: Bearer {token}
```

**Respuesta exitosa (200):**
```json
{
    "message": "User logged out successfully"
}
```

## Códigos de Error

- **401 Unauthorized**: Token no válido o no proporcionado
- **422 Unprocessable Entity**: Errores de validación
- **500 Internal Server Error**: Error del servidor

## Rate Limiting

- **Límite**: 60 requests por minuto por usuario autenticado o IP
- **Header de respuesta**: `X-RateLimit-Remaining`

## Autenticación

La API utiliza Laravel Sanctum para la autenticación basada en tokens. Incluye el token en el header `Authorization` como `Bearer {token}` para endpoints protegidos. 