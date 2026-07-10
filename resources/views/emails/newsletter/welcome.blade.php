<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Miravil Dental</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <tr>
            <td style="background: linear-gradient(135deg, #16a34a, #9333ea); padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome to Miravil Dental!</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <p style="color: #334155; font-size: 16px; line-height: 1.6;">Dear Subscriber,</p>
                <p style="color: #334155; font-size: 16px; line-height: 1.6;">Thank you for subscribing to our newsletter at <strong>Miravil Specialised Dental Centre</strong>. You will now receive the latest dental care tips, clinic updates, and special offers directly in your inbox.</p>
                <p style="color: #334155; font-size: 16px; line-height: 1.6;">Your email: <strong>{{ $email }}</strong></p>
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ url('/') }}" style="background: linear-gradient(135deg, #16a34a, #9333ea); color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">Visit Our Website</a>
                </div>
                <p style="color: #64748b; font-size: 14px; line-height: 1.6;">If you have any questions, feel free to reply to this email or contact us.</p>
            </td>
        </tr>
        <tr>
            <td style="background: #f1f5f9; padding: 20px; text-align: center;">
                <p style="color: #64748b; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} Miravil Specialised Dental Centre. All rights reserved.</p>
            </td>
        </tr>
    </table>
</body>
</html>
