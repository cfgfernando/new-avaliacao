# ⚙️ FASE 2: CONTROLADORES, VALIDAÇÕES E ROTAS

O banco de dados está pronto. Agora, implemente a camada de controle no padrão MVC para a **Gestão de Células e Membros**, respeitando o RBAC (Role-Based Access Control).

**Tarefas:**
1. **Middlewares:** Crie um Middleware ou Policies (`CellPolicy`, `MemberPolicy`) para garantir que um "Líder de Célula" só possa acessar/editar dados da sua própria `cell_id`.
2. **Form Requests:** Crie classes de validação separadas (ex: `StoreCellRequest`, `UpdateMemberRequest`) para limpar os Controllers. Defina regras estritas (ex: `email` único, 
`mentor_id` deve existir).
3. **Controllers:** Crie os controladores resource (`CellController`, `MemberController`).
4. **Rotas:** Gere o arquivo `routes/web.php` agrupando essas rotas sob o middleware de autenticação (`auth`) e organizando os prefixos.

[INSTRUÇÃO PARA A IA]: Gere APENAS o código dos Controllers, Form Requests, Policies/Middlewares e `web.php`. Mantenha os métodos dos Controllers enxutos, delegando a validação aos Form Requests.
 Não gere Views HTML.