@extends('layouts.dashboard')

@section('title', 'Mi Perfil')
@section('page-title', 'Mi Perfil')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="max-w-4xl mx-auto">
    <!-- Información del Perfil -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Información Personal</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Actualiza tu información personal y foto de perfil</p>
        </div>
        
        <div class="p-6">
            <form id="profileForm" enctype="multipart/form-data">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Foto de Perfil -->
                    <div class="lg:col-span-1">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 border-4 border-gray-200 dark:border-gray-600">
                                    <img id="profileImage" src="{{ auth()->user()->profile_photo_url }}" 
                                         alt="Foto de perfil" class="w-full h-full object-cover">
                                </div>
                                <button type="button" onclick="document.getElementById('profilePhoto').click()" 
                                        class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-camera text-sm"></i>
                                </button>
                                @if(auth()->user()->profile_photo_path)
                                <button type="button" onclick="deleteProfilePhoto()" 
                                        class="absolute top-0 right-0 bg-red-600 text-white p-1 rounded-full hover:bg-red-700 transition-colors text-xs">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" class="hidden" onchange="previewImage(this)">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">Haz clic en la cámara para cambiar la foto</p>
                            @if(auth()->user()->profile_photo_path)
                            <p class="text-xs text-red-500 dark:text-red-400 mt-1 text-center">Haz clic en la X para eliminar la foto</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Información Personal -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido</label>
                                <input type="text" name="last_name" value="{{ auth()->user()->last_name ?? '' }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teléfono</label>
                                <input type="tel" name="phone" value="{{ auth()->user()->phone ?? '' }}" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biografía</label>
                                <textarea name="bio" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                          placeholder="Cuéntanos un poco sobre ti...">{{ auth()->user()->bio ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
                </div>

    <!-- Configuración de Seguridad -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Seguridad</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Cambia tu contraseña y configura la seguridad</p>
                </div>

        <div class="p-6">
            <form id="passwordForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contraseña Actual</label>
                        <input type="password" name="current_password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nueva Contraseña</label>
                        <input type="password" name="new_password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Nueva Contraseña</label>
                        <input type="password" name="new_password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                        <i class="fas fa-key mr-2"></i> Cambiar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Preferencias -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Preferencias</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Configura tus preferencias de la aplicación</p>
        </div>
        
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Modo Oscuro</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Activar el tema oscuro en la aplicación</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="darkModeToggle" {{ auth()->user()->dark_mode ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Notificaciones por Email</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Recibir notificaciones por correo electrónico</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="emailNotificationsToggle" {{ auth()->user()->email_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Notificaciones Push</h5>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Recibir notificaciones en tiempo real</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="pushNotificationsToggle" {{ auth()->user()->push_notifications ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #4f46e5;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}
</style>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validar tipo de archivo
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Solo se permiten archivos de imagen (JPEG, PNG, JPG, GIF)', 'error');
            input.value = '';
            return;
        }
        
        // Validar tamaño (2MB máximo)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('El archivo es demasiado grande. Máximo 2MB', 'error');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileImage').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function deleteProfilePhoto() {
    if (confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
        fetch('/profile/photo', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('profileImage').src = data.profile_photo_url;
                
                // Actualizar todas las imágenes de perfil en la aplicación
                updateAllProfileImages(data.profile_photo_url);
                
                // Ocultar botón de eliminar
                const deleteButton = document.querySelector('button[onclick="deleteProfilePhoto()"]');
                if (deleteButton) {
                    deleteButton.style.display = 'none';
                }
                // Ocultar texto de eliminar
                const deleteText = document.querySelector('p.text-red-500');
                if (deleteText) {
                    deleteText.style.display = 'none';
                }
                showNotification('Foto de perfil eliminada exitosamente', 'success');
            } else {
                showNotification(data.message || 'Error al eliminar la foto', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al eliminar la foto de perfil', 'error');
        });
    }
}

// Formulario de perfil
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Mostrar estado de carga
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';
    
    fetch('/profile/update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar la imagen si se subió una nueva foto
            if (data.profile_photo_url) {
                document.getElementById('profileImage').src = data.profile_photo_url;
                
                // Actualizar todas las imágenes de perfil en la aplicación
                updateAllProfileImages(data.profile_photo_url);
                
                // Mostrar botón de eliminar si no existe
                const deleteButton = document.querySelector('button[onclick="deleteProfilePhoto()"]');
                if (!deleteButton) {
                    const photoContainer = document.querySelector('.relative');
                    const newDeleteButton = document.createElement('button');
                    newDeleteButton.type = 'button';
                    newDeleteButton.onclick = 'deleteProfilePhoto()';
                    newDeleteButton.className = 'absolute top-0 right-0 bg-red-600 text-white p-1 rounded-full hover:bg-red-700 transition-colors text-xs';
                    newDeleteButton.innerHTML = '<i class="fas fa-times"></i>';
                    photoContainer.appendChild(newDeleteButton);
                    
                    // Agregar texto de eliminar
                    const photoText = document.querySelector('.text-xs.text-gray-500');
                    const deleteText = document.createElement('p');
                    deleteText.className = 'text-xs text-red-500 mt-1 text-center';
                    deleteText.textContent = 'Haz clic en la X para eliminar la foto';
                    photoText.parentNode.insertBefore(deleteText, photoText.nextSibling);
                }
            }
            
            showNotification('Perfil actualizado exitosamente', 'success');
            
            // Limpiar el input de archivo
            document.getElementById('profilePhoto').value = '';
        } else {
            showNotification(data.message || 'Error al actualizar el perfil', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar el perfil', 'error');
    })
    .finally(() => {
        // Restaurar botón
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
});

// Formulario de contraseña
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/profile/password', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Contraseña cambiada exitosamente', 'success');
            this.reset();
        } else {
            showNotification(data.message || 'Error al cambiar la contraseña', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al cambiar la contraseña', 'error');
    });
});

// Toggles de preferencias
document.getElementById('darkModeToggle').addEventListener('change', function() {
    updatePreference('dark_mode', this.checked);
});

document.getElementById('emailNotificationsToggle').addEventListener('change', function() {
    updatePreference('email_notifications', this.checked);
});

document.getElementById('pushNotificationsToggle').addEventListener('change', function() {
    updatePreference('push_notifications', this.checked);
});

function updatePreference(key, value) {
    fetch('/profile/preferences', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            [key]: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Preferencia actualizada', 'success');
        } else {
            showNotification('Error al actualizar la preferencia', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar la preferencia', 'error');
    });
}

    // Función para actualizar todas las imágenes de perfil en la aplicación
    function updateAllProfileImages(newPhotoUrl) {
        // Actualizar imágenes en el sidebar
        const sidebarImages = document.querySelectorAll('#sidebarProfileImage');
        sidebarImages.forEach(img => {
            img.src = newPhotoUrl;
        });
        
        // Actualizar imágenes en el header
        const headerImages = document.querySelectorAll('#headerProfileImage');
        headerImages.forEach(img => {
            img.src = newPhotoUrl;
        });
        
        // Actualizar cualquier otra imagen de perfil
        const allProfileImages = document.querySelectorAll('img[src*="profile-photo"], img[src*="ui-avatars"]');
        allProfileImages.forEach(img => {
            if (img.src.includes('profile-photo') || img.src.includes('ui-avatars')) {
                img.src = newPhotoUrl;
            }
        });
        
        // Emitir evento personalizado para que otros componentes puedan escuchar
        window.dispatchEvent(new CustomEvent('profilePhotoUpdated', {
            detail: { photoUrl: newPhotoUrl }
        }));
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        // Configurar colores según el tipo
        switch(type) {
            case 'success':
                notification.className += ' bg-green-500 text-white';
                break;
            case 'error':
                notification.className += ' bg-red-500 text-white';
                break;
            case 'warning':
                notification.className += ' bg-yellow-500 text-white';
                break;
            default:
                notification.className += ' bg-blue-500 text-white';
        }
        
        notification.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Agregar al DOM
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
</script>
@endsection
