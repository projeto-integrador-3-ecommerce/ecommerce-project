<?php

namespace App;

use OpenApi\Attributes as OA;

// openapi do projeto ecommerce
#[OA\Info(
    version: '1.1.0',
    title: 'E-commerce 3D Laravel',
    description: 'Autenticação, catálogo, pedidos e checkout do E-commerce 3D.'
)]
#[OA\SecurityScheme(
    securityScheme: 'sessionCookie',
    type: 'apiKey',
    in: 'cookie',
    name: 'laravel_session'
)]
#[OA\Get(
    path: '/',
    summary: 'Health check',
    tags: ['System'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Application is available.'
        ),
    ]
)]
#[OA\Post(
    path: '/auth/login',
    summary: 'Authenticate with email and password',
    tags: ['Authentication'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                type: 'object',
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255),
                    new OA\Property(property: 'password', type: 'string', maxLength: 255),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 302, description: 'Redirect after authentication or invalid credentials.'),
        new OA\Response(response: 422, description: 'Input validation failed.'),
    ]
)]
#[OA\Post(
    path: '/auth/register',
    summary: 'Register a customer account',
    tags: ['Authentication'],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                type: 'object',
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 150),
                    new OA\Property(property: 'email', type: 'string', format: 'email', maxLength: 255),
                    new OA\Property(property: 'password', type: 'string', minLength: 8, maxLength: 255),
                    new OA\Property(property: 'password_confirmation', type: 'string', minLength: 8, maxLength: 255),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 302, description: 'Redirect after registration.'),
        new OA\Response(response: 422, description: 'Input validation failed.'),
    ]
)]
#[OA\Get(
    path: '/products',
    summary: 'List products',
    tags: ['Catalog'],
    responses: [new OA\Response(response: 200, description: 'Product catalog rendered.')]
)]
#[OA\Get(
    path: '/orders',
    summary: 'List orders visible to the authenticated user',
    tags: ['Orders'],
    security: [['sessionCookie' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Customer orders, or all orders for an administrator.'),
        new OA\Response(response: 302, description: 'Authentication required.'),
    ]
)]
#[OA\Get(
    path: '/orders/{order}',
    summary: 'View an order owned by the user or accessible to an administrator',
    tags: ['Orders'],
    parameters: [new OA\Parameter(name: 'order', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    security: [['sessionCookie' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Order details rendered.'),
        new OA\Response(response: 404, description: 'Order does not exist or is not visible to this user.'),
    ]
)]
#[OA\Patch(
    path: '/orders/{order}/status',
    summary: 'Update an order delivery status (administrator only)',
    tags: ['Orders'],
    parameters: [new OA\Parameter(name: 'order', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    security: [['sessionCookie' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'application/x-www-form-urlencoded',
            schema: new OA\Schema(
                type: 'object',
                required: ['status'],
                properties: [new OA\Property(
                    property: 'status',
                    type: 'string',
                    enum: ['to_pay', 'payment_confirmed', 'shipped', 'out_for_delivery', 'delivered', 'canceled']
                )]
            )
        )
    ),
    responses: [
        new OA\Response(response: 302, description: 'Redirect to the updated order.'),
        new OA\Response(response: 403, description: 'Administrator permission required.'),
        new OA\Response(response: 422, description: 'Invalid order status.'),
    ]
)]
#[OA\Post(
    path: '/payment/{order}',
    summary: 'Create or reuse a Mercado Pago Checkout Pro preference',
    tags: ['Payments'],
    parameters: [new OA\Parameter(name: 'order', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
    security: [['sessionCookie' => []]],
    responses: [
        new OA\Response(response: 302, description: 'Redirect to Mercado Pago hosted checkout.'),
        new OA\Response(response: 404, description: 'Order is not payable by this user.'),
    ]
)]
#[OA\Post(
    path: '/webhooks/mercado-pago',
    summary: 'Receive a signed Mercado Pago payment notification',
    tags: ['Payments'],
    parameters: [
        new OA\Parameter(name: 'x-request-id', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'x-signature', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: 'object',
            required: ['type', 'data'],
            properties: [
                new OA\Property(property: 'type', type: 'string', example: 'payment'),
                new OA\Property(
                    property: 'data',
                    type: 'object',
                    properties: [new OA\Property(property: 'id', type: 'string', example: '987654')]
                ),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Notification received and reconciled.'),
        new OA\Response(response: 401, description: 'Invalid notification signature.'),
        new OA\Response(response: 503, description: 'Payment provider temporarily unavailable.'),
    ]
)]

class OpenApi {}
