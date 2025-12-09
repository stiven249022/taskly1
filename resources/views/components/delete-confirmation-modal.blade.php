<!-- Modal de Confirmación de Eliminación -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Icono de advertencia -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            
            <!-- Título -->
            <h3 class="text-lg font-medium text-gray-900 mt-4" id="deleteModalTitle">
                Confirmar Eliminación
            </h3>
            
            <!-- Mensaje -->
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="deleteModalMessage">
                    ¿Estás seguro de que deseas eliminar este elemento? Esta acción no se puede deshacer.
                </p>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-center space-x-3 mt-4">
                <button id="deleteModalCancel" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                    Cancelar
                </button>
                <button id="deleteModalConfirm" 
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones para manejar el modal de confirmación
window.deleteConfirmationModal = {
    modal: null,
    confirmCallback: null,
    
    init() {
        this.modal = document.getElementById('deleteConfirmationModal');
        const cancelBtn = document.getElementById('deleteModalCancel');
        const confirmBtn = document.getElementById('deleteModalConfirm');
        
        // Cerrar modal al hacer clic en cancelar
        cancelBtn.addEventListener('click', () => {
            this.hide();
        });
        
        // Cerrar modal al hacer clic fuera del modal
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
        
        // Confirmar eliminación
        confirmBtn.addEventListener('click', () => {
            if (this.confirmCallback) {
                this.confirmCallback();
            }
            this.hide();
        });
        
        // Cerrar con Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.hide();
            }
        });
    },
    
    show(title, message, onConfirm) {
        document.getElementById('deleteModalTitle').textContent = title;
        document.getElementById('deleteModalMessage').textContent = message;
        this.confirmCallback = onConfirm;
        this.modal.classList.remove('hidden');
        
        // Focus en el botón cancelar para accesibilidad
        document.getElementById('deleteModalCancel').focus();
    },
    
    hide() {
        this.modal.classList.add('hidden');
        this.confirmCallback = null;
    }
};

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.deleteConfirmationModal.init();
});
</script> 