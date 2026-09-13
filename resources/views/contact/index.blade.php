@extends('layouts.app')

@section('title', 'Contact Us - CapacityConnect National Headquarters')

@section('content')
<!-- Page Header Banner -->
<section class="py-5 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #090D16 0%, #0F172A 50%, #1E3A8A 100%);">
    <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100 opacity-20 pointer-events-none" style="background: radial-gradient(circle at 70% 20%, rgba(245, 158, 11, 0.4) 0%, transparent 60%);"></div>
    <div class="container py-lg-4 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-20 text-warning small fw-bold mb-3">
                    <i class="bi bi-headset"></i> Executive Contact & Communications Desk
                </div>
                <h1 class="display-5 fw-extrabold text-white mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Get in Touch with Headquarters
                </h1>
                <p class="lead text-white-50 mb-0 fs-5" style="max-width: 680px;">
                    Have questions about workforce certifications, disaster response training, enterprise API integration, or institutional onboarding? Our team is available 24/7.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="p-3.5 bg-white bg-opacity-10 backdrop-blur rounded-4 border border-white border-opacity-15 text-start d-inline-block shadow-lg">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="bi bi-telephone-outbound-fill"></i>
                        </div>
                        <div>
                            <small class="text-white-50 d-block text-uppercase fw-bold fs-7">Emergency Ops Support</small>
                            <span class="fs-5 fw-bold text-white">+1 (800) 555-0199</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show p-4 rounded-4 shadow-sm border-0 mb-5 d-flex align-items-center gap-3" role="alert" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-left: 5px solid #10B981 !important;">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.35rem;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <h5 class="fw-bold text-success-emphasis mb-1">Message Delivered Successfully</h5>
                <p class="mb-0 text-success-emphasis text-opacity-90 fs-6">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 g-xl-5">
        <!-- Contact Form Section -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 p-md-5 border-bottom border-light">
                    <h3 class="fw-bold text-slate-900 mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">Send an Inquiry</h3>
                    <p class="text-muted mb-0">Fill out the official request form below. An assigned response officer will review and reply via email.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold text-dark fs-7 text-uppercase">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" id="name" class="form-control border-start-0 bg-light @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="e.g. Dr. Sarah Jenkins" required>
                                </div>
                                @error('name')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold text-dark fs-7 text-uppercase">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control border-start-0 bg-light @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="e.g. s.jenkins@agency.gov" required>
                                </div>
                                @error('email')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="department" class="form-label fw-bold text-dark fs-7 text-uppercase">Department / Organization <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                    <select name="department" id="department" class="form-select border-start-0 bg-light @error('department') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select Department...</option>
                                        <option value="Disaster Response & Management" {{ old('department') == 'Disaster Response & Management' ? 'selected' : '' }}>Disaster Response & Management</option>
                                        <option value="Healthcare & First Responders" {{ old('department') == 'Healthcare & First Responders' ? 'selected' : '' }}>Healthcare & First Responders</option>
                                        <option value="Trainer Accreditation" {{ old('department') == 'Trainer Accreditation' ? 'selected' : '' }}>Trainer Accreditation</option>
                                        <option value="Enterprise LMS Administration" {{ old('department') == 'Enterprise LMS Administration' ? 'selected' : '' }}>Enterprise LMS Administration</option>
                                        <option value="Public Inquiries" {{ old('department') == 'Public Inquiries' ? 'selected' : '' }}>Public & General Inquiries</option>
                                    </select>
                                </div>
                                @error('department')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="subject" class="form-label fw-bold text-dark fs-7 text-uppercase">Inquiry Subject <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="subject" id="subject" class="form-control border-start-0 bg-light @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="e.g. Trainer Certification Request" required>
                                </div>
                                @error('subject')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-bold text-dark fs-7 text-uppercase">Message Details <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="5" class="form-control bg-light @error('message') is-invalid @enderror" placeholder="Describe your inquiry in detail..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-12 pt-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark w-100 py-3 rounded-3 shadow-md d-inline-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-send-fill"></i> Submit Official Inquiry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Directives & Directory Sidebar -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-4">
                <!-- Card 1: Main HQ Location -->
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); color: #CBD5E1;">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 bg-warning text-dark d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-white mb-0">National Headquarters</h5>
                            <small class="text-white-50">CapacityConnect Operations HQ</small>
                        </div>
                    </div>
                    <p class="mb-3 text-slate-300 fs-6">
                        100 Federal Capacity Plaza, Suite 800<br>
                        Emergency Operations Center, DC 20001
                    </p>
                    <div class="pt-2 border-top border-slate-700 d-flex justify-content-between text-white-50 small">
                        <span><i class="bi bi-clock-history me-1 text-warning"></i> Desk Hours: 24/7 Active</span>
                        <span><i class="bi bi-shield-check me-1 text-success"></i> Secure Campus</span>
                    </div>
                </div>

                <!-- Card 2: Contact Options -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white border border-light">
                    <h5 class="fw-bold text-slate-900 mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">Directory Channels</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-envelope-open-fill fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-semibold text-uppercase fs-7">General Inquiries</small>
                                <a href="mailto:info@capacityconnect.gov" class="fw-bold text-slate-900 text-decoration-none">info@capacityconnect.gov</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-patch-check-fill fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-semibold text-uppercase fs-7">Trainer Accreditation Desk</small>
                                <a href="mailto:trainers@capacityconnect.gov" class="fw-bold text-slate-900 text-decoration-none">trainers@capacityconnect.gov</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                <i class="bi bi-shield-exclamation fs-5"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-semibold text-uppercase fs-7">Emergency Response Hotline</small>
                                <span class="fw-bold text-slate-900">+1 (800) 555-0199</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: AI Assistant Quick Action -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-white" style="background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%);">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <i class="bi bi-robot fs-2 text-warning"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-white">Need Instant AI Guidance?</h6>
                            <small class="text-white-50">Gemini AI Assistant is live on this portal</small>
                        </div>
                    </div>
                    <p class="small text-white-50 mb-3">Ask Gemini AI any question about courses, certifications, assessment tests, or platform features right now.</p>
                    <button type="button" onclick="toggleAiAssistant()" class="btn btn-warning btn-sm fw-bold text-dark rounded-pill px-3.5 py-2 align-self-start">
                        <i class="bi bi-chat-dots-fill me-1"></i> Open Gemini AI Chat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
