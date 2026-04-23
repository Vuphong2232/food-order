<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Mã OTP đặt lại mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f8f5ef; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:16px; padding:24px; border:1px solid #eee;">
        <h2 style="color:#5b3b1f;">Đặt lại mật khẩu</h2>
        <p>Xin chào,</p>
        <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản: <strong>{{ $email }}</strong></p>
        <p>Mã xác nhận của bạn là:</p>

        <div style="font-size:32px; font-weight:bold; letter-spacing:8px; text-align:center; color:#e67e22; margin:24px 0;">
            {{ $code }}
        </div>

        <p>Mã có hiệu lực trong 10 phút.</p>
        <p>Nếu bạn không yêu cầu, hãy bỏ qua email này.</p>
    </div>
</body>
</html>