<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListFeatureTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function test_can_view_list_index_page():void
    {
        $response = $this->get('/list');

        $response->assertStatus(200);
    }

    public function test_cannot_post_list_form_without_includes():void
    {
        $data = [
            'include' => [],
            'type' => 'csv'
        ];

        $response = $this->post('list', $data);

        // Assert we get the name missing error
        $response->assertSessionHasErrors([
            'include' => 'You must choose either Titles or Authors to export (or both).'
        ]);
    }

    public function test_cannot_export_without_books():void
    {
        $data = [
            'include' => ['title'],
            'type' => 'csv'
        ];

        $response = $this->post('list', $data);

        // Assert we get redirected back to list page with error message if we have no books that we can export
        $response->assertStatus(302);
        $response->assertRedirect('list');
        $this->followRedirects($response)->assertSee('Nothing to export!');
    }

    public function test_can_export_csv():void
    {
        Book::factory()->count(5)->create();
        // Fetch book to test against csv file
        $book = Book::all()->random();

        $data = [
            'include' => ['title' => 'on'],
            'type' => 'csv'
        ];

        $response = $this->post('/list', $data);

        // Assert we get csv file that contains title from test book, but not the author
        $this->assertEquals('attachment; filename=titles.csv', $response->headers->get('content-disposition'));        
        $this->assertStringContainsString($book->title, $response->streamedContent());
        $this->assertStringNotContainsString($book->author->name, $response->streamedContent());
    }

    public function test_can_export_xml():void
    {
        Book::factory()->count(5)->create();

        $data = [
            'include' => ['title' => 'on', 'author' => 'on'],
            'type' => 'xml'
        ];

        $response = $this->post('/list', $data);

        // Assert we get 200 status and empty content when getting xml as DomDocument
        $response->assertStatus(200);
        $this->assertEquals('', $response->getContent());
    }
}
