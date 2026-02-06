# Script para indentación de archivos .blade.php
function IndentBladeFile {
    param([string]$filePath)
    
    # Leer el contenido del archivo
    $content = Get-Content -Path $filePath -Raw -Encoding UTF8
    
    # Split por líneas
    $lines = $content -split "
"
    $indentedLines = @()
    $indentLevel = 0
    
    # Palabras clave que afectan indentación
    $openTags = @('@extends', '@section', '@if', '@foreach', '@for', '@while', '<div', '<form', '<table', '<tbody', '<thead', '<tr', '<ul', '<ol', '<select', '@component', '@isset', '@php', '<x-')
    $closeTags = @('@endsection', '@endif', '@endforeach', '@endfor', '@endwhile', '@endcomponent', '@endisset', '@endphp', '</div>', '</form>', '</table>', '</tbody>', '</thead>', '</tr>', '</ul>', '</ol>', '</select>', '</x-')
    
    foreach ($line in $lines) {
        $trimmed = $line.Trim()
        
        # Si la línea está vacía, conservarla vacía
        if ([string]::IsNullOrWhiteSpace($trimmed)) {
            $indentedLines += ''
            continue
        }
        
        # Detectar etiquetas de cierre (reducir indentación antes)
        $isClosing = $false
        foreach ($closeTag in $closeTags) {
            if ($trimmed.StartsWith($closeTag)) {
                if ($indentLevel -gt 0) { $indentLevel-- }
                $isClosing = $true
                break
            }
        }
        
        # Aplicar indentación (4 espacios por nivel)
        $indented = ('    ' * $indentLevel) + $trimmed
        $indentedLines += $indented
        
        # Detectar etiquetas de apertura (aumentar indentación después)
        foreach ($openTag in $openTags) {
            if ($trimmed.StartsWith($openTag)) {
                # Evitar duplicar si ya es una línea de cierre
                if (-not $isClosing) {
                    $indentLevel++
                }
                break
            }
        }
    }
    
    # Escribir el contenido indentado
    $outputContent = $indentedLines -join "
"
    Set-Content -Path $filePath -Value $outputContent -Encoding UTF8
}

# Procesar todos los archivos
$files = Get-ChildItem -Path "resources\views\admin" -Filter "*.blade.php" -Recurse
$processedCount = 0

foreach ($file in $files) {
    try {
        IndentBladeFile -filePath $file.FullName
        $processedCount++
        Write-Host "✓ Procesado: $($file.FullName)" -ForegroundColor Green
    } catch {
        Write-Host "✗ Error en $($file.FullName): $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host "
Total archivos procesados: $processedCount de $($files.Count)"
