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

class ReviewControllerTest extends AbstractWebTestCase
{
    /**
     * @var int
     */
    protected static $entityId;

    /**
     * @var int
     */
    protected static $rating;

    public function testUnauthorizedCreateAction()
    {
        self::$rating = 9;

        $this->client->request(
            Request::METHOD_POST,
            '/reviews',
            [],
            [],
            [],
            json_encode([
                'body' => 'Ut accusantium ad facere qui est. Voluptas quae rerum voluptas perspiciatis molestiae voluptas assumenda. Nobis impedit laudantium eaque saepe quae.',
                'rating' => self::$rating,
                'publicationDate' => '2018-06-24T00:00:00+00:00',
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
            '/reviews',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'body' => 'Ut accusantium ad facere qui est. Voluptas quae rerum voluptas perspiciatis molestiae voluptas assumenda. Nobis impedit laudantium eaque saepe quae.',
                'rating' => self::$rating,
                'publicationDate' => '2018-06-24T00:00:00+00:00',
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
            '/reviews',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'body' => 'Ut accusantium ad facere qui est. Voluptas quae rerum voluptas perspiciatis molestiae voluptas assumenda. Nobis impedit laudantium eaque saepe quae.',
                'rating' => self::$rating,
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
        self::$rating = 7;

        $this->client->request(
            Request::METHOD_PATCH,
            sprintf('/reviews/%d', self::$entityId),
            [],
            [],
            [],
            json_encode([
                'rating' => self::$rating,
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
            sprintf('/reviews/%d', self::$entityId),
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'rating' => self::$rating,
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

        $this->assertSame('rating', array_search(self::$rating, $responseContent));
    }

    public function testNotFoundUpdateAction()
    {
        $this->client->request(
            Request::METHOD_PATCH,
            '/reviews/0',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$this->token],
            json_encode([
                'rating' => 7,
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
        $this->client->request(Request::METHOD_GET, '/reviews');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('reviews', $this->client->getResponse()->getContent());
    }

    public function testFilterListAction()
    {
        $this->client->request(Request::METHOD_GET, sprintf('/reviews?review_filter[rating]=%s', self::$rating));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $this->assertTrue(
            $this->client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );

        $this->assertContains('reviews', $this->client->getResponse()->getContent());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame('rating', array_search(self::$rating, $responseContent['reviews'][0]));
    }

    public function testShowAction()
    {
        $this->client->request(Request::METHOD_GET, sprintf('/reviews/%d', self::$entityId));

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
        $this->client->request(Request::METHOD_GET, '/reviews/0');

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
        $this->client->request(Request::METHOD_DELETE, sprintf('/reviews/%d', self::$entityId));

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
            sprintf('/reviews/%d', self::$entityId),
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
            '/reviews/0',
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
