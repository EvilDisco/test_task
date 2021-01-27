<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class JwtDecorator implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'johndoe@example.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'apassword',
                ],
            ],
        ]);

        $requestBody = new Model\RequestBody(
            'Generate new JWT Token', // description
            new \ArrayObject([
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/Credentials',
                    ],
                ],
            ]) // content
        );

        $operation = new Model\Operation(
            'postCredentialsItem', // operationId
            [], // tags
            [
                '200' => [
                    'description' => 'Get JWT token',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Token',
                            ],
                        ],
                    ],
                ]
            ], // responses
            'Get JWT token to login.', // summary
            '', // description
            null, // externalDocs
            [], // parameters
            $requestBody
        );

        $pathItem = new Model\PathItem(
            'JWT Token', // ref
            null, // summary
            null, // description
            null, // get
            null, // put
            $operation // post
        );

        $openApi->getPaths()->addPath('/auth_token', $pathItem);

        return $openApi;
    }
}