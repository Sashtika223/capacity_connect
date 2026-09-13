<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $certificate->course->title }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=Inter:wght@400;500;600&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        
        .certificate-container {
            width: 1056px; /* 11 inches at 96 DPI */
            height: 816px; /* 8.5 inches at 96 DPI */
            background-color: #fff;
            padding: 40px;
            box-sizing: border-box;
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            position: relative;
        }

        .certificate-border {
            width: 100%;
            height: 100%;
            border: 2px solid #0d6efd;
            padding: 10px;
            box-sizing: border-box;
        }

        .certificate-inner {
            width: 100%;
            height: 100%;
            border: 1px solid #0d6efd;
            box-sizing: border-box;
            padding: 50px;
            text-align: center;
            position: relative;
            background-image: radial-gradient(#f8f9fa 20%, transparent 20%);
            background-size: 20px 20px;
            background-position: 0 0;
        }
        
        /* Corner ornaments */
        .corner { position: absolute; width: 60px; height: 60px; border: 4px solid #0d6efd; }
        .tl { top: -4px; left: -4px; border-bottom: none; border-right: none; }
        .tr { top: -4px; right: -4px; border-bottom: none; border-left: none; }
        .bl { bottom: -4px; left: -4px; border-top: none; border-right: none; }
        .br { bottom: -4px; right: -4px; border-top: none; border-left: none; }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #0d6efd;
            letter-spacing: 2px;
            margin-bottom: 40px;
        }

        .title {
            font-family: 'Playfair Display', serif;
            font-size: 64px;
            font-style: italic;
            color: #212529;
            margin: 0 0 20px 0;
        }

        .subtitle {
            font-size: 18px;
            color: #6c757d;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 40px;
        }

        .presented-to {
            font-size: 20px;
            color: #495057;
            margin-bottom: 20px;
        }

        .name {
            font-family: 'Playfair Display', serif;
            font-size: 54px;
            font-weight: 700;
            color: #0d6efd;
            border-bottom: 2px solid #e9ecef;
            display: inline-block;
            padding: 0 40px 10px;
            margin-bottom: 30px;
        }

        .reason {
            font-size: 20px;
            color: #495057;
            max-width: 600px;
            margin: 0 auto 50px;
            line-height: 1.6;
        }

        .course-name {
            font-weight: 600;
            color: #212529;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            padding: 0 50px;
        }

        .signature-block {
            text-align: center;
            width: 250px;
        }

        .signature {
            font-family: 'Pinyon Script', cursive;
            font-size: 40px;
            color: #000;
            margin-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .signature-title {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .seal {
            width: 120px;
            height: 120px;
            background: #0d6efd;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(13,110,253,0.3);
            border: 4px double #fff;
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
        }

        .meta-info {
            position: absolute;
            bottom: 20px;
            right: 20px;
            text-align: right;
            font-size: 12px;
            color: #adb5bd;
        }

        .meta-info a {
            color: #adb5bd;
            text-decoration: none;
        }

        .print-btn {
            position: fixed;
            top: 30px;
            right: 30px;
            background: #0d6efd;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(13,110,253,0.3);
            transition: transform 0.2s;
            font-family: 'Inter', sans-serif;
        }

        .print-btn:hover {
            transform: translateY(-2px);
        }

        .back-btn {
            position: fixed;
            top: 30px;
            left: 30px;
            background: white;
            color: #212529;
            border: 1px solid #dee2e6;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body { background: none; padding: 0; }
            .certificate-container { box-shadow: none; width: 100%; height: 100vh; padding: 20px; }
            .print-btn, .back-btn { display: none; }
        }
    </style>
</head>
<body>

    <a href="{{ route('trainee.certificates') }}" class="back-btn">&larr; Back to Dashboard</a>
    <button class="print-btn" onclick="window.print()">Print / Save PDF</button>

    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-inner">
                <div class="corner tl"></div>
                <div class="corner tr"></div>
                <div class="corner bl"></div>
                <div class="corner br"></div>

                <div class="logo">CAPACITY CONNECT</div>
                
                <h1 class="title">Certificate of Completion</h1>
                <div class="subtitle">Awarded For Excellence</div>

                <div class="presented-to">This is to proudly certify that</div>
                <div class="name">{{ $certificate->user->name }}</div>

                <div class="reason">
                    has successfully completed the comprehensive training program for <br>
                    <span class="course-name">{{ $certificate->course->title }}</span><br>
                    and has satisfied all academic and assessment requirements.
                </div>

                <div class="footer">
                    <div class="signature-block">
                        <div class="signature-title" style="border:none; border-bottom:1px solid #dee2e6; font-size:18px; text-transform:none; font-family: 'Inter', sans-serif; margin-bottom:5px;">{{ $certificate->issue_date->format('F d, Y') }}</div>
                        <div class="signature-title">Date of Issue</div>
                    </div>
                    
                    <div class="signature-block">
                        <div class="signature">{{ $certificate->course->trainer->name ?? 'Administrator' }}</div>
                        <div class="signature-title">Course Instructor</div>
                    </div>
                </div>

                <div class="seal">
                    Official<br>Seal
                </div>

                <div class="meta-info">
                    Certificate ID: <strong>{{ $certificate->certificate_id }}</strong><br>
                    Verify at: <a href="{{ route('verify.certificate', $certificate->certificate_id) }}" target="_blank">{{ request()->getHost() }}/verify</a><br>
                    @if($certificate->score) Score: {{ $certificate->score }}% @endif
                </div>

            </div>
        </div>
    </div>

</body>
</html>
