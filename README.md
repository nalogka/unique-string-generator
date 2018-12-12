# Генератор случайных строк

Пакет содержит два класса Generator и Converter, с соответствующими статическими методами для генерации случайных строк и их конвертации в различные представления.

## Генератор

Метод `Generator::generate()` служит для генерации случайно строки, принимает на вход два аргумента: множество символов, из которых будет сгенерирована случайная строка, и длинна строки:

Например:

```php 
Generator::generate('012', 5); // 02001
```

Для удобства в класс добавлены константы, с наиболее часто употребимыми наборами символов:

```
Generator::ALPHA_U  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
Generator::ALPHA_L = 'abcdefghijklmnopqrstuvwxyz';
Generator::DEC  = '0123456789';
Generator::DEC_ALPHA_L_U  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
Generator::HEX_U = '0123456789ABCDEF';
Generator::HEX_L = '0123456789abcdef';
```

## Конвертер

При помощи конвертера можно преобразовать сгенерированную строку с известным набором символов в бинарную строку и обратно (`Converter::toBinary()` и `Converter::fromBinary()`), а также в десятичное представление и обратно (`Converter::toDecimal()` и `Converter::fromDecimal()`).

```php
$generated = Generator::generate('ABC', 5); // ACAAB
$binStr = Converter::toBinary('ABC', $generated); // "\x37"
Converter::fromBinary('ABC', $binStr, 5); // ACAAB, здесь последний аргумент нужен, чтоб восстановить исходный размер строки
```