# 🎨 FASE 3: FRONTEND (BLADE + TAILWIND + JQUERY)

Atue como um Engenheiro Frontend Sênior. Implemente as Views em Blade para o CRUD de Células criado na Fase 2, seguindo nosso Design System "Elite V8".

**Diretrizes de Design (Tailwind CSS):**
- **Tema:** Dark Professional com Gold Accents.
- **Cores base:** Fundo da página `bg-gray-900`, Cards `bg-gray-800 border border-gray-700`.
- **Acentos (Gold):** Gradientes nos botões primários `bg-gradient-to-br from-[#d4af37] to-[#b8962d] text-white`.
- **Glassmorphism:** Use `bg-gray-900/85 backdrop-blur-md` para navbars e modais.

**Tarefas:**
1. Crie um Layout mestre (`resources/views/layouts/app.blade.php`) com uma Sidebar à esquerda e um header superior.
2. Gere a View `cells.index` (Tabela de listagem com design limpo).
3. Gere a View `cells.create` (Formulário estruturado em grid).
4. **Interatividade:** Adicione um bloco de script `<script>` usando jQuery para aplicar máscaras de input (ex: formatar CEP e Telefone) nos campos do formulário.

[INSTRUÇÃO PARA A IA]: Forneça o código Blade completo das views solicitadas, utilizando as classes utilitárias do Tailwind CSS. Certifique-se de que o layout seja responsivo.