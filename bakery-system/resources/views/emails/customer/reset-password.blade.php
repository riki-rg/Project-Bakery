@component('mail::message')
# Reset Kata Sandi

Halo {{ $name }}!

Kami menerima permintaan untuk mengatur ulang kata sandi akun Golden Hearth Anda.

Klik tombol di bawah ini untuk mengatur kata sandi baru:

@component('mail::button', ['url' => $url])
Reset Kata Sandi
@endcomponent

Link ini akan kadaluarsa dalam 60 menit. Jika Anda tidak meminta reset kata sandi, Anda bisa mengabaikan email ini.

Terima kasih,<br>
**Tim Golden Hearth**
@endcomponent
