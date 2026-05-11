# 🧪 FASE 8: HOMOLOGAÇÃO E TESTES DE CARGA (STRESS TEST)

A prova final do sistema é garantir que ele suporte o "horário de pico" e que as transações bancárias não falhem sob pressão.

**Tarefas:**
1. **Seeders de Alta Volumetria:** Crie `Factories` e `Seeders` robustos para popular o banco de dados com dados fictícios de:
   - 1.000+ Membros.
   - 100+ Células.
   - 2.000+ Relatórios Semanais históricos.
2. **Simulação de Concorrência:** Teste a lógica de `DB::transaction()` simulando múltiplos Líderes de Célula enviando o "Weekly Report" simultaneamente (Simulação de Domingo à Noite).
3. **Verificação de Deadlocks:** Monitore se há travamentos no banco de dados durante o fechamento em massa de malotes pela Tesouraria.

**Objetivo:** Certificar que a infraestrutura e o código suportem picos de tráfego sem perda de integridade ou degradação de resposta.

[INSTRUÇÃO PARA A IA]: Gere as Factories e o DatabaseSeeder necessários para popular o sistema com os volumes descritos. Forneça um script básico ou orientação de como testar a concorrência das transações.