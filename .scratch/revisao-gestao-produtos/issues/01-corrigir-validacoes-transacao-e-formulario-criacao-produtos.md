# 01 — Corrigir validações, integridade transacional e formulário de criação de produtos

**What to build:** Garantir que o lojista consiga cadastrar e editar produtos com validação consistente de preço e descrição, persistência atômica de dados e imagens, preenchimento automático de slug e status ativo por padrão, sem exibir nem sobrescrever atributos nas telas de criação e edição e sem travamentos de validação HTML nativa no upload de imagens.

**Blocked by:** Nenhum — pode começar imediatamente

**Status:** completed

- [x] O valor monetário informado na criação e edição é validado como número positivo dentro do limite suportado pelo banco antes de salvar, exibindo erro de validação amigável caso o formato seja inválido.
- [x] A descrição do produto utiliza campo de texto multi-linha na criação e respeita o limite máximo suportado pelo banco de dados tanto na criação quanto na edição.
- [x] O cadastro de novo produto define automaticamente o `slug` e o `status` ativo, executando a criação do produto, o salvamento das imagens e o vínculo de grupos dentro de uma transação atômica.
- [x] As ações de criação e edição de produtos não consultam, não exibem e não sobrescrevem vínculos de atributos do produto.
- [x] O seletor de imagens na tela de criação permite enviar o formulário sem bloqueio silencioso de controles ocultos, permite selecionar novamente um arquivo que tenha sido removido do preview e libera URLs temporárias de memória ao reordenar.
- [x] Testes automatizados cobrem a criação e atualização de produtos, validação de preço/descrição e preservação de integridade.

