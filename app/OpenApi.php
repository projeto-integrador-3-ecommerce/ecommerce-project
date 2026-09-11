<?php
namespace App;

use OpenApi\Attributes as OA;

// openapi do projeto ecommerce
#[OA\Info(
    // versão, título e descrição da nossa api em laravel
    version: '1.0.0',
    title: 'E-commerce 3D Laravel',
    description: 'API for an e-commerce platform for 3D printed products.'
)]

#[OA\Get(
    path: '/',
    summary: 'Teste',
    description: 'Rota de teste da API',
    tags: ['Teste'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Ok. Funcionando.'
        )
    ]
)]

class OpenApi
{

}
?>