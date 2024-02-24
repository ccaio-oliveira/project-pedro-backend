<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Usuário criado</title>
</head>
<body>
    <h1>Usuário criado</h1>
    <p>Olá {{ $full_name }}</p>
    <p>Um usuário foi criado para você.</p>
    <p><strong>Acesse com as informações abaixo:</strong></p>
    <p><strong>Usuário:</strong> {{ $email }} </p>
    <p><strong>Senha:</strong> {{ $password }}</p>
    <a href="{{ $url_login }}">Acessar a página de login</a>
</body>
</html>
