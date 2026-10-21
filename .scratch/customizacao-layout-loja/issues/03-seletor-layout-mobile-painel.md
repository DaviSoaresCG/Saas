# 03 — Painel de Configuração do Layout Mobile com Preview Interativo (Stitch)

**What to build:** Uma tela de configurações no painel administrativo onde o lojista seleciona a disposição dos produtos em celulares: 1 Coluna (feed vertical detalhado) ou 2 Colunas (grade compacta). A tela contém um mockup interativo de smartphone estilizado que anima dinamicamente a troca de layout quando o lojista clica nas opções, permitindo visualizar exatamente como os clientes verão a loja antes de salvar. A escolha é salva nas configurações do lojista (tabela `users`).

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] O lojista tem acesso à configuração de colunas do layout mobile no painel de administração (padrão: 1 coluna).
- [x] O componente de mockup de celular reage interativamente aos cliques: selecionar 1 coluna exibe a animação/disposição de feed vertical; selecionar 2 colunas exibe a grade lado a lado.
- [x] Ao salvar, a preferência (`colunas_mobile` = 1 ou 2) é validada e persistida nas configurações do usuário/loja.
- [x] A tela recarrega exibindo a opção atualmente ativa pré-selecionada no mockup.
