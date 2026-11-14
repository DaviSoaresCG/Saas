# 01 — Fundação visual e alinhamento do tema padrão da loja ao Brillance

**What to build:** O lojista e seus clientes que acessam a vitrine pública (`/loja/{slug}` ou subdomínio próprio) visualizam uma base visual alinhada ao padrão Brillance. O layout carrega a tipografia editorial (`Instrument Serif` para títulos/preços e `Inter` para navegação e conteúdo) e as variáveis CSS do tema padrão (`clean`) no arquivo `resources/css/app.css` passam a adotar os tons oficiais do Brillance (fundo papel `#f7f5f3`, cards brancos `#ffffff`, cor primária carvão `#37322f`, texto `#37322f` e acentos suaves), permitindo que lojas sem tema customizado reflitam a elegância do novo design system sem desconfigurar lojas com outros temas (`ocean`, `sunset`, `midnight`, `forest`, etc.).

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Carregamento das famílias tipográficas `Instrument Serif` (Google Fonts) e `Inter` no `<x-store-layout>` via `@push('styles')`.
- [x] Configuração do helper de classes ou extensão Tailwind para suporte à classe `font-serif` no contexto do layout da loja.
- [x] Atualização das variáveis do tema padrão (`clean` / `:root`) em `resources/css/app.css` para a paleta Brillance (`--bg-page: #f7f5f3;`, `--bg-card: #ffffff;`, `--color-primary: #37322f;`, `--text-base: #37322f;`, `--text-muted: #605a57;`, `--text-on-primary: #ffffff;`).
- [x] Preservação integral das variáveis CSS dos temas alternativos (`ocean`, `sunset`, `midnight`, `forest`, `premium`, `lavender`, `terracotta`, `cyber`).
- [x] Verificação de compilação sem erros e sem impacto na renderização dos componentes filhos.

