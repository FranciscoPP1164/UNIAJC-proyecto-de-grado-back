<!DOCTYPE html>
<html>
    <body>
        <h1>Hola {{$user->name}}</h1>
        <p>Su cuenta en enfercuidarte cronogramas ha sido activada.</p>
        <p>
            Para continuar con su proceso de registro, haga click
            <a href="{{route('auth.register', ['user' => $user, 'verificationToken' => $token])}}">
                aquí
            </a>
            <p>{{$user->id}}</p>
            <p>{{$token}}</p>
        </p>
    </body>
</html>
