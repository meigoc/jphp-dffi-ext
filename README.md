# DFFI 2.0
DevelNext Foreign Function Interface 2.0

## API Documentation
[api-docs , Документация](api-docs/)

## Bundle for DevelNext
[Download , СКАЧАТЬ](https://github.com/meigoc/jphp-dffi-ext/releases)

## Examples

### MessageBox
```php
<?php
use system\DFFI;
use system\DFFIType;

$user32 = new DFFI("user32");
$user32->bind("MessageBoxA", DFFIType::INT, [DFFIType::INT, DFFIType::STRING, DFFIType::STRING, DFFIType::INT]);
//int MesssageBoxA(int, string, string, int)

DFFI::MessageBoxA(0, "Hello", "HelloWorld", 0);
```
### Get Cursor Position
```php
<?php
use system\DFFI;
use system\DFFIType;
use system\DFFIStruct;

$user32 = new DFFI("user32");
$user32->bind("GetCursorPos", DFFIType::INT, [DFFIType::STRUCT]);

$point = new DFFIStruct("POINT", ["int", "int"]);
DFFI::GetCursorPos($point);
pre($point->getResponse());
```
### Set Cursor Position
```php
<?php
use system\DFFI;
use system\DFFIType;
use system\DFFIStruct;

$user32 = new DFFI("user32");
$user32->bind("SetCursorPos", DFFIType::INT, ["int", "int"]);

DFFI::SetCursorPos(100, 100);
```
### Make Screenshot
```php
<?php
use system\DFFI;
use system\DFFIType;

//Описываем функции
$user32 = new DFFI("user32");
$user32->bind("OpenClipboard", DFFIType::BOOL, [DFFIType::INT]);
$user32->bind("EmptyClipboard", DFFIType::BOOL, []);
$user32->bind("SetClipboardData", DFFIType::BOOL, [DFFIType::INT, DFFIType::INT]);
$user32->bind("CloseClipboard", DFFIType::BOOL, []);

$gdi32 = new DFFI("gdi32");
$gdi32->bind("CreateDCA", DFFIType::INT, [DFFIType::STRING, DFFIType::INT, DFFIType::INT, DFFIType::INT]);
$gdi32->bind("CreateCompatibleDC", DFFIType::INT, [DFFIType::INT]);
$gdi32->bind("GetDeviceCaps", DFFIType::INT, [DFFIType::INT, DFFIType::INT]);
$gdi32->bind("CreateCompatibleBitmap", DFFIType::INT, [DFFIType::INT, DFFIType::INT, DFFIType::INT]);
$gdi32->bind("SelectObject", DFFIType::INT, [DFFIType::INT, DFFIType::INT]);
$gdi32->bind("BitBlt", DFFIType::BOOL, [DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT, DFFIType::INT]);
$gdi32->bind("DeleteDC", DFFIType::BOOL, [DFFIType::INT]);


//Вызываем ф-ии
$hScreenDC = DFFI::CreateDCA("DISPLAY", 0, 0, 0);
$hMemoryDC = DFFI::CreateCompatibleDC($hScreenDC);

$width = DFFI::GetDeviceCaps($hMemoryDC, 8);
$height = DFFI::GetDeviceCaps($hMemoryDC, 10);

$hBitmap = DFFI::CreateCompatibleBitmap($hScreenDC, $width, $height);
$hOldBitmap = DFFI::SelectObject($hMemoryDC, $hBitmap);

DFFI::BitBlt($hMemoryDC, 0, 0, $width, $height, $hScreenDC, 0, 0, 0x00CC0020);
DFFI::SelectObject($hMemoryDC, $hOldBitmap);

DFFI::OpenClipboard(0);
DFFI::EmptyClipboard();
DFFI::SetClipboardData(2, $hBitmap);
DFFI::CloseClipboard();

DFFI::DeleteDC($hMemoryDC);
```
### Write Process Memory
```php
<?php
use system\DFFI;
use system\DFFIType;

//Описываем функции
$user32 = new DFFI("user32");
$user32->bind("FindWindowA", DFFIType::INT, [DFFIType::INT, DFFIType::STRING]);
$user32->bind("GetWindowThreadProcessId", DFFIType::INT, [DFFIType::INT, DFFIType::REFERENCE]);

$kernel32 = new DFFI("kernel32");
$kernel32->bind("OpenProcess", DFFIType::INT, [DFFIType::INT, DFFIType::BOOL, DFFIType::INT]);
$kernel32->bind("WriteProcessMemory", DFFIType::BOOL, [DFFIType::INT, DFFIType::INT, DFFIType::REFERENCE, DFFIType::INT, DFFIType::INT]);


$all_access = 0x000F0000 | 0x00100000 | 0xFFFF;
$base = 0x6FFAE0; // адрес
$newValue = 100;
$hwnd = DFFI::FindWindowA(null, "MainWindow"]); //получаем хендл окна
if($hwnd == 0){
	alert("Window not found");
} else {
	$pid_ref = new DFFIReferenceValue("int");
	DFFI::GetWindowThreadProcessId($hwnd, $pid_ref); //получаем pid процесса
	$pid = $pid_ref->getValue();
	
	$hOpen = DFFI::OpenProcess($all_access, false, $pid);
	if(!$hOpen){
		alert("Process error");
	}else{
		$newValue_ref = new DFFIReferenceValue("int", $newValue);
		DFFI::WriteProcessMemory($hOpen, $base, $newValue_ref, sizeof($newValue));
	}
}
```
### Console Colors
Используется библиотека [org.meigo.Colors](https://github.com/meigoc/Colors) для удобства.
```php
<?php
use system\DFFIConsole;

DFFIConsole::enableColors(); // Включаем цвета в консоли
echo "Color: ".Colors::withColor('green text', 'green')." \n"; //выводим
```
