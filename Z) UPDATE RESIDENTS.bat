@echo off
SETLOCAL

:: Set variables
SET resident_id=1
SET api_url=http://localhost:8000/api/residents/%resident_id%
SET fio=John Doe
SET area=123
SET start_date=2022-01-01

:: Convert parameters to JSON format, ensuring proper escaping
SET json_data={"fio": "%fio%", "area": %area%, "start_date": "%start_date%"}

:: Make the PUT request with curl
curl -X PUT -H "Content-Type: application/json" -d "%json_data%" "%api_url%"

ENDLOCAL
pause