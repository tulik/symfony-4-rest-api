<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Exception;

class FormInvalidException extends ApiException
{
    public const DEFAULT_MESSAGE = 'Submitted form did not pass validation.';

    /**
     * @param string $message
     * @param int $code
     * @param array $data
     */
    public function __construct(string $message, int $code, array $data = [])
    {
        parent::__construct($message, $code, $data);
    }
}
