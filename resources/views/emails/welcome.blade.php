@component('mail::message')
# Hola {{ $user->name }}

Gracias por crear una cuenta. Por favor verifícala usando el siguiente botón:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Confirmar mi cuenta
@endcomponent

Saludos,<br>
{{ config('app.name') }}

@component('mail::footer')
Si el botón no funciona, utilice el siguiente enlace: {{ route('verify', $user->verification_token) }}
@endcomponent
@endcomponent
