@component('mail::message')
# Hola {{ $user->name }}

Has cambiado tu correo electrónico. Por favor verifíca la nueva dirección usando el siguiente botón:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Confirmar mi cuenta
@endcomponent

Saludos,<br>
{{ config('app.name') }}

@component('mail::footer')
Si el botón no funciona, utilice el siguiente enlace: {{ route('verify', $user->verification_token) }}
@endcomponent
@endcomponent

