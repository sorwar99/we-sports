<div id="eventsField" class="row justify-content-center">
    @foreach($events as $event)
        @include('components.event-card')
    @endforeach
</div>