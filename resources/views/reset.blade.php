<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<body>
Уважаемый, @php
    {{ print_r($name);  }}
@endphp
<br>
Ваш код для восстановления пароля: {{ $code }}
<br>
Для того чтобы изменить пароль , перейдите по ссылке.
<br>
<a href="{{  $link }}">Перейти</a>
</body>
</html>