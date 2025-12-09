<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de Archivo - {{ $fileName ?? 'Archivo' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .viewer-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .viewer-header {
            background: #1f2937;
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .viewer-content {
            flex: 1;
            overflow: auto;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        iframe, embed, object {
            width: 100%;
            height: 100%;
            border: none;
        }
        .file-info {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <div class="viewer-header">
            <div class="flex items-center space-x-4">
                <i class="fas fa-file text-xl"></i>
                <div>
                    <h1 class="text-lg font-semibold">{{ $fileName ?? 'Archivo' }}</h1>
                    @if(isset($fileSize))
                    <p class="text-sm text-gray-300">{{ $fileSize }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ $downloadUrl }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center space-x-2"
                   download>
                    <i class="fas fa-download"></i>
                    <span>Descargar</span>
                </a>
                <button onclick="window.close()" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
        <div class="viewer-content">
            @php
                $extension = strtolower(pathinfo($fileName ?? '', PATHINFO_EXTENSION));
                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                $isPDF = $extension === 'pdf';
                $isText = in_array($extension, ['txt', 'md', 'csv']);
                $isOffice = in_array($extension, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);
            @endphp

            @if($isImage)
                <img src="{{ $fileUrl }}" alt="{{ $fileName }}" class="max-w-full max-h-full object-contain">
            @elseif($isPDF)
                <iframe src="{{ $fileUrl }}" type="application/pdf"></iframe>
            @elseif($isText)
                <div class="file-info max-w-4xl w-full">
                    <pre class="whitespace-pre-wrap text-sm font-mono bg-gray-900 text-green-400 p-4 rounded overflow-auto" style="max-height: 70vh;">{{ $fileContent ?? 'No se puede mostrar el contenido del archivo' }}</pre>
                </div>
            @elseif($isOffice)
                <div class="file-info max-w-2xl text-center">
                    <i class="fas fa-file-{{ $extension === 'doc' || $extension === 'docx' ? 'word' : ($extension === 'xls' || $extension === 'xlsx' ? 'excel' : 'powerpoint') }} text-6xl text-indigo-600 mb-4"></i>
                    <p class="text-gray-600 mb-4">Este tipo de archivo no se puede visualizar directamente en el navegador.</p>
                    <p class="text-sm text-gray-500 mb-4">Por favor, descarga el archivo para abrirlo con la aplicación correspondiente.</p>
                    <a href="{{ $downloadUrl }}" 
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium">
                        <i class="fas fa-download mr-2"></i> Descargar Archivo
                    </a>
                </div>
            @else
                <div class="file-info max-w-2xl text-center">
                    <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-4">Este tipo de archivo no se puede visualizar directamente en el navegador.</p>
                    <p class="text-sm text-gray-500 mb-4">Por favor, descarga el archivo para abrirlo.</p>
                    <a href="{{ $downloadUrl }}" 
                       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium">
                        <i class="fas fa-download mr-2"></i> Descargar Archivo
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>


