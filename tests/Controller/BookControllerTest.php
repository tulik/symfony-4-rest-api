<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookControllerTest extends AbstractWebTestCase
{
    /**
     * @var int
     */
    protected static $entityId;

    /**
     * @var string
     */
    protected static $isbn;

    public function testUnauthorizedCreateAction()
    {
        $this::$isbn = $this->faker->isbn13;

        $this->client->request(
            Request::METHOD_POST,
            '/books',
            [],
            [],
            [],
            json_encode([
                'isbn' => self::$isbn,
                'title' => 'Dolor similique aliquam.',
                'description' => 'Voluptatem voluptatem rerum vel error autem sunt ut reiciendis. Itaque numquam quam veniam id recusandae dolor totam. Necessitatibus amet eos ut enim ipsam.',
                'author' => 'Jade Carroll',
                'publicationDate' => '2000-09-04T17:58:04+00:00',
            ])
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testCreateAction()
    {
        $this->client->request(
            Request::METHOD_POST,
            '/books',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'isbn' => self::$isbn,
                'title' => 'Dolor similique aliquam.',
                'description' => 'Voluptatem voluptatem rerum vel error autem sunt ut reiciendis. Itaque numquam quam veniam id recusandae dolor totam. Necessitatibus amet eos ut enim ipsam.',
                'author' => 'Jade Carroll',
                'publicationDate' => '2000-09-04T17:58:04+00:00',
            ])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);

        self::$entityId = $responseContent['id'];
    }

    public function testBadRequestCreateAction()
    {
        $this->client->request(
            Request::METHOD_POST,
            '/books',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'isbn' => self::$isbn,
                'title' => 'Dolor similique aliquam.',
                'description' => 'Voluptatem voluptatem rerum vel error autem sunt ut reiciendis. Itaque numquam quam veniam id recusandae dolor totam. Necessitatibus amet eos ut enim ipsam.',
                'author' => 'Jade Carroll',
            ])
        );

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testUnauthorizedUpdateAction()
    {
        self::$isbn = $this->faker->isbn13;

        $this->client->request(
            Request::METHOD_PATCH,
            sprintf('/books/%d', self::$entityId),
            [],
            [],
            [],
            json_encode([
                'isbn' => self::$isbn,
            ])
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testUpdateAction()
    {
        $this->client->request(
            Request::METHOD_PATCH,
            sprintf('/books/%d', self::$entityId),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'isbn' => self::$isbn,
            ])
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('isbn', array_search(self::$isbn, $responseContent));
    }

    public function testNotFoundUpdateAction()
    {
        $this->client->request(
            Request::METHOD_PATCH,
            '/books/0',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'isbn' => self::$isbn,
            ])
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testListAction()
    {
        $this->client->request(Request::METHOD_GET, '/books');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('books', $this->client->getResponse()->getContent());
    }

    public function testFilterListAction()
    {
        $this->client->request(Request::METHOD_GET, sprintf('/books?book_filter[isbn]=%s', self::$isbn));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('books', $this->client->getResponse()->getContent());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('isbn', array_search(self::$isbn, $responseContent['books'][0]));
    }

    public function testShowAction()
    {
        $this->client->request(Request::METHOD_GET, sprintf('/books/%d', self::$entityId));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('id', array_search(self::$entityId, $responseContent));
    }

    public function testNotFoundShowAction()
    {
        $this->client->request(Request::METHOD_GET, '/books/0');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testUnauthorizedDeleteAction()
    {
        $this->client->request(Request::METHOD_DELETE, sprintf('/books/%d', self::$entityId));

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testDeleteAction()
    {
        $this->client->request(
            Request::METHOD_DELETE,
            sprintf('/books/%d', self::$entityId),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token]
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }

    public function testNotFoundDeleteAction()
    {
        $this->client->request(
            Request::METHOD_DELETE,
            '/books/0',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token]
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
