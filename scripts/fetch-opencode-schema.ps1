param(
    [string]$Url = 'https://opencode.ai/config.json',
    [string]$Out = '..\opencode.schema.json'
)

try {
    Write-Host "Baixando schema de $Url ..."
    Invoke-WebRequest -Uri $Url -OutFile $Out -UseBasicParsing -ErrorAction Stop
    Write-Host "Salvo em $Out"
} catch {
    Write-Error "Falha ao baixar: $_"
    exit 1
}
