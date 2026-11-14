# 01 — Estilização da tela de identificação, planos e seleção de pagamento

**What to build:** O lojista autenticado acessa `/pagamento/pendente` (ou `/assinatura/renovar`) e encontra uma interface moderna no padrão Brillance com cabeçalho unificado, stepper de 2 etapas (*Identificação* e *Plano & Pagamento*), campo de CPF/CNPJ com validação em tempo real e atalho para CPF de teste em sandbox, cards dos planos Mensal e Anual com botões de ação para Pix e Cartão de Crédito integrados à rota de geração de cobrança no Asaas, com testes de integração atualizados.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Migração do layout base de `resources/views/checkout/pending.blade.php` para o Design System Brillance (fundo `#f7f5f3`, tipografia `Inter` e `Instrument Serif`, remoção de classes `bg-slate-900` e estilos legados).
- [x] Cabeçalho superior simplificado com BrandMark ZapCatálogo e ação de logout discreta.
- [x] Stepper visual moderno dividindo o processo em Passo 1 (Identificação com CPF/CNPJ) e Passo 2 (Seleção do Plano e Forma de Pagamento).
- [x] Campo de CPF/CNPJ estilizado no padrão Brillance com máscara, validação dinâmica de dígitos e atalho para gerar CPF de teste em ambiente sandbox/local.
- [x] Grade responsiva com os cards dos planos Mensal e Anual, suportando pré-seleção via parâmetro de URL (`?plan=...`) e botões primários dedicados para pagamento via Pix e Cartão de Crédito.
- [x] Testes de integração em `tests/Feature/PixPaymentWebhookTest.php` validando a renderização do stepper, planos e submissão de dados sem regressões.
