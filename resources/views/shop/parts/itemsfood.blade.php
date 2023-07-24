<div class="row">
    @foreach($items as $item)
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <!-- Add image here -->
                <img src="{{ $item->picture_url }}" class="card-img-top card-img" alt="{{ $item->model_name }}">

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <!-- Title at the left below the image -->
                        <h5 class="card-title">{{ $item->model_name }}</h5>
                        <!-- Price at the right below the image -->
                        <p class="card-text">{{ $item->selling_price }}€</p>
                    </div>
                    <!-- Add to Cart button -->
                    <button class="btn btn-primary add-to-cart" data-id="{{ $item->id }}">Ajouter au panier</button>
                    <!-- View Item button -->
                    <a href="{{ route('item.show', $item->id) }}" class="btn btn-secondary">Voir l'article</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
