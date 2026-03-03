# Biblioteca de Integração com a Omie

A url para realizar os testes na api da Omie é essa:
[https://app.omie.com.br/developer/](https://app.omie.com.br/developer/)

Biblioteca PHP para facilitar integração com a API da Omie.

Este projeto ainda está em construção, mas já tem uma base pronta para:
- carregar configurações por `.env`
- centralizar configurações com singleton
- definir contratos de clientes seguindo SOLID
- executar testes com Pest

## Para quem é este projeto?

Para desenvolvedores iniciantes e intermediários que querem organizar uma integração com a Omie de forma simples e escalável.

## Requisitos

Antes de começar, você precisa ter instalado:
- PHP 8.4+ (recomendado)
- Composer

## Instalação

1. Entre na pasta do projeto.
2. Instale as dependências:

```bash
composer install
```

## Configuração do ambiente (.env)

Este projeto lê variáveis de ambiente para montar a configuração.

1. Crie o arquivo `.env` na raiz do projeto (ou copie de `.env.example`).
2. Preencha os valores:

```env
OMIE_API=https://app.omie.com.br/api/v1/
APP_KEY=sua_chave_omie
APP_SECRET=seu_segredo_omie
```

### O que cada variável significa?
- `OMIE_API`: URL base da API da Omie.
- `APP_KEY`: sua chave da aplicação na Omie.
- `APP_SECRET`: seu segredo da aplicação na Omie.

## Como obter as configurações no código

A classe `ConfigSingleton` carrega o `.env` e retorna o array de configuração da biblioteca.

Arquivo: `src/Config/ConfigSingleton.php`

Exemplo:

```php
<?php

use Rafael\Omiephpsdk\Config\ConfigSingleton;

$config = ConfigSingleton::getInstance()->getConfig();

print_r($config);
```

Saída esperada (exemplo):

```php
Array
(
    [omie_api] => https://app.omie.com.br/api/v1/
    [omie_key] => sua_chave_omie
    [omie_secret] => seu_segredo_omie
)
```

## Estrutura de contratos (SOLID)

As interfaces de clientes foram separadas por responsabilidade (Interface Segregation Principle).

Pasta: `src/Clients/Contracts`

Interfaces disponíveis:
- `CreatesClientsInterface` (`createClient`)
- `RetrievesClientsInterface` (`getClient`)
- `UpdatesClientsInterface` (`updateClient`)
- `DeletesClientsInterface` (`deleteClient`)
- `ListsClientsInterface` (`listClients`)
- `ClientServiceInterface` (agrega todas as interfaces acima)

Isso permite implementar apenas o que sua classe realmente precisa.

Contratos de users:
- `ListsUsersInterface` (`listUsers`)
- `UserServiceInterface` (agrega as interfaces de users)

Contratos de vendedores:
- `CreatesSellersInterface` (`createSeller`)
- `SellerServiceInterface` (agrega as interfaces de vendedores)

Contratos de produtos:
- `ListsProductsInterface` (`listProducts`)
- `ProductServiceInterface` (agrega as interfaces de produtos)

Contratos de contas correntes:
- `ListsCurrentAccountsInterface` (`listCurrentAccounts`)
- `CurrentAccountServiceInterface` (agrega as interfaces de contas correntes)

Contratos de CFOP:
- `ListsCfopInterface` (`listCfop`)
- `CfopServiceInterface` (agrega as interfaces de CFOP)

Contratos de categorias:
- `ListsCategoriesInterface` (`listCategories`)
- `CategoryServiceInterface` (agrega as interfaces de categorias)

## Service de Clientes (`OmieClientService`)

A implementação atual do serviço de clientes está em `src/Clients/OmieClientService.php`.

Métodos já implementados:
- `createClient(array $payload): array`
- `listClients(array $filters = []): array`
- `getClient(int|string $clientId): array`
- `updateClient(int|string $clientId, array $payload): array`
- `deleteClient(int|string $clientId): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\Clients\OmieClientService;

$service = new OmieClientService();

// Cria cliente (call: IncluirCliente)
$created = $service->createClient([
    'codigo_cliente_integracao' => 'CodigoInterno0001',
    'email' => 'primeiro@ccliente.com.br',
    'razao_social' => 'Primeiro Cliente  Ltda Me',
    'nome_fantasia' => 'Primeiro Cliente',
    'cnpj_cpf' => '59872959048',
]);

// Lista clientes (call: ListarClientes)
$clients = $service->listClients([
    'pagina' => 1,
    'registros_por_pagina' => 20,
]);

// Consulta um cliente por codigo_cliente_omie (call: ConsultarCliente)
$client = $service->getClient(11118162834);

// Atualiza cliente (call: AlterarCliente)
$updated = $service->updateClient('CodigoInterno0001', [
    'codigo_cliente_integracao' => 'CodigoInterno0001',
    'email' => 'primeiro@ccliente.com.br',
    'razao_social' => 'Primeiro Cliente  Ninja Ltda Me',
    'nome_fantasia' => 'Primeiro Cliente Ninja',
]);

// Exclui cliente (call: ExcluirCliente)
$deleted = $service->deleteClient(11118162834);
```

No `updateClient`, se o payload não incluir `codigo_cliente_omie` ou `codigo_cliente_integracao`,
o método usa o valor de `$clientId` como `codigo_cliente_integracao`.

### Filtros do `listClients`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 50`
- `apenas_importado_api = 'N'`

Você pode sobrescrever esses valores passando o array `$filters`.

## Service de Users (`OmieUserService`)

A implementação atual do serviço de users está em `src/Users/OmieUserService.php`.

Métodos já implementados:
- `listUsers(array $filters = []): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\Users\OmieUserService;

$service = new OmieUserService();

// Lista users (call: ListarUsuarios)
$users = $service->listUsers([
    'pagina' => 1,
    'registros_por_pagina' => 20,
]);
```

### Filtros do `listUsers`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 20`

Você pode sobrescrever esses valores passando o array `$filters`.

## Service de Vendedores (`OmieSellerService`)

A implementação atual do serviço de vendedores está em `src/Sellers/OmieSellerService.php`.

Métodos já implementados:
- `createSeller(array $payload): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\Sellers\OmieSellerService;

$service = new OmieSellerService();

// Cria vendedor (call: IncluirVendedor)
$seller = $service->createSeller([
    'codInt' => '123',
    'nome' => 'Joao Teste',
    'inativo' => 'N',
    'email' => 'teste@minhaempresa.com.br',
    'fatura_pedido' => 'S',
    'visualiza_pedido' => 'N',
    'comissao' => 10,
]);
```

## Service de Produtos (`OmieProductService`)

A implementação atual do serviço de produtos está em `src/Products/OmieProductService.php`.

Métodos já implementados:
- `listProducts(array $filters = []): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\Products\OmieProductService;

$service = new OmieProductService();

// Lista produtos (call: ListarProdutos)
$products = $service->listProducts([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);
```

### Filtros do `listProducts`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 50`
- `apenas_importado_api = 'N'`
- `filtrar_apenas_omiepdv = 'N'`

Você pode sobrescrever esses valores passando o array `$filters`.

## Service de Contas Correntes (`OmieCurrentAccountService`)

A implementação atual do serviço de contas correntes está em `src/CurrentAccount/OmieCurrentAccountService.php`.

Métodos já implementados:
- `listCurrentAccounts(array $filters = []): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\CurrentAccount\OmieCurrentAccountService;

$service = new OmieCurrentAccountService();

// Lista contas correntes (call: ListarContasCorrentes)
$accounts = $service->listCurrentAccounts([
    'pagina' => 1,
    'registros_por_pagina' => 100,
]);
```

### Filtros do `listCurrentAccounts`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 100`
- `apenas_importado_api = 'N'`

Você pode sobrescrever esses valores passando o array `$filters`.

## Service de CFOP (`OmieCfopService`)

A implementação atual do serviço de CFOP está em `src/CFOP/OmieCfopService.php`.

Métodos já implementados:
- `listCfop(array $filters = []): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\CFOP\OmieCfopService;

$service = new OmieCfopService();

// Lista CFOP (call: ListarCFOP)
$cfops = $service->listCfop([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);
```

### Filtros do `listCfop`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 50`

Você pode sobrescrever esses valores passando o array `$filters`.

## Service de Categorias (`OmieCategoryService`)

A implementação atual do serviço de categorias está em `src/Categories/OmieCategoryService.php`.

Métodos já implementados:
- `listCategories(array $filters = []): array`

### Exemplo de uso

```php
<?php

use Rafael\Omiephpsdk\Categories\OmieCategoryService;

$service = new OmieCategoryService();

// Lista categorias (call: ListarCategorias)
$categories = $service->listCategories([
    'pagina' => 1,
    'registros_por_pagina' => 50,
]);
```

### Filtros do `listCategories`

Por padrão, o método envia:
- `pagina = 1`
- `registros_por_pagina = 50`

Você pode sobrescrever esses valores passando o array `$filters`.

## Testes com Pest

Os testes ficam na pasta `tests`.

Para rodar:

```bash
./vendor/bin/pest
```

No Windows (PowerShell), se `php` estiver configurado no PATH:

```powershell
vendor\bin\pest.bat
```

## Estado atual do projeto

Este SDK está em fase inicial. A parte de contratos e configuração já está pronta.

Próximos passos comuns:
- criar implementação concreta para chamadas HTTP na API Omie
- tratar erros e respostas da API
- adicionar mais testes unitários e de integração

## Licença

MIT
