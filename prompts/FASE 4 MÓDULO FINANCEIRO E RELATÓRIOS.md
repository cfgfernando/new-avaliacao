# 📊 FASE 4: MOTOR DE RELATÓRIOS E CONTROLE DE MALOTES

Implemente as regras de negócio críticas e a proteção de estado financeiro descritas no contexto.

**Lógica do Relatório Semanal (Weekly Reports):**
1. Crie o `WeeklyReportController` com o fluxo de 3 etapas (Frequência, Espiritual, Financeiro).
2. **Sistema de Lock (Malotes):** Quando um líder submete o relatório, o `status` deve mudar de `Draft` para `Submitted`. A partir desse momento, qualquer tentativa de `update` ou `delete` 
pelo Líder de Célula deve retornar erro HTTP 403 (Forbidden).
3. **Transações Seguras:** Ao receber o malote, o Tesoureiro Master altera o status para `Conciliated`. Utilize `DB::transaction()` no Controller para garantir que a mudança de status do 
relatório e a criação da entrada contábil (`journal_entries`) ocorram de forma atômica (tudo ou nada).

[INSTRUÇÃO PARA A IA]: Gere a lógica do Controller e os Form Requests necessários. Documente o código com comentários explicando a implementação do "Lock" do malote e o uso de `DB::transaction()`.