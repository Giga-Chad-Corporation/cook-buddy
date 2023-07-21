@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mt-5">Choisissez un Plan</h1>

        <div class="row">

            @foreach ($plans as $plan)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $plan->name }}</h5>
                            <ul class="list-group">
                                <li class="list-group-item">Prix Mensuel: {{ $plan->monthly_price ? $plan->monthly_price.' €' : 'Gratuit' }}</li>
                                <li class="list-group-item">Prix Annuel: {{ $plan->annual_price ? $plan->annual_price.' €' : 'Gratuit' }}</li>
                                <li class="list-group-item">Publicité: {{ $plan->has_ads ? 'Oui' : 'Non' }}</li>
                                <li class="list-group-item">Commenter et publier des avis: {{ $plan->can_comment ? 'Autorisés' : 'Non autorisés' }}</li>
                                <li class="list-group-item">Accès aux leçons: {{ $plan->name === 'Master' ? 'Illimité' : ($plan->lesson_access !== null ? ($plan->lesson_access === 0 ? 'Illimité' : $plan->lesson_access) : 'N/A') }}</li>
                                <li class="list-group-item">Accès au chat avec les chefs: {{ $plan->has_chat_access ? 'Oui' : 'Non' }}</li>
                                <li class="list-group-item">Reduction permanante en boutique: {{ $plan->boutique_discount }}%</li>
                                <li class="list-group-item">Livraison gratuite boutique: {{ $plan->boutique_free_shipping ? 'Oui' : 'Non' }}</li>
                                <li class="list-group-item">Réservation d'espaces cuisine: {{ $plan->has_cooking_space ? 'Oui' : 'Non' }}</li>
                                <li class="list-group-item">Invitation aux événements: {{ $plan->invitation_to_events ? 'Oui' : 'Non' }}</li>
                                <li class="list-group-item">Remise renouvellement d'abonnement: {{ $plan->renewal_discount !== null ? $plan->renewal_discount.'%' : 'Aucune' }}</li>
                            </ul>

                            @if ($currentUserPlan && $currentUserPlan->id === $plan->id)
                                <a href="{{ route('user.profile') }}" class="btn btn-primary mt-2">Rester avec ce Plan</a>
                            @else
                                @if($plan->monthly_price > 0)
                                    <form action="{{ route('create-checkout-session', ['planId' => $plan->id, 'type' => 'monthly']) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary mt-2" type="submit">Choisir plan mensuel</button>
                                    </form>
                                @endif
                                @if($plan->annual_price > 0)
                                        <form action="{{ route('create-checkout-session', ['planId' => $plan->id, 'type' => 'annual']) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary mt-2" type="submit">Choisir plan annuel</button>
                                    </form>
                                @endif
                                @if($plan->monthly_price <= 0 && $plan->annual_price <= 0)
                                    <a href="{{ route('subscribe.free', ['planId' => $plan->id]) }}" class="btn btn-primary mt-2">Choisir le Plan</a>
                                @endif

                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
    </script>
@endsection
