@extends('layouts.dashboard')

@section('title', 'Prueba de Notificaciones')
@section('page-title', 'Prueba de Notificaciones')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Prueba de Notificaciones</h2>
    
    <div class="space-y-4">
        <div>
            <h3 class="text-lg font-medium mb-2">Notificaciones JavaScript</h3>
            <div class="flex space-x-2">
                <button onclick="showNotification('¡Éxito! Esta es una notificación de éxito.', 'success')" 
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Éxito
                </button>
                <button onclick="showNotification('¡Error! Esta es una notificación de error.', 'error')" 
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Error
                </button>
                <button onclick="showNotification('¡Info! Esta es una notificación informativa.', 'info')" 
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Info
                </button>
                <button onclick="showNotification('¡Advertencia! Esta es una notificación de advertencia.', 'warning')" 
                        class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                    Advertencia
                </button>
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-medium mb-2">Modal de Confirmación</h3>
            <button onclick="confirmDelete('/test-delete', 'Elemento de Prueba', 'elemento')" 
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Probar Modal de Eliminación
            </button>
        </div>
        
        <div>
            <h3 class="text-lg font-medium mb-2">Notificaciones Flash</h3>
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('test.success') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Flash Success
                    </button>
                </form>
                <form method="POST" action="{{ route('test.error') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Flash Error
                    </button>
                </form>
                <form method="POST" action="{{ route('test.warning') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                        Flash Warning
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Función de prueba para simular eliminación
function confirmDelete(url, itemName, itemType = 'elemento') {
    const title = `Eliminar ${itemType}`;
    const message = `¿Estás seguro de que deseas eliminar "${itemName}"? Esta acción no se puede deshacer.`;
    
    window.deleteConfirmationModal.show(title, message, () => {
        // Mostrar notificación de carga
        showNotification('Eliminando...', 'info');
        
        // Simular petición AJAX
        setTimeout(() => {
            showNotification('¡Elemento eliminado exitosamente!', 'success');
        }, 2000);
    });
}
</script>
@endsection 