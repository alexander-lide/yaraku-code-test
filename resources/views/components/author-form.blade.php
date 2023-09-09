<div class="card card-body bg-light mb-3">
    <form method="POST" action="{{ $type === 'edit' ? '/authors/'.$author->id : '/authors' }}">
        @csrf
        @if ($type === 'edit')
            @method('put')
            <x-form-input type="hidden" name="id" value="{{ $author->id }}"/>
        @endif
        <x-form-input type="text" name="name" value="{{ $author->name }}" label="Name"/>
        <input class="btn btn-primary" type="submit" value="{{ $type === 'create' ? 'Add author' : 'Edit author' }}">
    </form>
</div>