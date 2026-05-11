# 🚀 FASE 9: GO-LIVE E MIGRAÇÃO DE DADOS (PRODUÇÃO)

Chegou o momento de colocar o sistema no ar para os usuários reais. Esta fase exige precisão para não corromper dados da contabilidade atual da igreja.

**Tarefas:**
1. **Zero-Downtime Deploy:** Configure um script de CI/CD (ex: GitHub Actions + Deployer/Envoyer) para que as atualizações do sistema ocorram sem tirar o ERP do ar.
2. **Setup de Produção:** Ativação de SSL rigoroso, configuração do Redis para filas (Jobs de envio de e-mails/PDFs) e otimização de cache (`php artisan optimize`).
3. **Migração de Dados Legados:** Crie rotinas em PHP (Console Commands) para importar a base de membros antiga (planilhas de Excel/CSV) para o novo banco de dados relacional, cuidando 
da integridade da "Árvore de Discipulado".

**Objetivo:** Garantir uma transição suave do sistema antigo (ou das planilhas manuais) para o novo ERP, com a infraestrutura configurada para máxima performance.

[INSTRUÇÃO PARA A IA]: Escreva um `Console Command` (Artisan) focado na importação de um arquivo CSV de membros legados, contendo a lógica para tratar duplicidades (ex: buscar por CPF/Email) e vincular 
automaticamente ao `mentor_id` correto.