# 02 — Estilização da tela de checkout Pix com QR Code e Copia-e-Cola

**What to build:** Ao escolher o pagamento via Pix, o lojista é direcionado para `/pagamento/checkout`, apresentando um card limpo no padrão Brillance com valor destacado, QR Code em moldura de alto contraste, campo de código Pix Copia-e-Cola com ação de cópia em 1 clique, aviso de expiração, polling de aprovação automática e botão de simulação de pagamento em ambiente local/sandbox, acompanhado de testes automatizados.

**Blocked by:** Nenhum — pronto para implementação (Ticket 01 concluído).

**Status:** ready-for-agent

- [ ] Migração de `resources/views/checkout/pix.blade.php` para o Design System Brillance (fundo `#f7f5f3`, container branco/papel suave, tipografia `Instrument Serif` para valores e títulos).
- [ ] Exibição limpa do valor da cobrança e identificação do plano contratado.
- [ ] Card central destacado para o QR Code Pix com moldura branca nítida para facilitar o escaneamento por celulares.
- [ ] Campo Pix Copia-e-Cola com feedback visual instantâneo ao clicar em "Copiar código" via Alpine.js ou clipboard API nativa.
- [ ] Indicador de tempo de expiração do QR Code e polling assíncrono para verificar aprovação com redirecionamento automático para a rota de sucesso.
- [ ] Botão de simulação de pagamento visível apenas em ambiente local ou sandbox para testes rápidos de desenvolvimento.
- [ ] Testes automatizados cobrindo a renderização do checkout Pix e verificação de status.

