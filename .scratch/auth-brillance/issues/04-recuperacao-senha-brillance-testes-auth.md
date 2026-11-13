# 04 — Alinhar recuperação de senha e blindar autenticação com testes automatizados

**What to build:** Harmonizar as telas secundárias de fluxo de acesso (`resources/views/auth/forgot-password.blade.php` e `resources/views/auth/reset-password.blade.php`) para compartilharem a mesma identidade visual Brillance, e implementar uma suíte completa de testes automatizados de feature em Pest (`tests/Feature/AuthBrillanceTest.php`). A suíte verifica que visitantes acessam a tela de login e cadastro com status HTTP 200, que todos os elementos visuais da referência (títulos em serifa, link de voltar, inputs estilizados, imagem editorial) estão presentes, que o fluxo de autenticação e falha de credenciais responde corretamente, e que o cadastro de novos lojistas cria o usuário com o slug correto sem regressões.

**Blocked by:** 02 — Implementar tela de login com toggle de senha e feedback de erros, 03 — Implementar tela de cadastro com campos de loja, WhatsApp e validações

**Status:** completed

- [x] A tela de recuperação de senha (`forgot-password.blade.php`) adota o layout Brillance com título explicativo e botão de envio de link de recuperação.
- [x] A tela de redefinição de senha (`reset-password.blade.php`) adota o layout Brillance com os campos necessários e validações.
- [x] Teste automatizado valida renderização 200 OK das rotas `/login` e `/register` com a estrutura split-screen e elementos da marca.
- [x] Teste automatizado valida submissão de login com credenciais válidas e mensagem amigável para credenciais inválidas.
- [x] Teste automatizado valida fluxo de registro de novo lojista criando usuário, telefone e slug exclusivo da loja.
- [x] A suíte completa de testes da aplicação (`php artisan test`) executa sem falhas nem regressões.
