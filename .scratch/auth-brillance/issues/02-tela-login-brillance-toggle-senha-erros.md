# 02 — Implementar tela de login com toggle de senha e feedback de erros

**What to build:** Redesenhar a tela de login (`resources/views/auth/login.blade.php`) no padrão visual da referência visual Brillance. O lojista visualiza o cabeçalho com o eyebrow "QUE BOM TER VOCÊ DE VOLTA", o título em Instrument Serif "Entre na sua conta." e o subtítulo "Acesse seu painel e continue cuidando da sua loja.". O formulário disponibiliza o campo de e-mail com ícone interno e placeholder amigável, o campo de senha com ícone de cadeado e botão interativo de alternância de visibilidade da senha (mostrar/ocultar senha com ícones de olho aberto e fechado), link para recuperação de senha, botão de ação full-width "Entrar →" e atalho para criação de conta ("Ainda não tem conta? Criar conta"). Erros de validação e alertas de sessão são exibidos com destaque visual e mensagens claras.

**Blocked by:** 01 — Criar layout split-screen responsivo de autenticação no padrão Brillance

**Status:** ready-for-agent

- [ ] A tela de login renderiza a hierarquia tipográfica exata da referência: eyebrow verde musgo, headline em Instrument Serif e subtítulo explicativo.
- [ ] O campo de e-mail possui ícone Lucide de envelope, autopreenchimento de valor anterior (`old('email')`) e validação visual de erro.
- [ ] O campo de senha possui ícone de cadeado e botão funcional com Alpine.js para alternar entre tipo `password` e `text`, atualizando o ícone de olho.
- [ ] O formulário contém token CSRF e submete via POST para `route('login')`.
- [ ] Link "Esqueceu a senha?" direciona para `route('password.request')` quando configurado.
- [ ] Botão principal "Entrar →" estilizado em carvão `#37322f` com cantos arredondados (`rounded-full`) e efeito hover suave.
- [ ] Link inferior "Ainda não tem conta? Criar conta" direciona para `route('register')`.
- [ ] Mensagens de status da sessão (ex: redefinição de senha realizada) e erros de credenciais são exibidos de forma amigável e acessível.

