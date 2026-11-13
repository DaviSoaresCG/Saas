# 01 — Estilização da tela de aviso e reenvio de verificação de e-mail

**What to build:** O lojista recém-registrado é recebido em uma tela amigável e moderna no Design System Brillance (`auth/verify-email.blade.php`), informando com clareza o envio do link de confirmação para sua caixa postal, orientando a checar a caixa de spam, exibindo alerta com feedback visual em caso de reenvio (`session('status') == 'verification-link-sent'`), botão primário para reenviar o e-mail e opção discreta de logout para trocar de conta se necessário.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] `resources/views/auth/verify-email.blade.php` migrado de `<x-guest-layout>` para `<x-auth-split-layout>`.
- [x] Tipografia alinhada com as fontes do Design System Brillance (`Inter` e `Instrument Serif`).
- [x] Mensagem de orientação clara sobre caixa de entrada e spam com erro de digitação ("caixa de span") corrigido.
- [x] Alerta de feedback visual em tom verde suave com ícone Lucide quando um novo link for disparado.
- [x] Formulário de reenvio de e-mail com botão primário estilizado com micro-interações (`hover`, `active`).
- [x] Formulário de logout acessível e com estilo secundário integrado à interface.
- [x] Testes automatizados de renderização da tela de verificação (`tests/Feature/Auth/EmailVerificationTest.php`) executando com status HTTP 200.

