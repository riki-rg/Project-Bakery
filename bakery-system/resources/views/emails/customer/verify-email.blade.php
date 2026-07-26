@component('mail::message')
# Verifikasi Email Anda

Halo {{ $name }}!

Terima kasih sudah mendaftar di Golden Hearth. Silakan verifikasi email Anda dengan mengklik tombol di bawah ini:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Jika Anda tidak merasa membuat akun, Anda bisa mengabaikan email ini.

Terima kasih,<br>
**Tim Golden Hearth**
@endcomponent
