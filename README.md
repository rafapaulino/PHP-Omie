# omiephpsdk

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
- PHP 8.1+ (recomendado)
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

