<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .token {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Permintaan Reset Password</h2>
        <p>Anda telah meminta reset password. Gunakan token berikut untuk melanjutkan:</p>
        <div class="token">{{ $token }}</div>
        <p>Token ini berlaku selama 15 menit. Abaikan email ini jika Anda tidak merasa melakukan permintaan reset password.</p>
        <div class="footer">
            &copy; {{ date('Y') }} Aplikasi Anda. All rights reserved.
        </div>
    </div>
</body>
</html>
