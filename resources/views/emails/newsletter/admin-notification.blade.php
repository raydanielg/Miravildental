<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Newsletter Subscriber</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <tr>
            <td style="background: linear-gradient(135deg, #16a34a, #9333ea); padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">New Newsletter Subscriber</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 30px;">
                <p style="color: #334155; font-size: 16px; line-height: 1.6;">A new user has subscribed to the Miravil Dental newsletter.</p>
                <table width="100%" cellpadding="10" cellspacing="0" style="margin-top: 20px; border-collapse: collapse;">
                    <tr style="background: #f8fafc;">
                        <td style="border: 1px solid #e2e8f0; font-weight: bold; color: #334155;">Email</td>
                        <td style="border: 1px solid #e2e8f0; color: #334155;">{{ $email }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #e2e8f0; font-weight: bold; color: #334155;">Subscribed At</td>
                        <td style="border: 1px solid #e2e8f0; color: #334155;">{{ $subscribedAt }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="background: #f1f5f9; padding: 20px; text-align: center;">
                <p style="color: #64748b; font-size: 12px; margin: 0;">&copy; {{ date('Y') }} Miravil Specialised Dental Centre. Admin Notification.</p>
            </td>
        </tr>
    </table>
</body>
</html>
