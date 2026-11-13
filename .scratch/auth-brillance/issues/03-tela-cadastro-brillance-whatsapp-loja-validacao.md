# 03 — Implementar tela de cadastro com campos de loja, WhatsApp e validações

**What to build:** Redesenhar a tela de cadastro (`resources/views/auth/register.blade.php`) no mesmo estilo visual split-screen da autenticação Brillance. Apresenta o eyebrow "SUA LOJA COMEÇA AQUI", o título em Instrument Serif "Crie sua conta." e o subtítulo "Prepare sua vitrine digital e comece a receber pedidos pelo WhatsApp.". O formulário coleta todos os dados exigidos pelo fluxo de onboarding do ZapCatálogo: Nome completo, E-mail, WhatsApp (com máscara automática `(xx) 9xxxx-xxxx`), Nome da Loja (explicando que definirá o subdomínio profissional), Senha (com botão de alternância de visibilidade e dica de mínimo de 8 caracteres) e Confirmação de Senha. Conta com o botão de conversão "Criar minha conta →", atalho para login e integração total com as mensagens de validação do `RegisteredUserController`.

**Blocked by:** 01 — Criar layout split-screen responsivo de autenticação no padrão Brillance

**Status:** completed

- [x] A tela de cadastro exibe a hierarquia visual consistente com o Brillance: eyebrow verde musgo, título em Instrument Serif e subtítulo focado no valor do produto.
- [x] O campo de Nome possui ícone de usuário e preserva o valor submetido (`old('name')`).
- [x] O campo de E-mail possui ícone de envelope e preserva o valor submetido (`old('email')`).
- [x] O campo de WhatsApp implementa máscara interativa `(99) 99999-9999` com Alpine.js e validação de formato.
- [x] O campo de Nome da Loja possui ícone de loja/sacola e placeholder demonstrando o nome comercial.
- [x] Os campos de Senha e Confirmação de Senha possuem botão de alternância de visibilidade e aviso de requisitos mínimos.
- [x] O formulário contém token CSRF e submete via POST para `route('register')`.
- [x] Botão de submissão "Criar minha conta →" com estilo arredondado em carvão e seta.
- [x] Link de transição "Já tem uma conta? Entrar" direciona para `route('login')`.
- [x] Todas as mensagens de validação de campo do Laravel são renderizadas com estilo harmonizado e sem quebrar o layout.
