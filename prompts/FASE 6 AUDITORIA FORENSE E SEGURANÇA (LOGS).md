# 🔍 FASE 6: AUDITORIA FORENSE E SEGURANÇA (LOGS)

Como o MDA CHURCH lida com conformidade contábil e responsabilidade financeira, é obrigatório implementar uma trilha de auditoria forense para garantir a transparência total.

**Tarefas:**
1. **Integração de Auditoria:** Configure o pacote `spatie/laravel-activitylog` ou `owen-it/laravel-auditing` nos Models críticos (`weekly_reports`, `journal_entries`, `expenses`, `members`).
2. **Rastreabilidade Total:** O sistema deve registrar automaticamente:
   - Quem realizou a ação (ID do usuário).
   - O endereço IP e User-Agent.
   - O timestamp exato (data/hora).
   - O estado do dado "Antes" e "Depois" (Old values vs New values).
3. **Prevenção de Adulteração:** Implemente logs de auditoria para tentativas de acesso negado (403 Forbidden) em registros "Locked" (Malotes Conciliados).

**Objetivo:** Garantir que qualquer divergência financeira possa ser periciada com precisão caso haja necessidade de contestação ou auditoria externa.

[INSTRUÇÃO PARA A IA]: Gere o código de configuração do pacote de auditoria selecionado e aplique as Traits de monitoramento nos Models citados. Forneça um exemplo de como visualizar esses logs no 
Dashboard administrativo.