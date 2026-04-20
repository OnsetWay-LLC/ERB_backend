@echo off
setlocal EnableExtensions

REM =========================================================
REM setup_master.bat
REM يدعم:
REM   1) WINDOWS AUTH
REM   2) SQL AUTH
REM
REM Parameters:
REM   %1 = SERVER
REM   %2 = PORT
REM   %3 = DB_NAME
REM   %4 = AUTH_TYPE   (WINDOWS | SQL)
REM   %5 = DB_USERNAME
REM   %6 = DB_PASSWORD
REM
REM Example:
REM   setup_master.bat "localhost\SQLEXPRESS" "1433" "oms" "WINDOWS" "" ""
REM   setup_master.bat "localhost\SQLEXPRESS" "1433" "oms" "SQL" "sa" "123456"
REM =========================================================

set "SERVER=%~1"
set "PORT=%~2"
set "DB_NAME=%~3"
set "AUTH_TYPE=%~4"
set "DB_USERNAME=%~5"
set "DB_PASSWORD=%~6"

REM نفس مكان الملف:
set "SCRIPT_DIR=%~dp0"

REM إذا حاطة الملف داخل scripts\ فاستعملي هذا:
REM set "APP_DIR=%SCRIPT_DIR%.."

REM إذا حاطة الملف جنب artisan مباشرة استعملي هذا:
set "APP_DIR=%SCRIPT_DIR%"

set "BACKEND_DIR=%APP_DIR%"
set "PHP_EXE=php"
set "ENV_FILE=%BACKEND_DIR%\.env"

echo ========================================
echo SETUP MASTER STARTED
echo ========================================

echo SERVER      = %SERVER%
echo PORT        = %PORT%
echo DB_NAME     = %DB_NAME%
echo AUTH_TYPE   = %AUTH_TYPE%
echo DB_USERNAME = %DB_USERNAME%

echo.
echo ========================================
echo [0] Validating input parameters...
echo ========================================

if "%SERVER%"=="" (
    echo ERROR: SERVER is required.
    exit /b 101
)

if "%PORT%"=="" (
    echo ERROR: PORT is required.
    exit /b 102
)

if "%DB_NAME%"=="" (
    echo ERROR: DB_NAME is required.
    exit /b 103
)

if "%AUTH_TYPE%"=="" (
    echo ERROR: AUTH_TYPE is required. Use WINDOWS or SQL.
    exit /b 104
)

if /I "%AUTH_TYPE%"=="SQL" (
    if "%DB_USERNAME%"=="" (
        echo ERROR: DB_USERNAME is required for SQL auth.
        exit /b 105
    )
)

echo OK: input parameters are valid.

echo.
echo ========================================
echo [1] Checking required files...
echo ========================================

if not exist "%BACKEND_DIR%\artisan" (
    echo ERROR: artisan file not found in:
    echo %BACKEND_DIR%
    exit /b 11
)

if not exist "%ENV_FILE%" (
    echo ERROR: .env file not found in:
    echo %ENV_FILE%
    exit /b 12
)

echo OK: artisan and .env found.

echo.
echo ========================================
echo [2] Testing SQL Server connection...
echo ========================================

if /I "%AUTH_TYPE%"=="WINDOWS" (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
      "$cn = New-Object System.Data.SqlClient.SqlConnection;" ^
      "$cn.ConnectionString = 'Server=%SERVER%;Database=master;Integrated Security=True;TrustServerCertificate=True;';" ^
      "try { $cn.Open(); Write-Host 'SQL connection OK'; $cn.Close(); exit 0 } catch { Write-Host $_.Exception.Message; exit 1 }"
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
      "$cn = New-Object System.Data.SqlClient.SqlConnection;" ^
      "$cn.ConnectionString = 'Server=%SERVER%;Database=master;User ID=%DB_USERNAME%;Password=%DB_PASSWORD%;TrustServerCertificate=True;';" ^
      "try { $cn.Open(); Write-Host 'SQL connection OK'; $cn.Close(); exit 0 } catch { Write-Host $_.Exception.Message; exit 1 }"
)

if errorlevel 1 (
    echo ERROR: Failed to connect to SQL Server.
    exit /b 15
)

echo OK: SQL Server connection successful.

echo.
echo ========================================
echo [3] Creating database if not exists...
echo ========================================

if /I "%AUTH_TYPE%"=="WINDOWS" (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
      "$cn = New-Object System.Data.SqlClient.SqlConnection;" ^
      "$cn.ConnectionString = 'Server=%SERVER%;Database=master;Integrated Security=True;TrustServerCertificate=True;';" ^
      "$cmdText = 'IF DB_ID(N''%DB_NAME%'') IS NULL CREATE DATABASE [%DB_NAME%];';" ^
      "try { $cn.Open(); $cmd = $cn.CreateCommand(); $cmd.CommandText = $cmdText; $null = $cmd.ExecuteNonQuery(); $cn.Close(); Write-Host 'Database ready'; exit 0 } catch { Write-Host $_.Exception.Message; exit 1 }"
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
      "$cn = New-Object System.Data.SqlClient.SqlConnection;" ^
      "$cn.ConnectionString = 'Server=%SERVER%;Database=master;User ID=%DB_USERNAME%;Password=%DB_PASSWORD%;TrustServerCertificate=True;';" ^
      "$cmdText = 'IF DB_ID(N''%DB_NAME%'') IS NULL CREATE DATABASE [%DB_NAME%];';" ^
      "try { $cn.Open(); $cmd = $cn.CreateCommand(); $cmd.CommandText = $cmdText; $null = $cmd.ExecuteNonQuery(); $cn.Close(); Write-Host 'Database ready'; exit 0 } catch { Write-Host $_.Exception.Message; exit 1 }"
)

if errorlevel 1 (
    echo ERROR: Failed to create database.
    exit /b 20
)

echo OK: Database is ready.

echo.
echo ========================================
echo [4] Updating DB settings inside .env...
echo ========================================

if /I "%AUTH_TYPE%"=="WINDOWS" (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
        "$p='%ENV_FILE%';" ^
        "$c=Get-Content $p;" ^
        "$c=$c -replace '^DB_CONNECTION=.*','DB_CONNECTION=sqlsrv';" ^
        "$c=$c -replace '^DB_HOST=.*','DB_HOST=%SERVER%';" ^
        "$c=$c -replace '^DB_PORT=.*','DB_PORT=%PORT%';" ^
        "$c=$c -replace '^DB_DATABASE=.*','DB_DATABASE=%DB_NAME%';" ^
        "$c=$c -replace '^DB_USERNAME=.*','DB_USERNAME=';" ^
        "$c=$c -replace '^DB_PASSWORD=.*','DB_PASSWORD=';" ^
        "Set-Content -Path $p -Value $c"
) else (
    powershell -NoProfile -ExecutionPolicy Bypass -Command ^
        "$p='%ENV_FILE%';" ^
        "$c=Get-Content $p;" ^
        "$c=$c -replace '^DB_CONNECTION=.*','DB_CONNECTION=sqlsrv';" ^
        "$c=$c -replace '^DB_HOST=.*','DB_HOST=%SERVER%';" ^
        "$c=$c -replace '^DB_PORT=.*','DB_PORT=%PORT%';" ^
        "$c=$c -replace '^DB_DATABASE=.*','DB_DATABASE=%DB_NAME%';" ^
        "$c=$c -replace '^DB_USERNAME=.*','DB_USERNAME=%DB_USERNAME%';" ^
        "$c=$c -replace '^DB_PASSWORD=.*','DB_PASSWORD=%DB_PASSWORD%';" ^
        "Set-Content -Path $p -Value $c"
)

if errorlevel 1 (
    echo ERROR: Failed to update .env DB settings.
    exit /b 30
)

echo OK: .env updated successfully.

echo.
echo ========================================
echo [5] Clearing config cache...
echo ========================================

cd /d "%BACKEND_DIR%"
%PHP_EXE% artisan config:clear
if errorlevel 1 (
    echo ERROR: config:clear failed.
    exit /b 35
)

echo OK: config cache cleared.

echo.
echo ========================================
echo [6] Running migrations...
echo ========================================

%PHP_EXE% artisan migrate --force
if errorlevel 1 (
    echo ERROR: Migration failed.
    exit /b 50
)

echo OK: Migrations completed.

echo.
echo ========================================
echo [7] Running seeders...
echo ========================================

%PHP_EXE% artisan db:seed --force
if errorlevel 1 (
    echo ERROR: Seeder failed.
    exit /b 60
)

echo OK: Seeders completed.

echo.
echo ========================================
echo MASTER SETUP COMPLETED SUCCESSFULLY
echo ========================================
exit /b 0