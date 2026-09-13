<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Trainer Registration Request</title>
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
                <h3 style="margin-top: 0; color: #000000; font-family: 'Plus Jakarta Sans', Arial, sans-serif;">New Trainer Registration Request</h3>
                <p style="font-size: 15px; line-height: 1.5; color: #222222;">A new user has registered as a Trainer and is awaiting your review and approval.</p>

                <table width="100%" border="0" cellspacing="0" cellpadding="10" style="background-color: #F8F5E9; border-radius: 8px; margin: 20px 0; border: 1px solid rgba(0,0,0,0.08);">
                    <tr>
                        <td width="35%" style="font-weight: bold; color: #000000;">Trainer Name:</td>
                        <td style="color: #111111;">{{ $trainer->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #000000;">Trainer Email:</td>
                        <td style="color: #111111;">{{ $trainer->email }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #000000;">Registration Date:</td>
                        <td style="color: #111111;">{{ $trainer->created_at ? $trainer->created_at->format('M d, Y - h:i A') : now()->format('M d, Y - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #000000;">Role:</td>
                        <td style="color: #111111; text-transform: capitalize;">{{ $trainer->role }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; color: #000000;">Current Status:</td>
                        <td>
                            <span style="background-color: #000000; color: #FFFDF2; padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                {{ ucfirst($trainer->trainer_status ?? 'Pending') }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('admin.trainer-requests.index') }}" style="background-color: #000000; color: #FFFDF2; padding: 14px 28px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block; font-size: 14px;">
                        View Trainer Requests
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #F8F5E9; padding: 20px; text-align: center; border-top: 1px solid rgba(0,0,0,0.08); font-size: 12px; color: #555555;">
                &copy; {{ date('Y') }} CapacityConnect System Notification. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
