<div class="row">
    @if($items->isEmpty())
        <div class="col-12">
            <p>No items found.</p>
        </div>
    @else
        @foreach($items as $item)
            <div class="col-md-4">
                <div class="card mb-4 h-100">
                    <img src="{{ $item->picture_url }}" class="card-img-top card-img" alt="{{ $item->model_name }}">

                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">{{ $item->model_name }}</h5>
                            <p class="card-text">{{ $item->selling_price }}€</p>
                        </div>
                        <button class="btn btn-primary add-to-cart" data-id="{{ $item->id }}">Ajouter au panier</button>
                        <a href="{{ route('item.show', $item->id) }}" class="btn btn-secondary">Voir l'article</a>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
