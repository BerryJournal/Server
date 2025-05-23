<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение регистрации на платформе BerryJournal</title>
</head>

<body>
    <h1>Здравствуйте, {{ $userName }}</h1>
    <p>Подтвердите регистрацию на платформе BerryJournal, нажав на ссылку ниже:</p>
    <a href="http://10.10.10.103:3000/invitation/{{$invitation->id}}">Подтверждение регистрации</a>
</body>

</html>