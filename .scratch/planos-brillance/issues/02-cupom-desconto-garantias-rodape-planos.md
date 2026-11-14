# 02 — Seção de cupom de desconto, garantias de pagamento e rodapé unificado na página de planos

**What to build:** Na página `/plans`, o lojista conta com um formulário moderno de cupom de desconto integrado à rota de validação (`counpons.validate`), exibindo mensagens de sucesso ou erro com alertas estilizados. A página também ganha a seção de segurança e transparência (Pix, cartão de crédito, ativação instantânea e 0% de comissão) e o rodapé institucional do Brillance, acompanhado dos testes automatizados de ponta a ponta.

**Blocked by:** 01 — Shell da página de planos e cards de precificação no Design System Brillance

**Status:** completed

- [x] Formulário de cupom de desconto estilizado no padrão Brillance, integrado à rota `route('counpons.validate')`, com suporte a feedback de erro (`session('error')`) e sucesso (`session('success')`).
- [x] Seção informativa de garantias de segurança (ícone de escudo, menção a Pix e cartão de crédito, política transparente sem cobrança de comissões por venda).
- [x] Rodapé (Footer) unificado com a identidade da marca ZapCatálogo, links institucionais e ano dinâmico no copyright.
- [x] Menu mobile funcional com alternância de ícones via JavaScript leve e acessibilidade.
- [x] Testes automatizados em `tests/Feature/PlansPageTest.php` validando aplicação de cupons, exibição de mensagens de feedback e integridade do rodapé.
- [x] Execução da suíte completa de testes (`php artisan test`) com 100% dos testes passando sem regressões.

