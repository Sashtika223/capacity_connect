<div class="col-md-4 col-sm-6 mb-4">
    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4">
        <h6 class="text-muted fw-bold text-uppercase">{{ $title }}</h6>
        <h2 class="display-5 fw-bolder text-primary mb-0">{{ $value }}</h2>
        @if(isset($subtitle))
            <p class="text-muted mt-2 mb-0 small">{{ $subtitle }}</p>
        @endif
    </div>
</div>
