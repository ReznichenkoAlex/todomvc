## Задача 1. Понимание существующего кода.

Упростите следующий код, сохранив лексикографический порядок возвращаемых строк:

```
function makeMagicStringFromDate() 
{
    $dateTime = new DateTime("now", new DateTimeZone("GMT"));
    $str = $dateTime->format("YmdHis");

    for ($i = 0; $i < strlen($str); $i++) {
        if (ctype_digit($str[$i])) {
            if ($str[$i] == 0) {
                $str[$i] = 'a';
            } else {
                $str[$i] = 10 - $str[$i];
                    }
            }
    }
    return $str;
}
```
----
Данная функция создаёт объект DateTime, затем преобразует числовое значение даты по правилу: если 0, то его заменить на латинскую а, в противном случае от десяти отнять текущую цифру в цикле и присвоить новое значение.

В цикле присутстувут проверка цифровых симоволов (ctype_digit). Можно полностью её убрать, зная о том что DateTime::format всегда будет возвращать строку, содержащую только цифры (при параметре "YmdHis"). Либо можно поменять  условие ```$str[$i] == 0```  на ```$str[$i] === 0```, чтобы проверялся тип и сравнивались числа
```
function makeMagicStringFromDate()
{
    $dateTime = new DateTime("now", new DateTimeZone("GMT"));
    $str = $dateTime->format("YmdHis");

    for ($i = 0; $i < strlen($str); $i++) {
            $str[$i] = $str[$i] === '0' 
            ? 'a'
            : 10 - $str[$i];
        }

    return $str;
}
```
Но при таком подходе мы не проверяем в else кейсе наличие цифрового символа, поэтому внесем туда проверку

```
function makeMagicStringFromDate()
{
    $dateTime = new DateTime("now", new DateTimeZone("GMT"));
    $str = $dateTime->format("YmdHis");

    for ($i = 0; $i < strlen($str); $i++) {
            if($str[$i] === '0') {
                $str[$i] = 'a';
            } else if (ctype_digit($str[$i])) {
                $str[$i] = 10 - $str[$i];
            }
        }

    return $str;
}
```