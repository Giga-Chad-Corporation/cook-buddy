@extends('layouts.app')

@section('content')
    @if($hasMasterPlan)
        <h2>Prochains événements</h2>
        @forelse ($events as $event)
            <div>
                <h3>{{ $event->name }}</h3>
                <p>{{ $event->description }}</p>
                <p>Date: {{ $event->date }}</p>
            </div>
        @empty
            <p>Pas d'événements à venir</p>
        @endforelse
    @else
        <h2>Votre souscription ne vous permet pas de participer aux évènements</h2>
        <a href="{{ route('plans.index') }}" class="btn btn-primary">Changer mon plan</a>
    @endif
@endsection
