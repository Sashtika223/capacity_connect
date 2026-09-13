<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Trainer Registration Request Rejected</title>
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
                <h3 style="margin-top: 0; color: #000000; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">Trainer Registration Request Rejected</h3>
                
                <p style="font-size: 15px; line-height: 1.6; color: #222222;">
                    Your request to register as a Trainer on the CapacityConnect portal has been rejected by the Admin.
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #222222;">
                    You currently cannot access the Trainer portal.
                </p>

                @if(!empty($rejectionReason))
                    <div style="background-color: #F8F5E9; border-left: 4px solid #000000; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                        <h4 style="margin: 0 0 5px 0; font-size: 14px; color: #000000; font-weight: bold;">Rejection Reason:</h4>
                        <p style="margin: 0; font-size: 14px; color: #333333; line-height: 1.5;">{{ $rejectionReason }}</p>
                    </div>
                @endif
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
