<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Exception;

use App\Exception\Enum\ApiErrorEnumType;
use Exception;

class ApiException extends Exception
{
    public const DEFAULT_MESSAGE = 'General error.';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $data;

    /**
     * {@inheritdoc}
     *
     * @param string $message
     * @param array $data
     */
    public function __construct(string $message, int $code, array $data = [])
    {
        parent::__construct($message, $code);

        $this->type = ApiErrorEnumType::GENERAL_ERROR;
        $this->data = $data;
    }

    /**
     * @param array $data
     *
     * @return ApiException
     */
    public static function createWithData(array $data = []): self
    {
        return new static(static::DEFAULT_MESSAGE, 0, $data);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
