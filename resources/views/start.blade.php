
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
Исполнитель подтвердил выполнение заказа {{ $name_app }} ждите связи с Посредником.
<br>
<a href="{{  $link }}">Посмотреть</a>
</body>
</html>