# set-ngrok-url.ps1
# Run this after ngrok starts to update both .env files with the current tunnel URL.
# Usage: .\set-ngrok-url.ps1

$backendEnv  = "$PSScriptRoot\backend\.env"
$frontendEnv = "$PSScriptRoot\frontend\.env"

Write-Host "Fetching ngrok tunnel URL..." -ForegroundColor Cyan

try {
    $tunnels = Invoke-RestMethod -Uri "http://localhost:4040/api/tunnels" -ErrorAction Stop
    $httpsUrl = ($tunnels.tunnels | Where-Object { $_.proto -eq "https" } | Select-Object -First 1).public_url

    if (-not $httpsUrl) {
        Write-Host "No HTTPS tunnel found. Is ngrok running?" -ForegroundColor Red
        exit 1
    }

    $ngrokHost      = ([uri]$httpsUrl).Host
    $backendUrl     = "$httpsUrl/kominhoo/backend/public"
    $frontendUrl    = "$httpsUrl/kominhoo/frontend/public"
    $backendApiUrl  = "$httpsUrl/kominhoo/backend/public/api/v1"

    # Update backend .env
    $b = Get-Content $backendEnv -Raw
    $b = $b -replace 'APP_URL=.*',                    "APP_URL=$backendUrl"
    $b = $b -replace 'FRONTEND_URL=.*',               "FRONTEND_URL=$frontendUrl"
    $b = $b -replace 'SANCTUM_STATEFUL_DOMAINS=.*',   "SANCTUM_STATEFUL_DOMAINS=localhost,$ngrokHost"
    Set-Content -Path $backendEnv -Value $b -NoNewline

    # Update frontend .env
    $f = Get-Content $frontendEnv -Raw
    $f = $f -replace 'APP_URL=.*',      "APP_URL=$frontendUrl"
    $f = $f -replace 'API_BASE_URL=.*', "API_BASE_URL=$backendApiUrl"
    Set-Content -Path $frontendEnv -Value $f -NoNewline

    Write-Host ""
    Write-Host "Both .env files updated:" -ForegroundColor Green
    Write-Host "  Backend  APP_URL  = $backendUrl"
    Write-Host "  Frontend APP_URL  = $frontendUrl"
    Write-Host "  API_BASE_URL      = $backendApiUrl"
    Write-Host ""
    Write-Host "Your public URLs:" -ForegroundColor Yellow
    Write-Host "  Site  -> $frontendUrl"
    Write-Host "  API   -> $backendApiUrl"
    Write-Host ""
    Write-Host "Now run: cd backend && php artisan config:clear && cd ..\frontend && php artisan config:clear" -ForegroundColor Cyan

} catch {
    Write-Host "Could not reach ngrok API at localhost:4040. Make sure ngrok is running." -ForegroundColor Red
    Write-Host $_.Exception.Message
}
