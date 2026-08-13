<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email Anda</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <div style="background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; padding: 20px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">🔑 Verifikasi Email</h1>
            <p style="margin: 5px 0 0; font-size: 16px;">Lazismu Banten</p>
        </div>

        
        <div style="padding: 30px;">
            <h2 style="color: #2196F3; margin-top: 0;">Verifikasi Alamat Email Anda</h2>

            <p>Assalamu'alaikum wr. wb.,</p>

            <p><strong>{{ $user->name }}</strong>,</p>

            <p>Terima kasih telah mendaftar di Lazismu Banten. Untuk melengkapi proses pendaftaran, Anda perlu memverifikasi alamat email Anda.</p>

            <div style="background: #e3f2fd; border-left: 4px solid #2196F3; padding: 20px; margin: 20px 0; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #2196F3;">📧 Mengapa Verifikasi Email Penting?</h3>
                <ul>
                    <li style="margin-bottom: 10px;">Keamanan akun Anda</li>
                    <li style="margin-bottom: 10px;">Penerimaan notifikasi penting</li>
                    <li style="margin-bottom: 10px;">Pemulihan akun jika lupa password</li>
                    <li style="margin-bottom: 10px;">Akses penuh ke fitur sistem</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" style="background: #2196F3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">Verifikasi Email Sekarang</a>
            </div>

            <p style="background: #fff3e0; padding: 15px; border-radius: 5px; border-left: 4px solid #FF9800;">
                <strong>⚠️ Perhatian:</strong><br>
                Tautan verifikasi ini akan kedaluwarsa dalam 24 jam. Jika Anda tidak melakukan verifikasi dalam waktu tersebut, Anda perlu meminta tautan verifikasi baru.
            </p>

            <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:</p>
            <p style="word-break: break-all; background: #f5f5f5; padding: 10px; border-radius: 5px; font-size: 12px;">{{ $verificationUrl }}</p>

            <p style="margin-top: 30px;">Wallahu a'lam bishawab.</p>

            <p>Wassalamu'alaikum wr. wb.</p>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <p style="margin: 0;"><strong>Lazismu Banten</strong></p>
                <p style="margin: 5px 0 0;">Jl. Raya Banten</p>
                <p style="margin: 0;">Serang, Banten, Indonesia</p>
                <p style="margin: 5px 0 0;">Email: info@lazismu-banten.or.id</p>
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