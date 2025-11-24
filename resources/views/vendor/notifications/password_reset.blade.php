@component('mail::message')

Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Link reset password akan kadaluarsa dalam {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} menit.

Jika Anda tidak meminta reset password, abaikan email ini.

Salam,<br>
Atmint Fastcili-TI.
{{-- {{ config('app.name') }} --}}
@endcomponent