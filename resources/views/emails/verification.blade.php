@component('mail::message')
    # Verifiez votre adresse email

    Ciliquez sur le boutton ci-dessous pour vérifier votre adresse email :

    @component('mail::button', ['url' => $verificationLink])
        Vérifier mon adresse email
    @endcomponent

    Si vous n'avez pas créer de compte il n'y a rien à faire.

    Merci,<br>
    {{ config('app.name') }}
@endcomponent
