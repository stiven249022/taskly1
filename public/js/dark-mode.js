// Dark Mode Manager
class DarkModeManager {
    constructor() {
        this.darkModeKey = 'darkMode';
        this.init();
    }

    init() {
        // Aplicar modo oscuro al cargar la página
        this.applyDarkMode();
        
        // Escuchar cambios en la preferencia del sistema
        this.listenForSystemPreference();
        
        // Configurar event listeners para toggles
        this.setupToggleListeners();
    }

    applyDarkMode() {
        const isDarkMode = this.getDarkModePreference();
        
        if (isDarkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }

    getDarkModePreference() {
        // Primero verificar localStorage
        const storedPreference = localStorage.getItem(this.darkModeKey);
        
        if (storedPreference !== null) {
            return storedPreference === 'true';
        }
        
        // Si no hay preferencia guardada, usar la preferencia del sistema
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    setDarkModePreference(isDark) {
        localStorage.setItem(this.darkModeKey, isDark.toString());
        this.applyDarkMode();
    }

    toggleDarkMode() {
        const currentMode = this.getDarkModePreference();
        this.setDarkModePreference(!currentMode);
    }

    listenForSystemPreference() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        mediaQuery.addEventListener('change', (e) => {
            // Solo aplicar si no hay preferencia guardada en localStorage
            if (localStorage.getItem(this.darkModeKey) === null) {
                this.applyDarkMode();
            }
        });
    }

    setupToggleListeners() {
        // Configurar todos los toggles de modo oscuro
        const darkModeToggles = document.querySelectorAll('[data-dark-mode-toggle]');
        
        darkModeToggles.forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                this.setDarkModePreference(e.target.checked);
                this.updateServerPreference(e.target.checked);
            });
        });

        // Configurar botones de toggle
        const darkModeButtons = document.querySelectorAll('[data-dark-mode-button]');
        
        darkModeButtons.forEach(button => {
            button.addEventListener('click', () => {
                this.toggleDarkMode();
                this.updateServerPreference(this.getDarkModePreference());
            });
        });
    }

    updateServerPreference(isDark) {
        // Actualizar preferencia en el servidor
        fetch('/profile/preferences', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                dark_mode: isDark,
                email_notifications: document.getElementById('emailNotificationsToggle')?.checked || false,
                push_notifications: document.getElementById('pushNotificationsToggle')?.checked || false,
                task_reminders: document.getElementById('taskRemindersToggle')?.checked || false,
                project_deadlines: document.getElementById('projectDeadlinesToggle')?.checked || false,
                exam_reminders: document.getElementById('examRemindersToggle')?.checked || false
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Preferencias actualizadas correctamente');
            }
        })
        .catch(error => {
            console.error('Error al actualizar preferencias:', error);
        });
    }

    // Método para sincronizar toggles con el estado actual
    syncToggles() {
        const isDark = this.getDarkModePreference();
        const toggles = document.querySelectorAll('[data-dark-mode-toggle]');
        
        toggles.forEach(toggle => {
            toggle.checked = isDark;
        });
    }
}

// Inicializar el gestor de modo oscuro cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.darkModeManager = new DarkModeManager();
});

// Exportar para uso global
window.DarkModeManager = DarkModeManager; 