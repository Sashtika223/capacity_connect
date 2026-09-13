@extends('layouts.trainee')

@section('title', 'Notifications')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Notifications</h2>
    
    @if(auth()->user()->unreadNotifications->count() > 0)
    <form action="{{ route('trainee.notifications.markAllAsRead') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill px-3">Mark All as Read</button>
    </form>
    @endif
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush rounded-4">
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $data = $notification->data;
                    @endphp
                    <div class="list-group-item list-group-item-action p-4 {{ $isUnread ? 'bg-primary bg-opacity-10' : '' }}">
                        <div class="d-flex w-100 justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="mt-1">
                                    @if($data['type'] === 'enrollment')
                                        <i class="bi bi-person-plus-fill text-primary fs-4"></i>
                                    @elseif($data['type'] === 'completion')
                                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                    @elseif($data['type'] === 'certificate')
                                        <i class="bi bi-patch-check-fill text-warning fs-4"></i>
                                    @elseif($data['type'] === 'content')
                                        <i class="bi bi-file-earmark-play-fill text-info fs-4"></i>
                                    @else
                                        <i class="bi bi-bell-fill text-secondary fs-4"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold {{ $isUnread ? 'text-dark' : 'text-muted' }}">
                                        {{ $data['title'] ?? 'Notification' }}
                                    </h6>
                                    <p class="mb-1 {{ $isUnread ? 'text-dark' : 'text-muted' }}">{{ $data['message'] ?? '' }}</p>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            @if(isset($data['url']))
                                <a href="{{ route('trainee.notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">View</a>
                            @elseif($isUnread)
                                <a href="{{ route('trainee.notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Mark Read</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-bell-slash text-muted fs-1 mb-3 d-block"></i>
                <h5 class="text-muted">No notifications yet</h5>
                <p class="text-muted mb-0">We'll notify you when something important happens.</p>
            </div>
        @endif
    </div>
    @if($notifications->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
