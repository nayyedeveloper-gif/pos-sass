@echo off
REM Print Agent Installation Script for Windows

echo ================================
echo Print Agent Installation
echo ================================
echo.

REM Check if Node.js is installed
where node >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Node.js is not installed!
    echo Please download and install Node.js from: https://nodejs.org/
    echo.
    pause
    exit /b 1
)

echo Node.js version:
node -v
echo NPM version:
npm -v
echo.

echo Installing dependencies...
call npm install

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ================================
    echo Installation Complete!
    echo ================================
    echo.
    echo To start the print agent:
    echo   npm start
    echo.
    echo Configuration Steps:
    echo   1. Get your local IP: ipconfig
    echo   2. Allow port 3001 in Windows Firewall
    echo   3. Configure VPS to send requests to http://YOUR_LOCAL_IP:3001
    echo.
) else (
    echo.
    echo Installation failed!
    pause
    exit /b 1
)

pause
