# 01 — Configurar design system Brillance com fontes, paleta e shell base da Home

**What to build:** Estabelecer a fundação visual do template Brillance na página inicial (`home.blade.php`) do ZapCatálogo. Isso inclui carregar as famílias tipográficas (Instrument Serif para títulos elegantes e Inter para leitura), declarar os tokens de cores do design system (tons de papel suave `#f7f5f3`, carvão profundo `#37322f`, verde limão de destaque `#d7f56b`, verde oliva `#849451`/`#71834f` e bordas sutis), integrar o Lucide Icons e estruturar o container semântico principal da landing page, garantindo que o carregamento da Home permaneça veloz e compatível com o build do Vite e Tailwind.

**Blocked by:** Nenhum — pode começar imediatamente.

**Status:** completed

- [x] As fontes Instrument Serif (Google Fonts) e Inter são carregadas no cabeçalho da Home com renderização suave e sem flash de fonte desformatada.
- [x] A paleta de cores e tokens do estilo Brillance (fundo off-white `#f7f5f3`, texto `#37322f`, acento `#d7f56b` e verdes de apoio) estão disponíveis e prontos para uso nas classes utilitárias ou estilos da página.
- [x] Os ícones do Lucide estão configurados e inicializados corretamente na página inicial.
- [x] O arquivo `resources/views/home.blade.php` renderiza um shell semântico base com as tags essenciais de SEO, viewport e favicon sem quebrar a rota pública `GET /`.
- [x] A página inicial responde com código de status HTTP 200 e layout fluido em navegadores desktop e dispositivos móveis.

