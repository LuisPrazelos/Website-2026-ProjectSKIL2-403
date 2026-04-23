<div>
    <h2>Ingrediëntenbeheer</h2>
    <ul>
        @foreach($ingredients as $ingredient)
            <li>{{ $ingredient->name }}</li>
        @endforeach
    </ul>
    <!-- Voeg hier knoppen/forms toe voor CRUD-acties -->
</div>

