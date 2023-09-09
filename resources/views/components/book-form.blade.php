<div class="card card-body bg-light mb-3">
    <form method="POST" action="{{ $type === 'edit' ? '/books/'.$book->id : '/books' }}">
        @csrf
        @if ($type === 'edit')
            @method('put')
            <x-form-input type="hidden" name="id" value="{{ $book->id }}"/>
        @endif
        <x-form-input type="text" name="title" value="{{ $book->title }}" label="Title"/>
        <x-form-select name="author_id" :$options selected="{{ $author->id }}" label="Author"/>
        <input class="btn btn-primary" type="submit" value="{{ $type === 'create' ? 'Add book' : 'Edit book' }}">
    </form>
</div>