<#
Simple backup script for local XAMPP WordPress setup.
Adjust db settings below before running.
#>

param()

$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$backupDir = "..\backups\$timestamp"
New-Item -ItemType Directory -Force -Path $backupDir | Out-Null

# Database credentials (edit if necessary)
$dbName = "library_db"
$dbUser = "root"
$dbPass = ""

Write-Host "Dumping database $dbName..."
$dumpFile = "$backupDir\${dbName}_$timestamp.sql"
& "C:\xampp\mysql\bin\mysqldump.exe" -u $dbUser --password=$dbPass $dbName > $dumpFile

Write-Host "Zipping uploads and theme files..."
$zipFile = "$backupDir\site_files_$timestamp.zip"
$itemsToZip = @(
    "..\wp-content\uploads",
    "..\wp-content\themes\astra-library-child",
    "..\wp-content\plugins"
)
Compress-Archive -Path $itemsToZip -DestinationPath $zipFile -Force

Write-Host "Backup completed: $backupDir"
