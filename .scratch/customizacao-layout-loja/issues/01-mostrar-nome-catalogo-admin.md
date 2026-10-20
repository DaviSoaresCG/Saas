# 01 — Gerenciar visibilidade do nome do catálogo no Painel Admin

**What to build:** Permitir que o lojista decida, na criação e na edição de um catálogo no painel administrativo, se o nome do catálogo deve ser exibido publicamente na vitrine da loja. O estado dessa preferência deve persistir com segurança no banco de dados e refletir fielmente nos formulários e modais do painel.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] Campo/checkbox "Mostrar nome do Catálogo" disponível e funcional nos modais de criação e de edição de catálogos.
- [x] A preferência padrão para novos catálogos é definida como ativa (`true`).
- [x] Ao salvar a criação ou a edição de um catálogo, a opção selecionada é validada e persistida no banco de dados.
- [x] Ao reabrir o modal de edição de um catálogo, o estado salvo (marcado ou desmarcado) é carregado corretamente nos controles reativos.
