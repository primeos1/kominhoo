# start-ngrok.ps1
# Starts an ngrok HTTP tunnel to the local web server (default :80) and updates .env files.
# Usage:
#   .\start-ngrok.ps1
#   .\start-ngrok.ps1 -Port 80

param(
  [int]$Port = 80,
  [int]$WaitSeconds = 2
)

$ErrorActionPreference = "Stop"

function Resolve-NgrokExe {
  $cmd = Get-Command ngrok -ErrorAction SilentlyContinue
  if ($cmd -and $cmd.Source) { return $cmd.Source }

  $localExe = Join-Path $PSScriptRoot "ngrok.exe"
  if (Test-Path -LiteralPath $localExe) { return $localExe }

  return $null
}

$ngrokExe = Resolve-NgrokExe
if (-not $ngrokExe) {
  Write-Host "ngrok.exe not found." -ForegroundColor Red
  Write-Host "Install ngrok and ensure it's on PATH, or place ngrok.exe in: $PSScriptRoot" -ForegroundColor Yellow
  exit 1
}

Write-Host "Starting ngrok tunnel for http://localhost:$Port ..." -ForegroundColor Cyan

# If ngrok is already running, don't start another instance.
try {
  Invoke-RestMethod -Uri "http://localhost:4040/api/tunnels" -TimeoutSec 1 | Out-Null
  Write-Host "ngrok API already reachable on localhost:4040 (ngrok likely running)." -ForegroundColor Yellow
} catch {
  Start-Process -FilePath $ngrokExe -ArgumentList @("http", $Port) -WindowStyle Hidden | Out-Null
  Start-Sleep -Seconds $WaitSeconds
}

& (Join-Path $PSScriptRoot "set-ngrok-url.ps1")

