<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Akun Anda</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <div style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">🔒 Reset Password</h1>
            <p style="margin: 5px 0 0; font-size: 16px;">SIPZIS - Sistem Informasi Pengelolaan Zakat</p>
        </div>

        
        <div style="padding: 30px;">
            <h2 style="color: #f44336; margin-top: 0;">Reset Password Akun Anda</h2>

            <p>Assalamu'alaikum wr. wb.,</p>

            <p><strong>{{ $user->name }}</strong>,</p>

            <p>Kami menerima permintaan untuk mereset password akun Anda. Jika Anda tidak melakukan permintaan ini, abaikan email ini.</p>

            <div style="background: #ffebee; border-left: 4px solid #f44336; padding: 20px; margin: 20px 0; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #f44336;">🔐 Instruksi Reset Password</h3>
                <ol>
                    <li style="margin-bottom: 10px;">Klik tombol "Reset Password" di bawah ini</li>
                    <li style="margin-bottom: 10px;">Masukkan password baru Anda</li>
                    <li style="margin-bottom: 10px;">Konfirmasi password baru Anda</li>
                    <li style="margin-bottom: 10px;">Simpan perubahan</li>
                </ol>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('password/reset', $token) }}" style="background: #f44336; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Password</a>
            </div>

            <p style="background: #fff3e0; padding: 15px; border-radius: 5px; border-left: 4px solid #FF9800;">
                <strong>⚠️ Perhatian:</strong><br>
                Tautan reset password ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak menggunakan tautan ini dalam waktu tersebut, Anda perlu meminta reset password baru.
            </p>

            <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:</p>
            <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 5px; font-size: 12px;">{{ url('password/reset', $token) }}</p>

            <p style="margin-top: 30px;">Wallahu a'lam bishawab.</p>

            <p>Wassalamu'alaikum wr. wb.</p>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <p style="margin: 0;"><strong>Yayasan SIPZIS</strong></p>
                <p style="margin: 5px 0 0;">Jl. Contoh Alamat No. 123</p>
                <p style="margin: 0;">Jakarta, Indonesia</p>
                <p style="margin: 5px 0 0;">Email: info@sipzis.id</p>
            </div>
        </div>

        
        <div style="background: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #666;">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; <b>Created By :</b> — Kurniawan Dwi Prasetyo<br>
            Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>

</html>