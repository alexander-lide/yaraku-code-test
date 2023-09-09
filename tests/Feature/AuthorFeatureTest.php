<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorFeatureTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_can_view_authors_index_page():void
    {
        $response = $this->get('/authors');

        $response->assertStatus(200);
    }

    public function test_can_sort_authors_index_page():void
    {
        Author::factory()->count(5)->create();

        $data = [
            'sort' => 'id',
            'order' => 'desc'
        ];

        $authors = Author::all();

        $authors = $authors->sortCollection($data['sort'], $data['order'])->toArray();

        $response = $this->get(route('authors.index', $data));

        $sortedAuthors = $response->original->getData()['authors']->toArray();

        // $this->assertEquals($authors,$sortedAuthors); would return true even if the order was different
        // This is a work around where === will return false if order is different. == would return true with different order
        $sameOrder = false;
        if ($authors === $sortedAuthors)
        {
            $sameOrder = true;
        }

        $this->assertTrue($sameOrder);
    }

    public function test_can_create_an_author():void
    {
        $author = Author::factory()->make();

        $this->post('authors', $author->toArray());

        // Assert we have added one author
        $this->assertEquals(1,Author::all()->count());

        $response = $this->get('/authors');

        // Assert we can see the new author name on author index page
        $response->assertSee($author->name);
    }

    public function test_cannot_create_an_author_without_name():void
    {
        $data = [
            'name' => '',
        ];

        $author = Author::factory()->make($data);

        $response = $this->post('authors', $author->toArray());

        // Assert we get the name missing error
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }

    public function test_can_see_create_author_form():void
    {
        $response = $this->get('/authors/create');

        // Assert can see create author form
        $response->assertSee('Add Author');
    }

    public function test_can_see_author_in_edit_author_form():void
    {
        $author = Author::factory()->create();

        $response = $this->get('authors/'.$author->id.'/edit');

        // Assert we see the authors name in the form
        $response->assertSee($author->name);
    }

    public function test_can_edit_author():void
    {
        $oldAuthor = Author::factory()->create();
        $newAuthor = [
            'name' => fake()->unique()->name(),
        ];

        $this->put('authors/'.$oldAuthor->id, $newAuthor);

        $response = $this->get('authors');

        // Assert we see the new author and not the old one after updating author
        $response->assertSee($newAuthor['name']);
        $response->assertDontSee($oldAuthor->name);
    }

    public function test_cannot_edit_author_without_name():void
    {
        $oldAuthor = Author::factory()->create();
        $newAuthor = [
            'name' => '',
        ];

        $response = $this->put('authors/'.$oldAuthor->id, $newAuthor);

        // Assert we get the name missing error
        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }

    public function test_can_delete_author_without_books():void
    {
        $author = Author::factory()->create();

        $response = $this->delete('authors/'.$author->id);

        // Assert we redirect to authors and get delete success message
        $response->assertStatus(302);
        $response->assertRedirect('authors');
        $this->followRedirects($response)->assertSee('Successfully deleted author '.$author->name.'!');

        $deletedAuthor = Author::find($author->id);

        // Assert book is deleted
        $this->assertNull($deletedAuthor);
    }

    public function test_cannot_delete_author_with_books():void
    {
        $author = Author::factory()->create();

        $data = [
            'title' => fake()->unique()->words(3, true),
            'author_id' => $author->id
        ];

        Book::create($data);

        $response = $this->delete('authors/'.$author->id);

        // Assert we redirect to authors and see error message that we cannot delete user
        $response->assertStatus(302);
        $response->assertRedirect('authors');
        $this->followRedirects($response)->assertSee('Please remove author ' . $author->name . ' from any books before deleting!');

        $deletedAuthor = Author::find($author->id);

        // Assert book is not deleted
        $this->assertNotNull($deletedAuthor);
    }
}
