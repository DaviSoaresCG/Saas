# 01 — Criar layout split-screen responsivo de autenticação no padrão Brillance

**What to build:** Criar o componente de layout split-screen base para as telas de autenticação (`login`, `register`, `forgot-password`) no padrão visual Brillance. O layout divide a tela em duas metades no desktop: a coluna esquerda dedicada ao formulário, contendo a marca ZapCatálogo no topo, o link de navegação para retornar ao site (`← Voltar para o site`), o container central flexível para os formulários e o copyright no rodapé; e a coluna direita com a imagem editorial fotográfica de alta resolução em tela cheia, degradê inferior de sobreposição para legibilidade, badge flutuante ("● Simples para você. Fácil para seus clientes.") e título persuasivo em Instrument Serif ("Uma vitrine que transforma visitas em conversas."). Em dispositivos móveis e telas menores, a coluna da imagem fica oculta, priorizando o fluxo direto do formulário.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] O componente de layout split-screen é implementado suportando injeção de título, cabeçalhos e slots de formulário.
- [x] As fontes Instrument Serif e Inter, além dos ícones Lucide e suporte a Alpine.js, estão disponíveis no layout.
- [x] A coluna esquerda exibe a marca ZapCatálogo com ícone de sacola e o link "← Voltar para o site" apontando para a página inicial (`route('home')`).
- [x] A coluna direita exibe a imagem fotográfica editorial com sobreposição escura, badge em pill e headline em Instrument Serif.
- [x] A coluna da imagem é responsiva (oculta em mobile/tablets e exibida a partir de telas desktop `lg`).
- [x] O rodapé da coluna de formulário exibe a nota de segurança e o ano atual dinâmico nos direitos reservados.

