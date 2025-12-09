// Funciones comunes para la aplicación Taskly

// Configurar CSRF token para todas las peticiones AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Función para mostrar notificaciones
function showNotification(message, type = 'success') {
    // Remover notificaciones existentes para evitar superposición
    const existingNotifications = document.querySelectorAll('.taskly-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    let bgColor = 'bg-green-500';
    let icon = '';
    
    switch(type) {
        case 'success':
            bgColor = 'bg-green-500';
            icon = '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            icon = '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            break;
        case 'info':
            bgColor = 'bg-blue-500';
            icon = '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            icon = '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
            break;
    }
    
    notification.className = `taskly-notification fixed top-4 right-4 px-6 py-3 rounded-md text-white z-50 ${bgColor} transform transition-all duration-300 ease-in-out shadow-lg`;
    notification.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    
    // Animación de entrada
    notification.style.transform = 'translateX(100%)';
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Función para confirmar eliminación con modal
function confirmDelete(url, itemName, itemType = 'elemento') {
    const title = `Eliminar ${itemType}`;
    const message = `¿Estás seguro de que deseas eliminar "${itemName}"? Esta acción no se puede deshacer.`;
    
    window.deleteConfirmationModal.show(title, message, () => {
        // Mostrar notificación de carga
        showNotification('Eliminando...', 'info');
        
        // Enviar petición AJAX para eliminar
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al eliminar el elemento', 'error');
        });
    });
}

// Función para toggle de estado
function toggleStatus(url, itemType) {
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Recargar la página para actualizar el estado
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar el estado', 'error');
    });
}

// Función para actualizar progreso de proyectos
function updateProjectProgress(projectId, progress) {
    const url = `/projects/${projectId}/update-progress`;
    
    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ progress: progress })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            // Actualizar la barra de progreso visualmente
            const progressBar = document.querySelector(`#project-${projectId} .progress-fill`);
            if (progressBar) {
                progressBar.style.width = `${progress}%`;
            }
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al actualizar el progreso', 'error');
    });
}

// Función para validar formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    return isValid;
}

// Función para manejar envío de formularios con validación
function handleFormSubmit(formId, successMessage = 'Datos guardados exitosamente') {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        if (!validateForm(formId)) {
            e.preventDefault();
            showNotification('Por favor, completa todos los campos requeridos', 'warning');
            return false;
        }
    });
}

// Función para recargar la página después de eliminar
function reloadAfterDelete() {
    // Verificar si hay mensajes de éxito en la sesión
    const successMessages = document.querySelectorAll('.alert-success, .bg-green-100');
    if (successMessages.length > 0) {
        // Si hay mensajes de éxito, recargar después de un breve delay
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }
}

// Función para inicializar tooltips
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.left = this.offsetLeft + 'px';
            tooltip.style.top = (this.offsetTop - 30) + 'px';
            
            document.body.appendChild(tooltip);
            this.tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.remove();
                this.tooltip = null;
            }
        });
    });
}

// Función para manejar modales
function initModals() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        const closeBtn = modal.querySelector('.modal-close');
        const overlay = modal.querySelector('.modal-overlay');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        }
    });
}

// Función para manejar filtros
function handleFilters() {
    const filterForms = document.querySelectorAll('.filter-form');
    
    filterForms.forEach(form => {
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                form.submit();
            });
        });
    });
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    initTooltips();
    
    // Inicializar modales
    initModals();
    
    // Inicializar filtros
    handleFilters();
    
    // Configurar validación de formularios
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        handleFormSubmit(form.id);
    });
    
    // Configurar botones de eliminar
    const deleteButtons = document.querySelectorAll('[data-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-delete');
            const itemName = this.getAttribute('data-item-name');
            confirmDelete(url, itemName);
        });
    });
    
    // Configurar botones de toggle
    const toggleButtons = document.querySelectorAll('[data-toggle]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('data-toggle');
            const itemType = this.getAttribute('data-item-type');
            toggleStatus(url, itemType);
        });
    });
    
    // Configurar sliders de progreso
    const progressSliders = document.querySelectorAll('[data-progress-slider]');
    progressSliders.forEach(slider => {
        slider.addEventListener('change', function() {
            const projectId = this.getAttribute('data-project-id');
            const progress = this.value;
            updateProjectProgress(projectId, progress);
        });
    });
    
    // Verificar si recargar después de eliminar
    reloadAfterDelete();
});

// Exportar funciones para uso global
window.Taskly = {
    showNotification,
    confirmDelete,
    toggleStatus,
    updateProjectProgress,
    validateForm,
    handleFormSubmit
}; 