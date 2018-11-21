<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Controller;

use Faker\Factory;
use Faker\Generator;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

abstract class AbstractWebTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $token;

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * AbstractWebTestCase constructor.
     *
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->client = static::createClient();
        $this->token = self::getToken();
        $this->faker = Factory::create();
    }

    /**
     * @return string|null
     */
    private static function getToken(): ?string
    {
        self::bootKernel();

        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        $data = ['username' => 'developer@symfony.local', 'roles' => ['ROLE_ADMIN']];

        try {
            $token = $container
                ->get('lexik_jwt_authentication.encoder')
                ->encode($data);
        } catch (JWTEncodeFailureException $e) {
            echo $e->getMessage().PHP_EOL;
        }

        return $token;
    }
}
