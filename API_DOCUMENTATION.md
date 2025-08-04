# API de Bautista Iglesia

## Endpoints Disponibles

### Autenticación

#### Registro de Usuario
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

#### Inicio de Sesión
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

#### Obtener Perfil de Usuario (Autenticado)
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

#### Cerrar Sesión (Autenticado)
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

### Comunicaciones

#### Enviar Carta de Oración (Pray Letter)
```http
POST /api/pray-letters/send
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Parámetros:**
```json
{
    "subject": "string (requerido, max:255)",
    "description": "string (requerido)",
    "file": "file (requerido, pdf, max:10MB)"
}
```

**Descripción:**
- Envía una carta de oración a todos los misioneros activos
- El archivo es **requerido** y debe ser PDF
- Se envía por email con diseño profesional y colores desérticos
- Incluye logo configurable de la iglesia
- Footer con información de Dexel Inc.

**Respuesta exitosa (200):**
```json
{
    "status": {
        "status": "OK"
    }
}
```

#### Enviar Newsletter
```http
POST /api/newsletters/send
```

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Parámetros:**
```json
{
    "subject": "string (requerido, max:255)",
    "description": "string (requerido)",
    "file": "file (opcional, pdf/doc/docx, max:10MB)"
}
```

**Descripción:**
- Envía un newsletter a todos los subscriptores activos
- El archivo es **opcional** y acepta PDF, DOC, DOCX
- Sistema de batching automático (lotes de 50 emails para evitar límites de Gmail)
- Mismo diseño profesional que las cartas de oración
- Logo configurable y footer con información de Dexel Inc.
- Manejo eficiente de listas grandes de subscriptores

**Respuesta exitosa (200):**
```json
{
    "status": {
        "status": "OK"
    }
}
```

### Gestión de Usuarios (Autenticado)

#### Listar Usuarios
```http
GET /api/users
```

#### Crear Usuario
```http
POST /api/users
```

#### Mostrar Usuario
```http
GET /api/users/{id}
```

#### Actualizar Usuario
```http
PUT /api/users/{id}
```

#### Eliminar Usuario
```http
DELETE /api/users/{id}
```

### Gestión de Subscripciones (Autenticado)

#### Listar Subscripciones
```http
GET /api/subscriptions
```

#### Crear Subscripción
```http
POST /api/subscriptions
```

#### Mostrar Subscripción
```http
GET /api/subscriptions/{id}
```

#### Actualizar Subscripción
```http
PUT /api/subscriptions/{id}
```

#### Eliminar Subscripción
```http
DELETE /api/subscriptions/{id}
```

#### Toggle Subscripción
```http
PATCH /api/subscriptions/{id}/toggle
```

### Gestión de Testimonios (Autenticado)

#### Listar Testimonios
```http
GET /api/testimonies
```

#### Crear Testimonio
```http
POST /api/testimonies
```

#### Mostrar Testimonio
```http
GET /api/testimonies/{id}
```

#### Actualizar Testimonio
```http
POST /api/testimonies/{id}/edit
```

#### Eliminar Testimonio
```http
DELETE /api/testimonies/{id}
```

### Gestión de Misioneros (Autenticado)

#### Listar Misioneros
```http
GET /api/missionaries
```

#### Crear Misionero
```http
POST /api/missionaries
```

#### Mostrar Misionero
```http
GET /api/missionaries/{id}
```

#### Actualizar Misionero
```http
POST /api/missionaries/{id}/edit
```

#### Eliminar Misionero
```http
DELETE /api/missionaries/{id}
```

### Visitas

#### Registrar Visita
```http
POST /api/visits
```

#### Estadísticas de Visitas (Autenticado)
```http
POST /api/visits/stats
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

## Características Especiales

### Sistema de Emails
- **Diseño profesional** con colores desérticos (marrón, beige, dorado)
- **Logo configurable** mediante variable de entorno `CHURCH_LOGO_URL`
- **Footer con información** de Dexel Inc. (sitio web, email, teléfono)
- **Responsive design** para diferentes dispositivos

### Batching de Emails
- **Newsletters**: Sistema automático de lotes de 50 emails para evitar límites de Gmail
- **Logging detallado** de cada lote enviado
- **Manejo eficiente** de listas grandes de subscriptores

### Archivos Adjuntos
- **Pray Letters**: Archivo PDF requerido
- **Newsletters**: Archivo opcional (PDF, DOC, DOCX)
- **Límite**: 10MB por archivo
- **Limpieza automática** de archivos temporales 