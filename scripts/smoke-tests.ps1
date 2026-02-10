<#
Simple smoke tests for local Astra Library WordPress site.
Runs checks: required files exist, site root responds, REST `book` endpoint responds,
and if a book exists its single page returns 200.

Usage:
  Open PowerShell in this folder and run:
    .\smoke-tests.ps1

Adjust `$BaseUrl` if your site URL differs.
#>

$BaseUrl = 'http://localhost/library'
$script:failures = 0

function Assert-FileExists {
    param($rel)
    $path = Join-Path $PSScriptRoot "..\$rel"
    if (Test-Path $path) { Write-Host "[PASS] File exists: $rel" -ForegroundColor Green } else { Write-Host "[FAIL] Missing file: $rel" -ForegroundColor Red; $script:failures++ }
}

function Assert-UrlOk {
    param($url, $label)
    try {
        $r = Invoke-WebRequest -Uri $url -UseBasicParsing -Method GET -TimeoutSec 10
        if ($r.StatusCode -ge 200 -and $r.StatusCode -lt 400) { Write-Host "[PASS] $label -> $url (Status $($r.StatusCode))" -ForegroundColor Green } else { Write-Host "[FAIL] $label -> $url (Status $($r.StatusCode))" -ForegroundColor Red; $script:failures++ }
    } catch {
        Write-Host "[FAIL] $label -> $url ($_ )" -ForegroundColor Red
        $script:failures++
    }
}

Write-Host "Running smoke tests against $BaseUrl`n"

Write-Host "Checking required files..." -ForegroundColor Cyan
Assert-FileExists 'wp-content/mu-plugins/library-security.php'
Assert-FileExists 'wp-content/themes/astra-library-child/functions.php'
Assert-FileExists 'wp-content/themes/astra-library-child/single-book.php'
Assert-FileExists 'wp-content/themes/astra-library-child/archive-book.php'

Write-Host "`nChecking HTTP endpoints..." -ForegroundColor Cyan
Assert-UrlOk "$BaseUrl/" 'Site root'
Assert-UrlOk "$BaseUrl/wp-json/" 'WP REST API root'

Write-Host "`nChecking REST book endpoint..." -ForegroundColor Cyan
try {
    $books = Invoke-RestMethod -Uri "$BaseUrl/wp-json/wp/v2/book?per_page=5" -Method GET -TimeoutSec 10
    if ($books -and $books.Count -gt 0) {
        Write-Host "[PASS] REST `book` returned $($books.Count) items" -ForegroundColor Green
        $first = $books[0]
        if ($first.link) {
            Assert-UrlOk $first.link 'First book single page'
        } else {
            Write-Host "[WARN] First book has no link field" -ForegroundColor Yellow
        }
    } else {
        Write-Host "[WARN] REST `book` returned no items (publish at least one book to test single page)." -ForegroundColor Yellow
    }
} catch {
    Write-Host "[FAIL] REST `book` endpoint request failed: $_" -ForegroundColor Red
    $script:failures++
}

Write-Host "`nSummary:" -ForegroundColor Cyan
if ($script:failures -eq 0) { Write-Host "All smoke tests passed." -ForegroundColor Green; exit 0 } else { Write-Host "$script:failures test(s) failed." -ForegroundColor Red; exit 2 }
