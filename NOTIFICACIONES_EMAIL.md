# 🔔 Sistema de Notificaciones por Email para Alertas

## 📋 **Resumen del Sistema**

El sistema de notificaciones por email de Taskly envía automáticamente alertas cuando:
- **Tareas** están próximas a vencer (hoy, mañana, en 3 días)
- **Proyectos** están próximos a vencer (hoy, mañana, en 7 días)
- **Exámenes** están próximos (hoy, mañana)

## 🚀 **Funcionalidades Implementadas**

### ✅ **Notificaciones Creadas:**
1. **TaskDueSoonNotification** - Alertas de tareas próximas a vencer
2. **ProjectDueSoonNotification** - Alertas de proyectos próximos a vencer
3. **ExamReminderNotification** - Alertas de exámenes próximos

### ✅ **Comando Artisan:**
- **`notifications:send-due`** - Envía notificaciones automáticamente

### ✅ **Controlador API:**
- **NotificationController** - Gestiona notificaciones desde la API

### ✅ **Rutas API:**
- **`/api/notifications/task`** - Enviar notificación de tarea
- **`/api/notifications/project`** - Enviar notificación de proyecto
- **`/api/notifications/exam`** - Enviar notificación de examen
- **`/api/notifications/settings`** - Configurar notificaciones
- **`/api/notifications/send-automatic`** - Enviar notificaciones automáticas

## 🔧 **Cómo Funciona**

### **1. Notificaciones Automáticas:**
```bash
# Ejecutar comando para enviar notificaciones
php artisan notifications:send-due
```

### **2. Notificaciones Manuales (API):**
```bash
# Enviar notificación de tarea
POST /api/notifications/task
{
    "task_id": 1,
    "days_until_due": 0
}

# Enviar notificación de proyecto
POST /api/notifications/project
{
    "project_id": 1,
    "days_until_due": 1
}

# Enviar notificación de examen
POST /api/notifications/exam
{
    "reminder_id": 1,
    "days_until_due": 0
}
```

### **3. Configuración de Usuario:**
```bash
# Obtener configuración actual
GET /api/notifications/settings

# Actualizar configuración
POST /api/notifications/settings
{
    "email_notifications": true,
    "task_reminders": true,
    "project_deadlines": true,
    "exam_reminders": true,
    "reminder_frequency": "daily"
}
```

## 📧 **Tipos de Notificaciones**

### **Tareas (TaskDueSoonNotification):**
- **Hoy (0 días)**: "¡Tarea vence HOY!"
- **Mañana (1 día)**: "Tarea vence mañana"
- **3 días**: "Tarea vence en 3 días"

**Contenido del email:**
- Título y descripción de la tarea
- Fecha de vencimiento
- Curso asociado
- Prioridad
- Enlace directo a la tarea

### **Proyectos (ProjectDueSoonNotification):**
- **Hoy (0 días)**: "¡Proyecto vence HOY!"
- **Mañana (1 día)**: "Proyecto vence mañana"
- **7 días**: "Proyecto vence en 7 días"

**Contenido del email:**
- Título y descripción del proyecto
- Fecha de vencimiento
- Curso asociado
- Estado actual
- Enlace directo al proyecto

### **Exámenes (ExamReminderNotification):**
- **Hoy (0 días)**: "¡Examen HOY!"
- **Mañana (1 día)**: "Examen mañana"

**Contenido del email:**
- Título y descripción del examen
- Fecha y hora
- Curso asociado
- Tipo de recordatorio
- Enlace directo al recordatorio

## ⚙️ **Configuración del Usuario**

### **Campos de Configuración:**
- **`email_notifications`**: Habilitar/deshabilitar todas las notificaciones por email
- **`task_reminders`**: Alertas específicas para tareas
- **`project_deadlines`**: Alertas específicas para proyectos
- **`exam_reminders`**: Alertas específicas para exámenes
- **`reminder_frequency`**: Frecuencia de recordatorios (daily, weekly, monthly)

### **Ejemplo de Configuración:**
```json
{
    "email_notifications": true,
    "push_notifications": false,
    "task_reminders": true,
    "project_deadlines": true,
    "exam_reminders": true,
    "reminder_frequency": "daily"
}
```

## 🕐 **Programación Automática**

### **Recomendación de Cron Job:**
```bash
# Ejecutar cada día a las 8:00 AM
0 8 * * * cd /path/to/taskly && php artisan notifications:send-due

# Ejecutar cada hora para verificar tareas urgentes
0 * * * * cd /path/to/taskly && php artisan notifications:send-due
```

### **Configuración en Windows (Task Scheduler):**
1. Abrir **Programador de tareas**
2. Crear **Tarea básica**
3. **Programar**: Diariamente a las 8:00 AM
4. **Acción**: Iniciar programa
5. **Programa**: `php`
6. **Argumentos**: `artisan notifications:send-due`
7. **Iniciar en**: `C:\path\to\taskly`

## 🧪 **Pruebas del Sistema**

### **1. Probar Comando Artisan:**
```bash
cd taskly
php artisan notifications:send-due
```

### **2. Probar API (con token):**
```bash
# Enviar notificación automática
curl -X POST http://localhost:8000/api/notifications/send-automatic \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"

# Ver configuración
curl -X GET http://localhost:8000/api/notifications/settings \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

### **3. Verificar Emails:**
- Revisar carpeta de **Spam** en Gmail
- Verificar configuración de **filtros** de Gmail
- Comprobar logs de Laravel en `storage/logs/laravel.log`

## 🚨 **Solución de Problemas**

### **Error: "No se pueden enviar emails"**
- Verificar configuración de Gmail en `.env`
- Comprobar que `MAIL_PASSWORD` sea la contraseña de aplicación
- Verificar que la verificación en 2 pasos esté habilitada

### **Error: "Usuario no encontrado"**
- Verificar que el usuario tenga `email_notifications = true`
- Comprobar que el usuario esté autenticado

### **Error: "Tarea no encontrada"**
- Verificar que la tarea exista y pertenezca al usuario
- Comprobar que la tarea no esté completada

### **Emails no llegan:**
- Revisar carpeta de Spam
- Verificar configuración de Gmail
- Comprobar logs de Laravel
- Verificar que el comando se ejecute correctamente

## 📱 **Integración con Frontend**

### **Ejemplo de Uso en JavaScript:**
```javascript
// Enviar notificación de tarea
async function sendTaskNotification(taskId, daysUntilDue) {
    const response = await fetch('/api/notifications/task', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            task_id: taskId,
            days_until_due: daysUntilDue
        })
    });
    
    return await response.json();
}

// Obtener configuración de notificaciones
async function getNotificationSettings() {
    const response = await fetch('/api/notifications/settings', {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    });
    
    return await response.json();
}
```

## 🎯 **Próximos Pasos**

### **Mejoras Futuras:**
1. **Notificaciones push** para dispositivos móviles
2. **Plantillas personalizables** de email
3. **Programación inteligente** basada en hábitos del usuario
4. **Integración con calendarios** (Google Calendar, Outlook)
5. **Notificaciones de equipo** para proyectos colaborativos

### **Configuración Avanzada:**
1. **Filtros personalizados** por prioridad
2. **Horarios específicos** para cada tipo de notificación
3. **Zonas horarias** automáticas
4. **Idiomas múltiples** para las notificaciones

## ✅ **Estado Actual**

- ✅ **Notificaciones básicas** implementadas
- ✅ **API completa** para gestión de notificaciones
- ✅ **Comando Artisan** para envío automático
- ✅ **Configuración de usuario** personalizable
- ✅ **Documentación completa** del sistema

¡El sistema de notificaciones por email está completamente funcional! 🎉
