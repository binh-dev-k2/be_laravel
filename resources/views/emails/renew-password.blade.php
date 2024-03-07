@component('mail::message')
    # Renew Password

    Don't share it with anyone. Your new password is {{ $password }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
