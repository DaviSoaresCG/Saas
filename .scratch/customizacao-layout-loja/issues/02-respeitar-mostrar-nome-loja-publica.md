# 02 — Respeitar visibilidade do nome do catálogo na Vitrine Pública

**What to build:** Quando um cliente ou visitante acessa a loja de um catálogo específico, o cabeçalho/título do catálogo só deve ser renderizado se o lojista configurou a opção "Mostrar nome do Catálogo" como ativa. Caso esteja desativada, a página inicial da loja exibe os produtos diretamente sem o cabeçalho com o nome do catálogo.

**Blocked by:** 01 — Gerenciar visibilidade do nome do catálogo no Painel Admin

**Status:** completed

- [x] Na sessão do visitante/tenant, a flag de visibilidade do catálogo ativo é carregada e disponibilizada para a camada de visualização.
- [x] O componente de layout da loja esconde o nome e cabeçalho do catálogo quando a configuração estiver desmarcada.
- [x] O componente de layout da loja exibe o nome e cabeçalho normalmente quando a configuração estiver marcada.
- [x] Testes automatizados cobrem a renderização da loja pública com e sem o cabeçalho do catálogo.
