@echo off
start "" "C:\laragon\laragon.exe" --startup
timeout /t 5 /nobreak > nul
for /f "tokens=2 delims=:" %%i in ('ipconfig ^| findstr /c:"IPv4 Address"') do set IP=%%i
set IP=%IP:~1%
echo Tablet Access: http://%IP%
pause