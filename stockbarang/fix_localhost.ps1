# fix_localhost.ps1
# Run this script as Administrator (right-click -> Run with PowerShell)
# What it does:
# - Adds localhost entries to hosts file if missing
# - Adds Windows Firewall rules to allow Apache (httpd.exe)
# - Tests the local site via curl

function Assert-Admin {
    $isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
    if (-not $isAdmin) {
        Write-Error "This script must be run as Administrator. Right-click -> Run with PowerShell."
        exit 1
    }
}

Assert-Admin

$hostsPath = "C:\\Windows\\System32\\drivers\\etc\\hosts"
$hostsBackup = "$hostsPath.bak_$(Get-Date -Format yyyyMMddHHmmss)"

# Backup hosts
Copy-Item -Path $hostsPath -Destination $hostsBackup -Force
Write-Output "Backed up hosts to $hostsBackup"

# Ensure entries exist
$hostsContent = Get-Content -Path $hostsPath -ErrorAction Stop
$want127 = '127.0.0.1       localhost'
$want6 = '::1             localhost'

$changed = $false
if (-not ($hostsContent -match '127\.0\.0\.1\s+localhost')) {
    Add-Content -Path $hostsPath -Value $want127
    Write-Output "Added: $want127"
    $changed = $true
}
if (-not ($hostsContent -match '::1\s+localhost')) {
    Add-Content -Path $hostsPath -Value $want6
    Write-Output "Added: $want6"
    $changed = $true
}
if (-not $changed) { Write-Output "Hosts already contained the required entries." }

# Add firewall rules for Apache (httpd.exe)
$httpdPath = 'C:\\xampp\\apache\\bin\\httpd.exe'
$rules = @(
    @{Name='Apache (httpd.exe)'; Program=$httpdPath},
    @{Name='Allow HTTP (port 80)'; Port=80},
    @{Name='Allow HTTPS (port 443)'; Port=443}
)

# Add program rule
try {
    netsh advfirewall firewall add rule name="Apache (httpd.exe)" dir=in action=allow program="$httpdPath" enable=yes profile=any | Out-Null
    Write-Output "Firewall: added program rule for $httpdPath"
} catch {
    Write-Warning "Could not add program firewall rule: $_"
}

# Add port rules
try {
    netsh advfirewall firewall add rule name="Allow HTTP (port 80)" dir=in action=allow protocol=TCP localport=80 profile=any | Out-Null
    netsh advfirewall firewall add rule name="Allow HTTPS (port 443)" dir=in action=allow protocol=TCP localport=443 profile=any | Out-Null
    Write-Output "Firewall: added port rules for 80 and 443"
} catch {
    Write-Warning "Could not add port firewall rules: $_"
}

Write-Output "Done. Please restart Apache from XAMPP Control Panel (Stop -> Start)."

# Quick test
Write-Output "Testing http://127.0.0.1/stockbarang/ ..."
try {
    $resp = & curl.exe -s -I http://127.0.0.1/stockbarang/ --max-time 5
    if ($LASTEXITCODE -eq 0) { Write-Output $resp } else { Write-Warning "curl failed with exit code $LASTEXITCODE" }
} catch {
    Write-Warning "Failed to run curl: $_"
}

Write-Output "If your browser still shows 'localhost refused to connect', try opening http://127.0.0.1/stockbarang/ or restart your browser."