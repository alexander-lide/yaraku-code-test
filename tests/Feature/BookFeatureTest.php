<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookFeatureTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_can_view_books_index_page():void
    {
        $response = $this->get('/books');

        $response->assertStatus(200);
    }

    public function test_can_search_books_index_page():void
    {
        Book::factory()->count(5)->create();
        $searchBook = Book::find(1);

        $data = [
            'title' => $searchBook->title,
            'author' => $searchBook->author->name
        ];

        $response = $this->get(route('books.index', $data));

        $foundBooks = $response->original->getData()['books'];

        // Assert we only get one book from our search and that it matches the search
        $this->assertEquals(1,$foundBooks->count());
        $foundBook = $foundBooks->first();
        $this->assertEquals($foundBook->title, $searchBook->title);
        $this->assertEquals($foundBook->author, $searchBook->author->name);
    }

    public function test_can_sort_books_index_page():void
    {
        Book::factory()->count(5)->create();

        $data = [
            'sort' => 'id',
            'order' => 'desc'
        ];

        $book = new Book();
        $books = $book->allBooksWithAuthors();

        $books = $books->sortCollection($data['sort'], $data['order'])->toArray();

        $response = $this->get(route('books.index', $data));

        $sortedBooks = $response->original->getData()['books']->toArray();

        // $this->assertEquals($books,$sortedBooks); would return true even if the order was different
        // This is a work around where === will return false if order is different. == would return true with different order
        $sameOrder = false;
        if ($books === $sortedBooks)
        {
            $sameOrder = true;
        }

        $this->assertTrue($sameOrder);
    }

    public function test_can_create_a_book():void
    {
        $book = Book::factory()->make();

        $this->post('books', $book->toArray());

        // Assert we have added one book
        $this->assertEquals(1,Book::all()->count());

        $response = $this->get('/books');

        // Assert we can see the new book title and author name on book index page
        $response->assertSee($book->title);
        $response->assertSee($book->author->name);
    }

    public function test_cannot_create_a_book_without_title():void
    {
        $data = [
            'title' => '',
            'author_id' => Author::factory()->create()->id
        ];

        $book = Book::factory()->make($data);

        $response = $this->post('books', $book->toArray());

        // Assert we get the title missing error
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.'
        ]);
    }

    public function test_can_see_create_book_form():void
    {
        $author = Author::factory()->create();
        $response = $this->get('/books/create');

        // Assert can see create book form
        $response->assertSee('Add Book');
        $response->assertSee($author->name);
    }

    public function test_create_book_warning_showing_if_no_authors():void
    {
        $response = $this->get('/books/create');

        // Assert the warning shows if trying to create book without any authors
        $response->assertSee('Before you can add books you need to create authors to add to the books.');
    }

    public function test_can_see_book_in_edit_book_form():void
    {
        $book = Book::factory()->create();

        $response = $this->get('books/'.$book->id.'/edit');

        // Assert we see the books title and author in the form
        $response->assertSee($book->title);
        $response->assertSee($book->author->name);
    }

    public function test_can_edit_book():void
    {
        $oldBook = Book::factory()->create();
        $author = Author::factory()->create();
        $newBook = [
            'title' => fake()->unique()->words(3, true),
            'author_id' => $author->id
        ];

        $this->put('books/'.$oldBook->id, $newBook);

        $response = $this->get('books');

        // Assert we see the new book and not the old one after updating book
        $response->assertSee($newBook['title']);
        $response->assertSee($author->name);
        $response->assertDontSee($oldBook->title);
        $response->assertDontSee($oldBook->author->name);
    }

    public function test_cannot_edit_book_without_title():void
    {
        $oldBook = Book::factory()->create();
        $author = Author::factory()->create();
        $newBook = [
            'title' => '',
            'author_id' => $author->id
        ];

        $response = $this->put('books/'.$oldBook->id, $newBook);

        // Assert we get the title missing error
        $response->assertSessionHasErrors([
            'title' => 'The title field is required.'
        ]);
    }

    public function test_can_delete_book():void
    {
        $book = Book::factory()->create();

        $response = $this->delete('books/'.$book->id);

        // Assert we redirect to books and get delete success message
        $response->assertStatus(302);
        $response->assertRedirect('books');
        $this->followRedirects($response)->assertSee('Successfully deleted book '.$book->title.'!');

        $deletedBook = Book::find($book->id);

        // Assert book is deleted
        $this->assertNull($deletedBook);
    }
}
