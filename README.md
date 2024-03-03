<p align="center">
<a href="hhttps://www.adoorei.com.br/" target="_blank">
<img src="https://adoorei.s3.us-east-2.amazonaws.com/images/loje_teste_logoadoorei_1662476663.png" width="160"></a>
</p>

## Challange Adoorei

**PHP:** v8.1.2

**Laravel:** v10.10

## Installation

Instale a aplicação com os comandos abaixo:

```bash
  composer install
  make up
```

## Running Tests

Para executar testes, execute o seguinte comando:

```bash
  make test
```

## Commands

Comandos úteis:

```bash
  make up         -> instala o projeto
  make down       -> derruba os containers
  make bash       -> acessa o terminal da aplicação
  make fresh-db   -> recria o banco de dados e popula com dados iniciais
  make test       -> executa os testes e limpa a configuração
  make reset-env  -> limpa e cacheia a configuração
```

## API Reference

### Documentation

Link do postman: https://bityl.co/OWE3

#### Headers

```:
  Accept: application/json
  Content-Type: application/json
```

#### Listar produtos disponíveis

```http
  GET /api/products
```

#### Cadastrar nova venda

```http
  POST /api/sales
```

| Parameter             | Type      | Description  |
| :-------------------- | :-------- | :----------- |
| `products`            | `array`   | **Required** |
| `products.product_id` | `integer` | **Required** |
| `products.amount`     | `integer` | **Required** |

#### Consultar vendas realizadas

```http
  GET /api/sales
```

| Parameter       | Type      | Description                          |
| :-------------- | :-------- | :----------------------------------- |
| `per_page`      | `string`  | **Optional** (default 10)            |
| `order_by`      | `integer` | **Optional** (default sales.sale_id) |
| `order_by_type` | `array`   | **Optional** (default asc)           |
| `page`          | `integer` | **Optional** (default 1)             |

#### Consultar uma venda específica

```http
  GET /api/sales/${sale_id}
```

#### Cancelar uma venda

```http
  DELETE /api/sales/${sale_id}/cancel
```

#### Cadastrar novos produtos a uma venda

```http
  POST /api/sales/${sale_id}/product
```

| Parameter    | Type      | Description  |
| :----------- | :-------- | :----------- |
| `product_id` | `integer` | **Required** |
| `amount`     | `integer` | **Required** |

#### Remover produto de uma venda

```http
  POST /api/sales/remove-product/${sale_id}/${product_id}
```
