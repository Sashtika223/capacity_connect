<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainer Registration Approved</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #FFFDF2; color: #000000; margin: 0; padding: 30px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #FFFFFF; border: 1px solid rgba(0,0,0,0.15); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <!-- Header -->
        <tr>
            <td style="background-color: #000000; color: #FFFDF2; padding: 25px; text-align: center;">
                <h2 style="margin: 0; font-family: 'Plus Jakarta Sans', Arial, sans-serif; font-weight: 800; letter-spacing: -0.02em;">Capacity<span style="color: #FFFDF2;">Connect</span></h2>
                <p style="margin: 5px 0 0 0; font-size: 13px; color: #E2DFD2;">Digital Capacity Building & Learning Management Portal</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px;">
                <h3 style="margin-top: 0; color: #000000; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">Trainer Registration Approved</h3>
                
                <p style="font-size: 15px; line-height: 1.6; color: #222222;">
                    Your trainer registration request has been accepted by the Admin.
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #222222;">
                    You are now approved as a Trainer on the CapacityConnect portal.
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #222222;">
                    You can now log in to the portal using your registered email address and password.
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #000000; font-weight: bold; margin-top: 25px;">
                    Welcome to CapacityConnect.
                </p>

                <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                    <a href="{{ route('login') }}" style="background-color: #000000; color: #FFFDF2; padding: 14px 32px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block; font-size: 15px;">
                        Login to Portal
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #F8F5E9; padding: 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.08); font-size: 12px; color: #555555;">
                &copy; {{ date('Y') }} CapacityConnect Portal. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
