<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temporary Password</title>
</head>
<body style="font-family: Arial, sans-serif; background: #faf8ff; margin: 0; padding: 40px 20px;">
    <div style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 16px; padding: 40px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); text-align: center;">
        <h1 style="color: #8c52ff; font-size: 24px; margin: 0 0 8px;">PERFIT</h1>
        <p style="color: #666; font-size: 14px; margin: 0 0 24px;">Use this temporary password to sign in. Change it after logging in.</p>
        <div style="background: #f0e6ff; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <span style="font-size: 28px; font-weight: bold; color: #8c52ff; letter-spacing: 4px;">{{ $temporaryPassword }}</span>
        </div>
        <p style="color: #999; font-size: 12px; margin: 0;">If you didn't request this, ignore this email. Your current password remains valid.</p>
    </div>
</body>
</html>
