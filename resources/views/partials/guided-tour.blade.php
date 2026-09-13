<!-- Interactive Website Guided Tour Modal & Overlay Component -->
<style>
    .tour-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        z-index: 100000;
        animation: fadeInTour 0.3s ease;
    }
    @keyframes fadeInTour {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .tour-modal-card {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 520px;
        max-width: calc(100vw - 32px);
        background: #FFFFFF;
        border-radius: 24px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
        border: 2px solid #F59E0B;
        z-index: 100001;
        overflow: hidden;
    }
    .tour-card-header {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        color: #FFFFFF;
        padding: 24px;
        position: relative;
    }
    .tour-step-badge {
        background: rgba(245, 158, 11, 0.2);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.4);
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        display: inline-block;
        margin-bottom: 8px;
    }
    .tour-card-body {
        padding: 24px;
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .tour-card-footer {
        padding: 16px 24px;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>

<div class="tour-overlay" id="portalTourOverlay">
    <div class="tour-modal-card" id="portalTourCard">
        <div class="tour-card-header">
            <span class="tour-step-badge" id="tourStepBadge">STEP 1 OF 5</span>
            <h4 class="fw-extrabold text-white mb-1" id="tourStepTitle" style="font-family: 'Plus Jakarta Sans', sans-serif;">Welcome to CapacityConnect LMS</h4>
            <p class="text-white-50 small mb-0" id="tourStepSubtitle">National Capacity Building & Emergency Operations Portal</p>
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-4 shadow-none" onclick="stopPortalTour()"></button>
        </div>

        <div class="tour-card-body" id="tourStepContent">
            Centralized enterprise platform for workforce capacity assessment, interactive disaster response drills, competency risk management, and verifiable professional certifications.
        </div>

        <div class="tour-card-footer">
            <button type="button" class="btn btn-sm btn-link text-muted text-decoration-none" onclick="stopPortalTour()">Skip Tour</button>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="tourPrevBtn" onclick="prevTourStep()" style="display:none;">Previous</button>
                <button type="button" class="btn btn-sm btn-warning fw-bold text-dark rounded-pill px-4" id="tourNextBtn" onclick="nextTourStep()">Next Step <i class="bi bi-arrow-right ms-1"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    const tourSteps = [
        {
            badge: "STEP 1 OF 5",
            title: "Welcome to CapacityConnect LMS",
            subtitle: "National Capacity Building & Emergency Portal",
            content: "Welcome! CapacityConnect is a central workforce portal for capacity building, emergency response drills, competency risk tracking, and verifiable professional certifications."
        },
        {
            badge: "STEP 2 OF 5",
            title: "Top Emergency & Operations Bar",
            subtitle: "Real-time Telemetry & Communications",
            content: "The top operational bar displays live connectivity status (ONLINE / FIELD OFFLINE MODE), emergency response hotline numbers, and official social channels."
        },
        {
            badge: "STEP 3 OF 5",
            title: "Course Catalog & Subject Matchmaker",
            subtitle: "Learning & Domain Expert Discovery",
            content: "Explore published training courses, complete disaster drills, or use the **Subject Matchmaker** to connect organizations with certified domain experts and trainers."
        },
        {
            badge: "STEP 4 OF 5",
            title: "Role Control Center & Dashboards",
            subtitle: "Trainee, Trainer & Admin Control",
            content: "Access your personalized dashboard to track course progress, certificate renewals, competency gap matrices, and competency risk radar analytics."
        },
        {
            badge: "STEP 5 OF 5",
            title: "Gemini AI Assistant & Guided Assistance",
            subtitle: "Always Here to Help You",
            content: "Click the floating **Gemini AI Assistant** icon at the bottom-right of your screen anytime to ask questions, generate assessment tests, or relaunch this portal tour!"
        }
    ];

    let currentTourStep = 0;

    function startPortalTour() {
        currentTourStep = 0;
        renderTourStep();
        document.getElementById('portalTourOverlay').style.display = 'block';
    }

    function stopPortalTour() {
        document.getElementById('portalTourOverlay').style.display = 'none';
        localStorage.setItem('capacity_connect_tour_completed', 'true');
    }

    function renderTourStep() {
        const step = tourSteps[currentTourStep];
        document.getElementById('tourStepBadge').textContent = step.badge;
        document.getElementById('tourStepTitle').textContent = step.title;
        document.getElementById('tourStepSubtitle').textContent = step.subtitle;
        document.getElementById('tourStepContent').textContent = step.content;

        document.getElementById('tourPrevBtn').style.display = currentTourStep > 0 ? 'inline-block' : 'none';

        const nextBtn = document.getElementById('tourNextBtn');
        if (currentTourStep === tourSteps.length - 1) {
            nextBtn.innerHTML = 'Finish Tour <i class="bi bi-check-lg ms-1"></i>';
            nextBtn.onclick = stopPortalTour;
        } else {
            nextBtn.innerHTML = 'Next Step <i class="bi bi-arrow-right ms-1"></i>';
            nextBtn.onclick = nextTourStep;
        }
    }

    function nextTourStep() {
        if (currentTourStep < tourSteps.length - 1) {
            currentTourStep++;
            renderTourStep();
        }
    }

    function prevTourStep() {
        if (currentTourStep > 0) {
            currentTourStep--;
            renderTourStep();
        }
    }
</script>
