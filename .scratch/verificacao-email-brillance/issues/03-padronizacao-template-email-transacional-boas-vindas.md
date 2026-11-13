# 03 — Padronização visual do template de e-mail transacional de boas-vindas

**What to build:** O lojista que conclui a verificação ou ativa a loja recebe um e-mail transacional com design moderno e compatível com clientes de e-mail (Gmail, Outlook, Apple Mail), contendo a identidade visual do ZapCatálogo, mensagem personalizada com o nome do lojista e botão direto para o painel de controle da sua loja (`route('dashboard', ['slug' => $user->slug])`).

**Blocked by:** 01 — Estilização da tela de aviso e reenvio de verificação de e-mail, 02 — Estilização da tela de confirmação de e-mail com sucesso

**Status:** ready-for-agent

- [ ] Unificação e modernização dos templates de e-mail de boas-vindas (`resources/views/emails/welcome.blade.php` e `resources/views/email/welcome.blade.php`).
- [ ] Implementação de layout HTML com CSS inline compatível com clientes de e-mail modernos e legados.
- [ ] Cabeçalho com a marca ZapCatálogo e tipografia limpa.
- [ ] Botão de ação (CTA) destacado apontando para o dashboard do lojista via subdomínio (`route('dashboard', ['slug' => $user->slug])`).
- [ ] A suíte completa de testes da aplicação (`php artisan test`) executa 100% verde sem regressões.

