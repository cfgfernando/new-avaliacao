# 🚀 FASE 7: INFRAESTRUTURA E ARQUITETURA SINGLE-TENANT

A preparação do ambiente de produção deve priorizar a integridade total dos dados e a performance local, evitando os riscos de latência e vazamento de dados de modelos multi-tenant genéricos.

**Tarefas:**
1. **Arquitetura Single-Tenant:** Estruture o ambiente para que a base de dados da instituição opere em um silo isolado e exclusivo.
2. **Configuração de Servidor:** Defina as diretrizes para uma stack otimizada (Nginx, PHP-FPM 8.x, MySQL 8.0).
3. **Backup Automatizado:** Crie um comando Artisan ou script de sistema para realizar o backup diário automático do banco de dados e dos arquivos anexos (comprovantes de despesas), 
com envio para armazenamento externo (ex: S3 ou Drive).
4. **Otimização de Assets:** Configure o pipeline do Vite para produção (minify, versionamento).

**Objetivo:** Garantir que a aplicação seja resiliente, privada e performática sob uso intenso.

[INSTRUÇÃO PARA A IA]: Forneça um guia de configuração do servidor Nginx otimizado para Laravel e o código de uma rotina de backup (Artisan Command) para o banco de dados.