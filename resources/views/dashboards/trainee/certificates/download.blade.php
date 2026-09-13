<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate {{ $certificate->certificate_number }} - CapacityConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; margin: 0; padding: 0; }
            .certificate-box { border: 15px solid #1a365d !important; box-shadow: none !important; }
        }
        body { background: #f4f6f9; font-family: 'Georgia', serif; }
        .certificate-box {
            background: #ffffff;
            border: 15px solid #1a365d;
            outline: 3px solid #d4af37;
            padding: 50px;
            max-width: 900px;
            margin: 30px auto;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .cert-header { font-family: 'Arial', sans-serif; letter-spacing: 2px; text-transform: uppercase; color: #1a365d; }
        .gold-line { height: 3px; background: linear-gradient(to right, #1a365d, #d4af37, #1a365d); margin: 20px 0; }
        .recipient-name { font-size: 2.8rem; font-weight: bold; color: #0d233a; border-bottom: 2px solid #d4af37; display: inline-block; padding-bottom: 5px; }
        .course-title { font-size: 1.8rem; font-weight: bold; color: #1a365d; font-family: 'Arial', sans-serif; }
    </style>
</head>
<body>

<div class="text-center my-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-lg shadow"><i class="fas fa-print me-2"></i> Print / Save as PDF Certificate</button>
    <a href="{{ route('trainee.certificates') }}" class="btn btn-outline-secondary btn-lg ms-2"><i class="fas fa-arrow-left me-1"></i> Back</a>
</div>

<div class="certificate-box text-center">
    <div class="mb-3">
        <i class="fas fa-award fa-4x text-warning"></i>
    </div>
    
    <div class="cert-header h4 fw-bold mb-1">CapacityConnect LMS</div>
    <div class="text-muted small text-uppercase mb-3" style="letter-spacing: 3px;">Digital Capacity Building & Emergency Response Portal</div>

    <div class="gold-line"></div>

    <h1 class="display-6 my-4" style="font-family: 'Georgia', serif; font-style: italic;">Certificate of Competency & Completion</h1>

    <p class="fs-5 text-muted mb-2">This is to officially certify that</p>
    
    <div class="recipient-name my-2">{{ $certificate->user->name }}</div>

    <p class="fs-5 text-muted mt-3 mb-2">has successfully completed all prescribed coursework and practical assessments for</p>

    <div class="course-title my-3">{{ $certificate->course->title }}</div>

    <p class="text-muted small px-5">Demonstrating verified proficiency, operational readiness, and core domain competencies as validated by CapacityConnect LMS Assessment Framework.</p>

    <div class="gold-line"></div>

    <div class="row mt-4 align-items-center text-start">
        <div class="col-4">
            <small class="text-muted d-block">Certificate Number:</small>
            <strong class="text-dark font-monospace">{{ $certificate->certificate_number }}</strong>
            <small class="text-muted d-block mt-2">Issued On: {{ $certificate->issue_date ? $certificate->issue_date->format('M d, Y') : now()->format('M d, Y') }}</small>
        </div>
        <div class="col-4 text-center">
            <div class="border p-2 d-inline-block rounded bg-light">
                <i class="fas fa-qrcode fa-3x text-dark"></i>
                <div class="small text-muted mt-1" style="font-size: 0.65rem;">Scan to Verify</div>
            </div>
        </div>
        <div class="col-4 text-end">
            <div class="mb-2" style="border-bottom: 1px dashed #6c757d; width: 140px; margin-left: auto;"></div>
            <strong class="d-block small text-dark">Director of Training</strong>
            <small class="text-muted">CapacityConnect Portal</small>
        </div>
    </div>
</div>

</body>
</html>
