<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Registrasi</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #ffffff; color: #334155;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 40px 0 20px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; text-align: left;">
                    
                    <tr>
                        <td style="background-color: #2563eb; height: 4px; line-height: 4px; font-size: 4px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 0 20px 0;">
                            <table width="100%">
                                <tr>
                                    <td style="width: 50px;">
                                        <img src="https://via.placeholder.com/40x40" alt="Logo" width="40" height="40" style="display: block; border: 0;">
                                    </td>
                                    <td style="font-size: 18px; font-weight: bold; color: #1e293b; vertical-align: middle;">
                                        {{ config('app.name') }} - Kode OTP Verifikasi Akun
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 20px; font-size: 14px; line-height: 1.6;">
                            Hai <strong>{{ $name }}!</strong>,<br><br>
                            Mohon jangan memberitahu kode verifikasi ini kepada siapapun!<br>
                            Gunakan kode OTP berikut untuk melanjutkan registrasi akun pasien:
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 30px 0; background-color: #f8fafc; border-radius: 4px;">
                            <span style="font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #0f172a;">
                                {{ $otp }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 0; font-size: 14px; line-height: 1.6; border-bottom: 1px solid #e2e8f0;">
                            <p>Kode berlaku selama <strong>{{ $expiryMinutes }} menit</strong>.</p>
                            <p style="font-size: 13px; color: #64748b;">
                                Selain melalui media resmi kami, kami tidak pernah meminta informasi yang bersifat pribadi dan rahasia. 
                                <strong>JANGAN BERIKAN INFORMASI RAHASIA INI KEPADA SIAPAPUN.</strong>
                            </p>
                            <p>Terima kasih,<br><strong>Admin {{ config('app.name') }} </strong></p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top: 20px; font-size: 11px; color: #94a3b8; text-align: center;">
                            NB: Email ini dibuat otomatis, mohon untuk tidak membalas.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>