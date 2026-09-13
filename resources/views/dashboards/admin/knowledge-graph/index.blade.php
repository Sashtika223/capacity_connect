@extends('layouts.admin')

@section('title', 'Knowledge Graph')

@section('admin_content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-bold"><i class="bi bi-diagram-3 text-primary me-2"></i> Competency Knowledge Graph</h2>
        <p class="text-muted mb-0">Visual map of employees, skills, courses, certifications and departments.</p>
    </div>
</div>

{{-- Legend & Controls --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3 d-flex align-items-center flex-wrap gap-3">
                <span class="fw-bold text-muted small text-uppercase me-2">Legend:</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#3b82f6;font-size:.85rem;"><i class="bi bi-person-fill me-1"></i> Trainee</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#8b5cf6;font-size:.85rem;"><i class="bi bi-person-badge me-1"></i> Trainer</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#10b981;font-size:.85rem;"><i class="bi bi-star-fill me-1"></i> Competency</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#f59e0b;font-size:.85rem;"><i class="bi bi-book-fill me-1"></i> Course</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#ef4444;font-size:.85rem;"><i class="bi bi-award-fill me-1"></i> Certification</span>
                <span class="badge rounded-pill px-3 py-2" style="background:#6b7280;font-size:.85rem;"><i class="bi bi-building me-1"></i> Department</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-3 d-flex align-items-center gap-2">
                <input type="text" id="search-input" class="form-control rounded-pill border-primary" placeholder="Filter by keyword, e.g. 'Cyclone'...">
                <button id="search-btn" class="btn btn-primary rounded-pill px-3 flex-shrink-0">
                    <i class="bi bi-search"></i>
                </button>
                <button id="reset-btn" class="btn btn-outline-secondary rounded-pill px-3 flex-shrink-0" title="Reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Graph Canvas --}}
<div class="card border-0 shadow-sm rounded-4 mb-4" style="position: relative;">
    <div id="graph-loading" class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center rounded-4" style="background:rgba(255,255,255,0.85); z-index:10;">
        <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status" style="width:3rem;height:3rem;"></div>
            <p class="fw-bold text-muted">Building Knowledge Graph…</p>
        </div>
    </div>
    <div id="knowledge-graph" style="height:650px; border-radius: 1rem;"></div>
</div>

{{-- Node Info Panel (hidden until click) --}}
<div id="node-info" class="card border-0 shadow rounded-4 d-none mb-4" style="border-left: 4px solid #3b82f6 !important;">
    <div class="card-body p-4">
        <h5 id="node-info-title" class="fw-bold mb-1"></h5>
        <p id="node-info-detail" class="text-muted mb-0" style="white-space: pre-line;"></p>
    </div>
</div>

{{-- Vis.js CDN --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis-network/9.1.9/vis-network.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis-network/9.1.9/vis-network.min.js"></script>

<script>
let network = null;
let allNodes = [];
let allEdges = [];

const groupColors = {
    trainee:      { background: '#3b82f6', border: '#1d4ed8', highlight: { background: '#60a5fa', border: '#1d4ed8' }, font: { color: '#ffffff' } },
    trainer:      { background: '#8b5cf6', border: '#6d28d9', highlight: { background: '#a78bfa', border: '#6d28d9' }, font: { color: '#ffffff' } },
    competency:   { background: '#10b981', border: '#065f46', highlight: { background: '#34d399', border: '#065f46' }, font: { color: '#ffffff' } },
    course:       { background: '#f59e0b', border: '#92400e', highlight: { background: '#fcd34d', border: '#92400e' }, font: { color: '#000000' } },
    certification:{ background: '#ef4444', border: '#991b1b', highlight: { background: '#f87171', border: '#991b1b' }, font: { color: '#ffffff' } },
    department:   { background: '#6b7280', border: '#374151', highlight: { background: '#9ca3af', border: '#374151' }, font: { color: '#ffffff' } },
};

const options = {
    groups: groupColors,
    nodes: {
        shape: 'dot',
        size: 18,
        font: { size: 13, face: 'Inter, sans-serif' },
        borderWidth: 2,
        shadow: true,
    },
    edges: {
        font: { size: 10, align: 'middle', color: '#6b7280' },
        color: { color: '#d1d5db', highlight: '#3b82f6' },
        smooth: { type: 'dynamic' },
        arrows: { to: { scaleFactor: 0.6 } },
    },
    physics: {
        enabled: true,
        barnesHut: {
            gravitationalConstant: -5000,
            centralGravity: 0.15,
            springLength: 180,
            springConstant: 0.04,
        },
        stabilization: { iterations: 200, updateInterval: 25 },
    },
    interaction: {
        tooltipDelay: 200,
        hideEdgesOnDrag: true,
        navigationButtons: true,
        keyboard: true,
    },
};

function buildGraph(filter = '') {
    document.getElementById('graph-loading').classList.remove('d-none');

    const url = new URL('{{ route('admin.knowledge-graph.data') }}', window.location.origin);
    if (filter) url.searchParams.set('filter', filter);

    fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        allNodes = data.nodes;
        allEdges = data.edges;
        renderGraph(allNodes, allEdges);
    })
    .catch(() => {
        document.getElementById('graph-loading').classList.add('d-none');
        alert('Failed to load graph data. Please refresh the page.');
    });
}

function renderGraph(nodes, edges) {
    const container = document.getElementById('knowledge-graph');
    const dataset = {
        nodes: new vis.DataSet(nodes),
        edges: new vis.DataSet(edges),
    };

    if (network) {
        network.destroy();
    }

    network = new vis.Network(container, dataset, options);

    network.on('stabilizationIterationsDone', () => {
        document.getElementById('graph-loading').classList.add('d-none');
        network.fit({ animation: { duration: 1000, easingFunction: 'easeInOutQuad' } });
    });

    network.on('click', function(params) {
        if (params.nodes.length > 0) {
            const nodeId = params.nodes[0];
            const node = nodes.find(n => n.id === nodeId);
            if (node) {
                document.getElementById('node-info-title').innerText = node.label;
                document.getElementById('node-info-detail').innerText = node.title || '';
                document.getElementById('node-info').classList.remove('d-none');
            }
        } else {
            document.getElementById('node-info').classList.add('d-none');
        }
    });
}

document.getElementById('search-btn').addEventListener('click', () => {
    const q = document.getElementById('search-input').value.trim();
    buildGraph(q);
});

document.getElementById('search-input').addEventListener('keyup', (e) => {
    if (e.key === 'Enter') document.getElementById('search-btn').click();
});

document.getElementById('reset-btn').addEventListener('click', () => {
    document.getElementById('search-input').value = '';
    buildGraph('');
});

// Load the full graph on page load
buildGraph();
</script>

<style>
#knowledge-graph { background: #f8fafc; border-radius: 1rem; }
.vis-button {
    background: white !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.375rem !important;
    color: #374151 !important;
}
</style>
@endsection
