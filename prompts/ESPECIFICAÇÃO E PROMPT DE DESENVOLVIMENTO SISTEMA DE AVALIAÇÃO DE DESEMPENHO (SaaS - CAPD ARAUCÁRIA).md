# ESPECIFICAÇÃO E PROMPT DE DESENVOLVIMENTO: SISTEMA DE AVALIAÇÃO DE DESEMPENHO (SaaS - CAPD ARAUCÁRIA)

## 1. Contexto e Papel do Desenvolvedor
Atue como um Engenheiro de Software Full-Stack Sênior e Arquiteto de Soluções. Sua missão é desenvolver o **Sistema de Avaliação Periódica de Desempenho (APD)** para os servidores públicos do 
Município de Araucária. A arquitetura deve garantir segurança jurídica absoluta, mitigar a subjetividade humana, permitir alta customização por parte da gestão e automatizar fluxos de dados vitais.

---

## 2. 🛠️ Stack Tecnológica
* **Backend:** PHP 8.x + Laravel (Última versão). Foco em rotas protegidas, API Resources e Form Requests rigorosos.
* **Banco de Dados:** MySQL (utilizando Migrations e Eloquent ORM).
* **Frontend:** HTML5 renderizado via Blade Templates.
* **Estilização e UI:** Tailwind CSS para design responsivo e acessível.
* **Interatividade:** JavaScript (ES6+) e jQuery para manipulação dinâmica do DOM, requisições AJAX e validações *client-side*.
* **Build e Bundling:** Vite.

---

## 3. Integrações Sistêmicas e Barramento de APIs
O sistema deve prever *Jobs/Services* e rotas de consumo (REST/JSON) para garantir a objetividade da avaliação cruzando dados institucionais:
* [cite_start]**Sistema de Ponto Eletrônico:** Integração bidirecional para importar automaticamente dados de faltas injustificadas, atrasos e horas extras, calculando de forma sistêmica a base da
 nota de "Assiduidade"[cite: 264, 358].
* [cite_start]**Sistema de RH (Gestão de Pessoas / Escola de Gestão):** Integração para sincronizar o cadastro de servidores (cargo, lotação, chefia imediata) e importar a carga horária de formação 
continuada/cursos[cite: 328, 330].
* **Trava de Processo Disciplinar (PAD):** Consulta para alertar a chefia e a comissão caso o servidor avaliado possua processo disciplinar ativo.

---

## 4. Parametrização e Dashboards Dinâmicos
O painel administrativo da CAPD deve permitir a **parametrização total** do sistema. Nenhuma métrica deve ser "hardcoded":
* **Métricas e Pesos:** O sistema deve permitir a criação de "Ciclos de Avaliação" onde o administrador define os pesos de cada categoria (ex: Assiduidade peso 2, Iniciativa peso 1), prazos de 
início/fim e notas de corte.
* **Módulo de Dashboards:** Utilizar bibliotecas JavaScript (ex: Chart.js ou ApexCharts) para gerar visualizações em tempo real nos seguintes níveis:
    1.  **Dashboard Individual (Servidor):** Gráficos de radar mostrando a evolução das notas ao longo dos ciclos, faltas importadas e status de recursos.
    2.  **Dashboard por Categoria (Cargos):** Comparativo de desempenho médio (ex: Médicos vs. Enfermeiros; Docentes vs. Apoio Escolar).
    3.  **Dashboard por Secretaria/Lotação:** Gráficos de barras comparando o desempenho médio entre secretarias, permitindo identificar gargalos de gestão.
    4.  **Dashboard Geral (Executivo):** Visão macro da prefeitura, total de avaliações concluídas/pendentes, média ponderada institucional e volume de incidentes críticos.

---

## 5. Regras de Negócio e Metodologia Core (Segurança Jurídica)
1.  **Escala Gráfica Fechada:** Notas de 1 (Insatisfatório) a 5 (Excelente). Botões de rádio obrigatórios.
2.  **Gatilho de Incidente Crítico (Regra de Ouro):** Se a nota atribuída for **1, 2 ou 5**, o frontend (jQuery) deve bloquear o formulário e abrir um Modal obrigatório exigindo justificação em texto 
e upload de evidência (PDF/JPG). O backend (Laravel Form Request) deve rejeitar a submissão sem estes dados.
3.  **Recurso Automatizado:** Servidores podem contestar notas dentro do prazo parametrizado, submetendo o fluxo para análise da CAPD.

---

## 6. Matriz de Indicadores de Avaliação (Seed do Banco de Dados)
O sistema deve renderizar as perguntas abaixo de forma dinâmica, vinculadas ao cargo/lotação do servidor avaliado:

### A. QUADRO GERAL (Administrativo e Operacional)
* **Assiduidade e Pontualidade:**
    * [cite_start]Regularidade da Jornada: O servidor cumpre sua jornada de trabalho de forma integral, evitando saídas antecipadas ou entradas tardias sem justificativa prévia? [cite: 358]
    * [cite_start]Cumprimento de Escalas: Em situações de escalas especiais ou eventos institucionais, o servidor comparece nos dias e horários designados com pontualidade? [cite: 359]
    * [cite_start]Comunicação de Ausências: Quando ocorrem imprevistos, o servidor comunica a chefia imediata em tempo hábil para que o fluxo de trabalho não seja prejudicado? [cite: 360]
    * [cite_start]Disponibilidade no Posto: O servidor permanece efetivamente em seu local de atuação durante o expediente, evitando ausências prolongadas e injustificadas de sua mesa ou setor? 
	[cite: 361]
    * [cite_start]Respeito aos Intervalos: O servidor observa rigorosamente os tempos destinados a repouso e alimentação, retornando prontamente às suas atividades? [cite: 362]
* **Disciplina:**
    * [cite_start]Respeito à Hierarquia: O servidor acata as ordens e orientações de seus superiores com urbanidade e profissionalismo? [cite: 364]
    * [cite_start]Observância de Normas: O servidor segue o estatuto do servidor, as portarias internas e as instruções normativas vigentes na instituição? [cite: 365]
    * [cite_start]Conduta Ética: O servidor mantém um comportamento probo e respeitoso no ambiente de trabalho, tratando colegas e público externo com cortesia? [cite: 366]
    * [cite_start]Uso de Recursos Tecnológicos: O servidor utiliza os sistemas, internet e e-mail institucional estritamente para fins profissionais e de acordo com a política de segurança da
	informação? [cite: 367]
    * [cite_start]Apresentação e Postura: O servidor mantém uma postura adequada ao ambiente público, zelando pela imagem da instituição perante o cidadão? [cite: 368]
* **Capacidade de Iniciativa:**
    * [cite_start]Solução de Problemas: Diante de obstáculos rotineiros, o servidor busca alternativas para resolver a questão antes de repassá-la à chefia? [cite: 370]
    * [cite_start]Simplificação de Processos: O servidor propõe ideias para desburocratizar ou agilizar tarefas administrativas sob sua responsabilidade? [cite: 371]
    * [cite_start]Autonomia: O servidor demonstra segurança para tomar decisões dentro do seu limite de competência, sem necessidade de supervisão constante? [cite: 372]
    * [cite_start]Atualização Profissional: O servidor demonstra interesse em aprender novas ferramentas, sistemas ou legislações que impactam diretamente sua área de atuação? [cite: 373]
    * [cite_start]Visão Sistêmica: O servidor antecipa necessidades do setor (como falta de materiais ou prazos vencendo) e age preventivamente? [cite: 374]
* **Responsabilidade:**
    * [cite_start]Zelo pelo Patrimônio: O servidor utiliza materiais de escritório e equipamentos de forma consciente, evitando o desperdício de recursos públicos? [cite: 376]
    * [cite_start]Gestão de Prazos: O servidor organiza suas demandas de modo a cumprir os cronogramas estabelecidos, evitando o acúmulo de processos ou pendências? [cite: 377]
    * [cite_start]Tratamento de Dados: O servidor demonstra cautela no manuseio de informações sensíveis ou sigilosas, respeitando a Lei Geral de Proteção de Dados (LGPD)? [cite: 378]
    * [cite_start]Conformidade Legal: O servidor executa suas tarefas com atenção à legalidade, garantindo que os atos administrativos não possuam erros que possam gerar nulidades? [cite: 379]
    * [cite_start]Comprometimento com Resultados: O servidor assume a responsabilidade pelos erros cometidos, buscando corrigi-los prontamente e aprender com a situação? [cite: 380]
* **Cooperação:**
    * [cite_start]Trabalho em Equipe: O servidor colabora com os colegas de setor, compartilhando conhecimentos e auxiliando na execução de tarefas conjuntas? [cite: 382]
    * [cite_start]Clima Organizacional: O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem conflitos ou desmotivação na equipe? [cite: 383]
    * [cite_start]Disponibilidade Intersetorial: O servidor demonstra presteza quando solicitado a colaborar com outros departamentos ou em projetos transversais da prefeitura/órgão? [cite: 384]
    * [cite_start]Troca de Informações: O servidor repassa as informações necessárias para que seus colegas possam dar continuidade ao trabalho em sua ausência ou em processos compartilhados?
	[cite: 385]
    * [cite_start]Atitude Colaborativa: O servidor recebe críticas construtivas e sugestões de colegas e chefia com abertura e disposição para melhorar? [cite: 386]
* **Qualidade do Trabalho Executado:**
    * [cite_start]Exatidão e Precisão: Os documentos, relatórios e ofícios elaborados pelo servidor apresentam correção gramatical, clareza e conformidade técnica? [cite: 388]
    * [cite_start]Atendimento ao Público: (Se aplicável) O servidor presta informações corretas, ágeis e educadas aos usuários do serviço público? [cite: 389]
    * [cite_start]Organização do Trabalho: O servidor mantém seus arquivos (físicos ou digitais) e sua estação de trabalho organizados, facilitando a localização de informações? [cite: 390]
    * [cite_start]Produtividade: O servidor mantém um volume de produção compatível com as metas estabelecidas e com a demanda do setor? [cite: 392]
    * [cite_start]Excelência Técnica: O servidor demonstra domínio das ferramentas de trabalho (sistemas, planilhas, editores de texto) necessárias para a execução de suas tarefas com qualidade? 
	[cite: 393]

### B. SAÚDE
* **Assiduidade e Pontualidade:**
    * [cite_start]Cumprimento de horários: O servidor cumpre integralmente sua jornada de trabalho, chegando e saindo nos horários estabelecidos, inclusive nos horários de troca de plantão ou início 
	de atendimentos? [cite: 396]
    * Regularidade de presença: Com que frequência o servidor se ausenta do serviço? [cite_start]As ausências ocorrem apenas em casos de extrema necessidade e são comunicadas com antecedência? 
	[cite: 397, 398]
    * [cite_start]Disponibilidade no posto: Durante o horário de expediente, o servidor permanece em seu local de trabalho e está disponível para as demandas da unidade de saúde? [cite: 399]
    * [cite_start]Comprometimento com a escala: O servidor respeita as escalas de trabalho e plantões acordados, evitando solicitações constantes de trocas que possam comprometer a equipe? [cite: 400]
    * [cite_start]Impacto da ausência: O servidor demonstra consciência de como seus eventuais atrasos ou faltas impactam o fluxo de atendimento e a sobrecarga dos colegas? [cite: 401]
* **Disciplina:**
    * [cite_start]Respeito a normas e protocolos: O servidor observa e cumpre as normas regulamentares, protocolos clínicos e procedimentos operacionais padrão (POPs) da instituição? [cite: 403]
    * [cite_start]Uso de EPIs: O servidor utiliza corretamente os equipamentos de proteção individual e segue rigorosamente as normas de biossegurança? [cite: 404]
    * [cite_start]Acato a orientações: Como o servidor reage ao receber ordens ou orientações técnicas de seus superiores hierárquicos? [cite: 405]
    * [cite_start]Conduta ética: O servidor mantém um comportamento condizente com o código de ética de sua profissão e com o estatuto do servidor público? [cite: 406]
    * [cite_start]Zelo pelo patrimônio: O servidor utiliza os materiais, equipamentos e insumos de saúde de forma consciente, evitando desperdícios e cuidando da conservação do patrimônio público?
	[cite: 407]
* **Capacidade de Iniciativa:**
    * [cite_start]Proatividade na resolução: Diante de um problema imprevisto no atendimento ou na rotina da unidade, o servidor busca soluções imediatas ou aguarda passivamente por ordens? [cite: 409]
    * [cite_start]Busca de conhecimento: O servidor demonstra interesse em se atualizar sobre novas técnicas, medicamentos ou fluxos de atendimento na sua área de atuação? [cite: 410]
    * [cite_start]Sugestão de melhorias: O servidor propõe ideias ou mudanças que visem otimizar o atendimento ao paciente ou a organização do ambiente de trabalho? [cite: 411]
    * [cite_start]Autonomia técnica: O servidor demonstra segurança e independência para realizar as tarefas inerentes ao seu cargo dentro de sua competência legal? [cite: 412]
    * [cite_start]Antecipação de necessidades: O servidor consegue prever necessidades de reposição de materiais ou organização do consultório/leito antes que se tornem um problema? [cite: 413]
* **Responsabilidade:**
    * [cite_start]Qualidade do cuidado: O servidor demonstra compromisso com a segurança do paciente, conferindo dados e procedimentos para evitar erros de medicação ou diagnóstico? [cite: 415]
    * [cite_start]Prazos e tarefas: As tarefas administrativas (preenchimento de prontuários, relatórios, guias) são realizadas com fidedignidade e dentro dos prazos estabelecidos? [cite: 416]
    * [cite_start]Sigilo profissional: O servidor mantém o sigilo absoluto sobre as informações e prontuários dos pacientes, conforme as normas legais (LGPD) e éticas? [cite: 417]
    * [cite_start]Assiduidade em treinamentos: O servidor comparece e se empenha nas capacitações oferecidas para a melhoria do serviço de saúde? [cite: 418]
    * [cite_start]Consciência do cargo: O servidor compreende a importância social de sua função na saúde pública e age com o devido zelo e humanização? [cite: 419]
* **Cooperação:**
    * [cite_start]Trabalho em equipe: O servidor colabora ativamente com os demais membros da equipe multiprofissional (médicos, enfermeiros, técnicos, administrativos)? [cite: 421]
    * [cite_start]Clima organizacional: O servidor contribui para a manutenção de um ambiente de trabalho harmonioso, evitando fofocas ou conflitos desnecessários? [cite: 422]
    * [cite_start]Disponibilidade para ajudar: O servidor se prontifica a auxiliar os colegas em momentos de alta demanda ou emergências, mesmo que a tarefa não seja estritamente sua? [cite: 423]
    * [cite_start]Compartilhamento de informações: O servidor transmite informações relevantes sobre o estado dos pacientes ou andamento do serviço de forma clara e assertiva para a equipe? [cite: 424]
    * [cite_start]Mediação de conflitos: O servidor busca resolver divergências de opiniões de forma profissional e construtiva, focando no bem-estar do paciente e da equipe? [cite: 425]
* **Qualidade do Trabalho Executado:**
    * [cite_start]Precisão técnica: Os procedimentos técnicos realizados pelo servidor seguem os padrões de excelência e técnica exigidos para a função? [cite: 427]
    * [cite_start]Humanização do atendimento: O servidor trata os pacientes, familiares e acompanhantes com empatia, respeito, cortesia e atenção? [cite: 428]
    * [cite_start]Organização e clareza: Os registros feitos pelo servidor (em prontuários ou sistemas) são legíveis, organizados e contêm todas as informações necessárias para a continuidade do 
	cuidado? [cite: 429]
    * [cite_start]Eficiência técnica: O servidor consegue realizar suas atividades com qualidade, minimizando o retrabalho e otimizando o tempo de atendimento? [cite: 430]
    * [cite_start]Resultados alcançados: O desempenho do servidor reflete positivamente nos indicadores de saúde da unidade e na satisfação dos usuários atendidos? [cite: 431]

### C. GUARDA MUNICIPAL (Segurança Pública)
* **Assiduidade e Pontualidade:**
    * [cite_start]Pontualidade no Rendição: O servidor apresenta-se uniformizado e equipado para a rendição de postos ou turnos exatamente no horário previsto, evitando atrasos que comprometam a 
	continuidade do policiamento? [cite: 227]
    * [cite_start]Assiduidade nas Escalas: O servidor cumpre integralmente sua escala de serviço, incluindo plantões e convocações extraordinárias, mantendo um índice de faltas dentro do estritamente
	justificado? [cite: 228]
    * [cite_start]Disponibilidade em Chamadas: Em situações de emergência ou necessidade de reforço, o servidor demonstra disponibilidade e prontidão quando acionado pela central ou comando? [cite: 229]
    * [cite_start]Permanência no Posto/Viatura: Durante o turno, o servidor permanece em seu setor de patrulhamento ou posto designado, ausentando-se apenas mediante autorização ou necessidade 
	operacional comunicada? [cite: 230]
    * [cite_start]Cumprimento de Prazos Administrativos: O servidor entrega relatórios de ocorrência, documentos de cautela de armas e veículos dentro dos horários estabelecidos ao final do turno? 
	[cite: 231]
* **Disciplina:**
    * [cite_start]Respeito à Hierarquia: O servidor demonstra urbanidade e acata prontamente as ordens de seus superiores, seguindo a cadeia de comando estabelecida? [cite: 233]
    * [cite_start]Uso de Uniforme e Equipamentos: O servidor apresenta-se com o uniforme limpo, alinhado e completo, zelando pela correta utilização e porte dos equipamentos de proteção e armamento?
	[cite: 234]
    * [cite_start]Observância do Regulamento Interno: O servidor pauta sua conduta pelo Regulamento Disciplinar da Guarda, evitando comportamentos que possam manchar a imagem da instituição? [cite: 235]
    * [cite_start]Controle Emocional: Em situações de estresse ou conflito, o servidor mantém a calma e a disciplina, utilizando a força apenas de forma progressiva e dentro da legalidade? [cite: 236]
    * [cite_start]Cumprimento de POPs: O servidor segue rigorosamente os Procedimentos Operacionais Padrão (POPs) durante abordagens, conduções e patrulhamentos? [cite: 237]
* **Capacidade de Iniciativa:**
    * [cite_start]Antecipação de Riscos: Durante o patrulhamento, o servidor identifica situações de risco potencial (iluminação precária, atitudes suspeitas) e age preventivamente antes que se 
	tornem ocorrências graves? [cite: 239]
    * [cite_start]Resolução de Problemas no Campo: Diante de situações não previstas nos manuais, o servidor busca soluções criativas e legais para resolver o problema de imediato, garantindo a ordem
	pública? [cite: 240]
    * [cite_start]Sugestões de Melhoria: O servidor propõe mudanças no itinerário de patrulhamento ou na organização do setor que possam aumentar a eficiência da segurança na região? [cite: 241]
    * [cite_start]Busca por Treinamento: O servidor demonstra interesse em aprimorar suas técnicas de defesa pessoal, legislação ou primeiros socorros de forma proativa? [cite: 242]
    * [cite_start]Liderança Situacional: Em ocorrências com múltiplos envolvidos, o servidor assume o protagonismo na organização da cena e na divisão de tarefas até a chegada de apoio? [cite: 243]
* **Responsabilidade:**
    * [cite_start]Zelo com o Patrimônio: O servidor cuida das viaturas, armamentos e equipamentos de comunicação, realizando as vistorias (check-list) e comunicando imediatamente qualquer avaria? 
	[cite: 245]
    * [cite_start]Segurança de Terceiros: O servidor age com responsabilidade na condução de viaturas e no manuseio de armas, priorizando sempre a segurança dos munícipes e dos colegas? [cite: 246]
    * [cite_start]Fidelidade nos Relatórios: As informações inseridas nos Registros de Ocorrência são verídicas, detalhadas e refletem fielmente os fatos presenciados? [cite: 247]
    * [cite_start]Guarda de Objetos e Provas: O servidor demonstra responsabilidade na preservação de locais de crime e no manuseio de objetos apreendidos, garantindo a integridade da cadeia de 
	custódia? [cite: 248]
    * [cite_start]Compromisso com o Munícipe: O servidor compreende o impacto de sua função para a segurança da comunidade, agindo com dedicação e profissionalismo em cada chamado? [cite: 249]
* **Cooperação:**
    * [cite_start]Trabalho com a Equipe de Guarnição: O servidor mantém uma relação de confiança e apoio mútuo com seu parceiro de viatura ou equipe de posto? [cite: 251]
    * [cite_start]Integração com outras Forças: O servidor colabora de forma eficiente em operações conjuntas com a Polícia Militar, Polícia Civil ou agentes de trânsito? [cite: 252]
    * [cite_start]Transmissão de Informações: O servidor compartilha informações relevantes coletadas no campo com os demais turnos e com a inteligência da corporação? [cite: 253]
    * [cite_start]Apoio a Colegas: O servidor demonstra prontidão para auxiliar colegas de outros setores em situações de perigo ou alta demanda operacional? [cite: 254]
    * [cite_start]Clima Interno: O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem desunião na tropa ou desrespeito entre pares? [cite: 255]
* **Qualidade do Trabalho Executado:**
    * [cite_start]Eficácia nas Abordagens: As abordagens realizadas pelo servidor são técnicas, respeitosas e atingem o objetivo de garantir a segurança sem gerar reclamações por abuso? [cite: 257]
    * [cite_start]Atendimento Comunitário: O servidor atende o cidadão com educação, presteza e clareza, orientando-o adequadamente sobre seus direitos e deveres? [cite: 258]
    * [cite_start]Domínio de Técnicas e Armas: O servidor demonstra competência técnica no uso de tecnologias não letais e letais, agindo com precisão quando necessário? [cite: 259]
    * [cite_start]Redação e Documentação: Os documentos e partes de serviço produzidos são claros, sem erros ortográficos graves e com linguagem técnica adequada? [cite: 260]
    * [cite_start]Impacto na Segurança Local: O desempenho do servidor resulta em uma percepção real de segurança no seu setor de atuação, com diminuição de incidentes ou melhoria do ordenamento urbano?
	[cite: 261]

### D. EDUCAÇÃO
* [cite_start]**Assiduidade e Pontualidade:** O docente inicia as atividades pedagógicas e as aulas rigorosamente nos horários previstos pela Unidade Educacional? [cite: 267]
* [cite_start]**Disciplina:** O profissional segue as diretrizes do Projeto Político-Pedagógico e as instruções normativas da Secretaria Municipal de Educação? [cite: 277, 278]
* [cite_start]**Capacidade de Iniciativa:** O professor busca ativamente novos recursos didáticos ou tecnologias para superar dificuldades de aprendizagem dos alunos? [cite: 288]
* [cite_start]**Responsabilidade:** O docente assume o compromisso com os prazos de planejamento, avaliação e entrega de registros da vida escolar dos estudantes? [cite: 300, 301]
* [cite_start]**Cooperação:** O profissional participa de forma colaborativa e propositiva nos planejamentos coletivos, conselhos de classe e reuniões pedagógicas? [cite: 312]
* [cite_start]**Qualidade do Trabalho:** As estratégias de ensino-aprendizagem resultam em evolução perceptível do aproveitamento escolar e desenvolvimento dos alunos? [cite: 323, 324]
* [cite_start]**Desenvolvimento de RH:** O docente completou a carga horária exigida de formação continuada e participou integralmente das Semanas Pedagógicas? [cite: 338, 339]
* [cite_start]**Avaliação pelo Usuário:** As famílias e responsáveis demonstram satisfação com a comunicação e o atendimento prestado pelo profissional na Unidade Educacional? [cite: 349, 350]

---

## 7. Entregáveis Iniciais Exigidos da IA
Para iniciar o projeto, gere o seguinte código baseado nas especificações acima:
1.  **Estrutura de Banco de Dados (Laravel Migrations):** Crie as migrações para Usuários, Ciclos de Avaliação, Perguntas Parametrizáveis, Respostas e Incidentes Críticos.
2.  **Validação e Segurança Backend (PHP 8.x):** Crie o `StoreEvaluationRequest` contendo a validação rigorosa (rejeitando submissão se nota 1, 2 ou 5 não possuir justificação em texto e anexo).
3.  **Visualização Dinâmica (Blade + jQuery + Tailwind):** Estruture a view `evaluation.blade.php`, incluindo o painel de avaliação, cards das perguntas e a lógica em jQuery que escuta alterações 
nos *radio buttons* para abrir automaticamente o Modal de 
"Incidente Crítico".