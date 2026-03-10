# ============================================
# CONFIGURACIÓN
# ============================================
$baseUrl = "http://localhost:8081/api"

# ============================================
# FUNCIONES
# ============================================

function Send-JsonPost {
    param(
        [string]$url,
        [hashtable]$data
    )

    $json = $data | ConvertTo-Json -Depth 10

    Write-Host "`n➡ POST $url"
    Write-Host "➡ Body:" $json

    try {
        $response = Invoke-RestMethod `
            -Uri $url `
            -Method Post `
            -ContentType "application/json" `
            -Body $json `
            -ErrorAction Stop

        Write-Host "✔ Respuesta:"
        $response | ConvertTo-Json -Depth 10
        return $response
    }
    catch {
        Write-Host "❌ Error en POST: $($_.Exception.Message)"
        return $null
    }
}

function Send-Get {
    param(
        [string]$url
    )

    Write-Host "`n➡ GET $url"

    try {
        $response = Invoke-RestMethod `
            -Uri $url `
            -Method Get `
            -ErrorAction Stop

        Write-Host "✔ Respuesta:"
        $response | ConvertTo-Json -Depth 10
        return $response
    }
    catch {
        Write-Host "❌ Error en GET: $($_.Exception.Message)"
        return $null
    }
}

# ============================================
# CREAR MUCHOS USUARIOS
# ============================================

Write-Host "`n==============================="
Write-Host "   CREANDO 10 USUARIOS"
Write-Host "==============================="

$usuarios = @()

for ($i = 1; $i -le 10; $i++) {
    $usuario = Send-JsonPost "$baseUrl/usuarios" @{
        nombre = "Usuario$i"
        apellidos = "Apellido$i"
        dni = "0000000$i"
    }

    if ($usuario -and $usuario["@id"]) {
        $usuarios += $usuario["@id"]
    }
}

Write-Host "`n✔ Usuarios creados:"
$usuarios

# ============================================
# CREAR MUCHOS LIBROS
# ============================================

Write-Host "`n==============================="
Write-Host "   CREANDO 10 LIBROS"
Write-Host "==============================="

$libros = @()

for ($i = 1; $i -le 10; $i++) {
    $libro = Send-JsonPost "$baseUrl/libros" @{
        titulo = "Libro $i"
        autor = "Autor $i"
        isbn = "ISBN000$i"
    }

    if ($libro -and $libro["@id"]) {
        $libros += $libro["@id"]
    }
}

Write-Host "`n✔ Libros creados:"
$libros

# ============================================
# CREAR PRÉSTAMOS ALEATORIOS
# ============================================

Write-Host "`n==============================="
Write-Host "   CREANDO 10 PRÉSTAMOS"
Write-Host "==============================="

$prestamos = @()

if ($usuarios.Count -eq 0 -or $libros.Count -eq 0) {
    Write-Host "❌ No se pueden crear préstamos: no hay usuarios o libros creados."
    exit
}

for ($i = 1; $i -le 10; $i++) {
    $usuarioRandom = Get-Random -InputObject $usuarios
    $libroRandom = Get-Random -InputObject $libros

    $prestamo = Send-JsonPost "$baseUrl/prestamos" @{
        usuario = $usuarioRandom
        libro = $libroRandom
        fechaPrestamo = "2026-03-$i"
    }

    if ($prestamo -and $prestamo["@id"]) {
        $prestamos += $prestamo["@id"]
    }
}

Write-Host "`n✔ Préstamos creados:"
$prestamos

# ============================================
# CONSULTAS MASIVAS
# ============================================

Write-Host "`n==============================="
Write-Host "   CONSULTA: TODOS LOS USUARIOS"
Write-Host "==============================="

Send-Get "$baseUrl/usuarios"

Write-Host "`n==============================="
Write-Host "   CONSULTA: TODOS LOS LIBROS"
Write-Host "==============================="

Send-Get "$baseUrl/libros"

Write-Host "`n==============================="
Write-Host "   CONSULTA: TODOS LOS PRÉSTAMOS"
Write-Host "==============================="

Send-Get "$baseUrl/prestamos"

Write-Host "`n==============================="
Write-Host "   FIN DEL SCRIPT"
Write-Host "==============================="
