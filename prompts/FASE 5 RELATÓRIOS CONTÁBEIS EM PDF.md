# 🖨️ FASE 5: RELATÓRIOS CONTÁBEIS EM PDF

Com os dados financeiros consolidados, precisamos gerar os relatórios de auditoria no formato A4 para a controladoria. Assuma o uso do pacote `barryvdh/laravel-dompdf`.

**Tarefas:**
1. Crie um `ReportController` responsável por compilar os dados contábeis.
2. Desenvolva um método para gerar a **DRE (Demonstração do Resultado do Exercício)**, somando Entradas (Ofertas, PIX) e subtraindo as Saídas (`expenses`), agrupado por mês.
3. Crie a View Blade dedicada para o PDF (`resources/views/reports/dre-pdf.blade.php`). Use CSS inline e tabelas (`<table>`), pois o conversor de PDF lida melhor com estilos 
tradicionais do que com Flexbox/Grid complexos. O design do PDF deve ser em fundo branco, fonte preta, com cabeçalho contendo o logo e informações da instituição.

[INSTRUÇÃO PARA A IA]: Gere o Controller com a lógica de somatório financeiro (Eloquent aggregation) e a View Blade estruturada para impressão em PDF perfeito.