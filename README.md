
# API de Gerenciamento de Produtos e Pedidos
API REST desenvolvida em Laravel para gerenciamento de produtos e pedidos.
## 🚀 Como executar
### Pré-requisitos
-   PHP 8.2+
-   Composer
-   MySQL
-   Laravel 12
### Instalação
1.  Clone o repositório
2.  ``composer install``
3.  Copie .env.example para .env
4.  Configure o banco de dados no .env
5.  ``php artisan key:generate``
6.  ``php artisan migrate --seed``
    

### Credenciais para teste

Após executar o seed:

-   Email: ``test@mail.com``
-   Senha: ``password``
    
## 📚 Endpoints

### Autenticação

- `POST /api/register` - Registrar usuário
- `POST /api/login` - Login
- `POST /api/logout` - Logout (autenticado)
- `GET /api/user` - Usuário atual (autenticado)
    

### Produtos (Públicos)

- `GET /api/products` - Listar produtos
- `POST /api/products` - Criar produto
- `GET /api/products/{id}` - Mostrar produto
- `PUT /api/products/{id}` - Atualizar produto
- `DELETE /api/products/{id}` - Excluir produto    

### Pedidos (Autenticados)

- `GET /api/orders` - Listar pedidos do usuário
- `POST /api/orders` - Criar pedido
- `GET /api/orders/{id}` - Mostrar pedido
- `PUT /api/orders/{id}` - Atualizar pedido
- `PATCH /api/orders/{order}/cancel` - Cancelar pedido
- `PATCH /api/orders/{order}/complete` - Finalizar pedido
    
## 🧪 Testes

**Execute os testes com: **
``php artisan test``

## 📊 Regras de Negócio

### Produtos

-   Todas as rotas são públicas
-   Não é possível excluir produtos vinculados a pedidos
-   Campos obrigatórios: nome, preço, estoque, categoria
    

### Pedidos

-   Todas as rotas exigem autenticação
-   Apenas o criador pode visualizar/editar/cancelar
-   Não é possível excluir pedidos
-   Estoque é validado e atualizado automaticamente
-   Pedidos cancelados não podem ser editados

## 🛠️Tecnologias Utilizadas

-   Laravel 12 - Framework PHP
-   Sanctum - Autenticação API
-   MySQL - Banco de dados

## ✅ Funcionalidades Implementadas

### Obrigatórias
-   API REST em Laravel 12+
-   Autenticação com Sanctum
-   CRUD completo de Produtos (rotas públicas)
-   CRUD completo de Pedidos (rotas protegidas)
-   Paginação em todas as listagens
-   Validação de estoque
-   Regras de negócio implementadas
    

### Diferenciais

-   Testes automatizados (PHPUnit)
-   Laravel Resources para padronização
-   Service Layer organizada
-   Validação e decremento de estoque

## Fluxo de Pedidos

1.  Criação: Usuário autenticado cria pedido com produtos
2.  Validação: Estoque é verificado e decrementado
3.  Edição: Apenas o criador pode editar (se não cancelado)
4.  Cancelamento: Devolve estoque e marca como cancelado

----------
