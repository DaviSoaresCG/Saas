# ZapCatálogo — Plataforma SaaS Multi-tenant de Catálogos Digitais (v2.0)

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-Testing-F43F5E?style=for-the-badge&logo=php&logoColor=white)

## 📖 Sobre o Projeto

O **ZapCatálogo** é uma plataforma **SaaS (Software as a Service) Multi-tenant** voltada para a criação e gestão de catálogos digitais com fechamento de pedidos integrado ao WhatsApp e integração nativa com sistemas ERP (como **SigaDez**).

Cada lojista possui seu próprio subdomínio isolado (ex.: `loja.zapcatalago.com.br`) e pode compartilhar variantes promocionais do catálogo por link (`zapcatalago.com.br/{hash}`), garantindo isolamento completo de dados entre inquilinos, personalização visual e controle de disponibilidade do catálogo.

---

## 🏗️ Arquitetura e Decisões Técnicas

### 1. Multi-tenancy (Single Database + Global Scopes)
A aplicação adota a estratégia de **Single Database com Tenant Isolation** via `user_id`:
* **Isolamento Automático (`BelongsToTenant` / `TenantScope`):** Models do domínio (`Products`, `Grupo`, `Catalogo`, `Pedido`, `Atributo`) aplicam automaticamente o escopo global do tenant ativo em todas as consultas Eloquent.
* **Resolução de Contexto (`ResolveTenant`):** Middleware que identifica o inquilino tanto via subdomínio (`{slug}.dominio.com`) quanto via rota de variante promocional (`/{hash}` de 12 caracteres), injetando a instância do lojista no container da aplicação e configurando parâmetros de sessão da vitrine (descontos, preço sob consulta e visibilidade do nome do catálogo).
* **Proteção Cross-Tenant (`EnsureUserBelongsToTenant`):** Impede que um lojista autenticado no `tenant A` acesse ou modifique recursos administrativos do `tenant B` (proteção contra IDOR em produtos, grupos, imagens e pedidos).

### 2. Perfis de Cliente: Direto (`direct`) vs Integrado a ERP (`erp`)
* **Cliente Direto (`direct`):** Gerencia manualmente seus produtos pelo painel administrativo, com suporte a upload de múltiplas imagens, ordenação por drag-and-drop, categorização em grupos e assinatura recorrente.
* **Cliente ERP (`erp`):** Sincroniza produtos, estoques, SKUs, pesos e imagens via API REST autenticada por **Laravel Sanctum** (`/api/erp/...` e `/api/v1/SigaDezAPI`). O processamento de imagens enviadas em Base64 ocorre em background via fila (`ProcessImageBase64`) sincronizada com a galeria do produto. Campos controlados pelo ERP (`nome`, `preco_base`, `estoque`) são protegidos contra sobrescrita manual no painel.

### 3. Pagamentos e Assinaturas (Asaas — PIX e Cartão de Crédito)
* **Cobrança Recorrente e Renovação:** Suporte a planos mensal e anual via **Asaas API** (PIX com QR Code/Copia e Cola e Cartão de Crédito).
* **Webhooks Idempotentes:** Processamento automático de confirmações de pagamento (`PAYMENT_RECEIVED` / `PAYMENT_CONFIRMED`), somando dias de vigência de forma cumulativa (`plano_expira_em`) e liberando o subdomínio da loja em tempo real via **Laravel Reverb** (WebSockets).
* **Controle de Vigência (`CheckSubscription`):** Bloqueia o acesso ao painel e à vitrine pública quando a assinatura está expirada, exceto para clientes ERP ou contas ativadas por tempo indeterminado pelo Super Admin.

---

## 🚀 Principais Funcionalidades

### 📦 Gestão de Produtos e Categorias (Grupos)
* **Cadastro e Edição Transacional:** Criação e edição de produtos com conversão monetária segura (padrão brasileiro `1.234,56` ou decimal), geração automática de `slug` e transação atômica (`DB::transaction`) com limpeza automática de arquivos no storage em caso de falha.
* **Galeria de Imagens:** Upload de múltiplas fotos (`jpeg`, `png`, `jpg`, `webp`), reordenação dinâmica com sincronização automática da imagem de capa (`syncCover`) e exclusão individual isolada por tenant.
* **Ativação / Inativação Rápida:** Alternância de status (`Ativo` / `Inativo`) diretamente na listagem administrativa do lojista (`PATCH /products/{product}/toggle-status`). Produtos inativos permanecem visíveis no painel do lojista, mas são ocultados imediatamente da vitrine pública, buscas, URLs diretas de visitantes e adição ao carrinho.
* **Grupos de Produtos:** Criação de categorias com foto de capa e vínculo em lote de produtos.

### 🏷️ Catálogos Promocionais (Variantes por Hash)
* Geração de links exclusivos (`/{hash}`) com configurações independentes:
  * **Percentual de Desconto:** Aplica desconto automático sobre os preços na vitrine e no carrinho.
  * **Preço Sob Consulta (`sob_consulta`):** Oculta os valores monetários na vitrine e nos pedidos.
  * **Exibição de Identificação (`mostrar_nome`):** Permite exibir ou ocultar o nome do catálogo/empresa no topo da vitrine pública.

### 🎨 Personalização da Vitrine e Experiência de Compra
* **Temas e Identidade Visual:** Upload de logotipo da empresa e escolha entre temas pré-definidos (`clean`, `dark`, ` vibrant`, etc.) ou paleta de cores customizada.
* **Layout Mobile Configurável:** Seletor no painel com preview interativo para exibir a grade de produtos em **1 ou 2 colunas** em dispositivos móveis.
* **Carrinho e Pedidos:** Contador dinâmico de itens no topo da loja, observações por item, registro histórico de pedidos no painel e redirecionamento formatado para conclusão no WhatsApp do lojista.
* **Métricas no Dashboard:** Contabilização de cliques/visualizações por produto (`ProductClick`) e resumo de pedidos.

### 👑 Painel Super Admin
* Área restrita (`/superadmin`) para administração global da plataforma:
  * **Ativação por Tempo Indeterminado (`Ilimitado`) e Desativação** imediata de qualquer lojista.
  * Visualização de métricas globais e detalhes por inquilino.
  * Ferramentas de limpeza seletiva (produtos, pedidos, grupos, catálogos, atributos) ou exclusão completa de conta com remoção de mídias do storage.

---

## ⚡ Stack Tecnológica

* **Backend:** PHP 8.3+, Laravel 12, Laravel Sanctum, Laravel Reverb, Laravel Cashier
* **Banco de Dados:** MySQL 8 (Produção/Desenvolvimento) / SQLite in-memory (Testes)
* **Pagamentos:** Asaas API (PIX e Cartão de Crédito)
* **Processamento de Imagens & PDF:** Intervention Image v4, DomPDF
* **Frontend:** Blade, TailwindCSS, Alpine.js, Lucide Icons, SortableJS
* **Testes Automatizados:** Pest PHP 3 (`89 testes` / `374 asserções` cobrindo fluxos multi-tenant, produtos, pagamentos, catálogos, layout mobile e super admin)

---

## 🛠️ Como Executar Localmente

1. **Clone o repositório e instale as dependências:**
   ```bash
   composer install
   npm install
   ```

2. **Configure o ambiente:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure as credenciais do banco de dados (`DB_*`), domínio base (`APP_DOMAIN`, ex.: `lvh.me` ou `saas.test`) e chave do Asaas (`ASAAS_API_KEY`) no arquivo `.env`.

3. **Execute as migrações e crie o link simbólico do storage:**
   ```bash
   php artisan migrate
   php artisan storage:link
   ```

4. **Inicie o ambiente de desenvolvimento (Servidor, Fila e Vite):**
   ```bash
   composer run dev
   ```

5. **Execute a suíte de testes:**
   ```bash
   php artisan test
   ```

---

## 🔗 Links de Demonstração
* **Home:** http://zapcatalago.com.br/
* **Página de um Lojista:** https://davilmtd.zapcatalago.com.br/produtos
* **Login de demonstração (Dashboard):** `email: davisoaresgigante@gmail.com` | `senha: 123123123`

