<?php

namespace App\Constants;

use Symfony\Component\HttpFoundation\Response;

final class HttpStatus
{
    public const OK = Response::HTTP_OK;
    public const CREATED = Response::HTTP_CREATED;
    public const UNPROCESSABLE_ENTITY = Response::HTTP_UNPROCESSABLE_ENTITY;
    public const UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;
    public const INTERNAL_SERVER_ERROR = Response::HTTP_INTERNAL_SERVER_ERROR;
} 