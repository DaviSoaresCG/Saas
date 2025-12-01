# SaaS Multi-tenant Platform (Laravel & Stripe)

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-Payment-008CDD?style=for-the-badge&logo=stripe&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 Sobre o Projeto

Este projeto é uma plataforma **SaaS (Software as a Service) Multi-tenant** desenvolvida para simular um ambiente real de gestão empresarial B2B. A aplicação permite que cada cliente tenha seu próprio subdomínio isolado (ex: `clienteA.plataforma.com`), garantindo a segurança e separação dos dados.

O foco principal deste projeto foi a implementação de uma arquitetura robusta de Backend, lidando com desafios de **Tenant Isolation**, **Pagamentos Recorrentes** e **Roteamento Dinâmico**.

## 🏗️ Arquitetura e Decisões Técnicas

### 1. Multi-tenancy (Single Database)
Optei pela estratégia de **Single Database com Tenant Isolation** via `tenant_id`.
* **Isolamento:** Utilizei `Global Scopes` do Eloquent para garantir que queries como `Product::all()` retornem apenas os dados do inquilino atual automaticamente.
* **Identificação:** Um Middleware personalizado intercepta a requisição, extrai o subdomínio (`cliente.app.com`), valida no banco e injeta o contexto do Tenant na aplicação.

### 2. Fluxo de Pagamento (Stripe)
Integração completa com **Laravel Cashier**.
* Assinaturas mensais/anuais.
* Processamento de **Webhooks** do Stripe para renovação automática e tratamento de falhas de cartão.

### 3. Autenticação e Segurança
* **Laravel Breeze** personalizado para redirecionamento inteligente.
* **Middleware de Proteção:** Impede que um usuário logado no `tenant A` acesse o painel do `tenant B` manipulando a URL (proteção contra IDOR e Session Fixation).

## ⚡ Stack Tecnológica

* **Backend:** PHP 8.3, Laravel 12
* **Banco de Dados:** MySQL 8
* **Pagamentos:** Stripe API (Laravel Cashier)
* **Frontend:** Blade, TailwindCSS, Alpine.js
