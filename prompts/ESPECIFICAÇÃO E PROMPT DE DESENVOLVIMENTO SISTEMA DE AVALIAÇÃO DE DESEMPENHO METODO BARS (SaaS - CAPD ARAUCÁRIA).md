# ESPECIFICAÇÃO E PROMPT DE DESENVOLVIMENTO: SISTEMA DE AVALIAÇÃO DE DESEMPENHO (SaaS - CAPD ARAUCÁRIA)

## 1. Contexto e Papel do Desenvolvedor
Atue como um Engenheiro de Software Full-Stack Sênior e Arquiteto de Soluções. Sua missão é desenvolver o **Sistema de Avaliação Periódica de Desempenho (APD)** para os servidores públicos do 
Município de Araucária (aprox. 4.200 usuários). A arquitetura deve garantir segurança jurídica absoluta, mitigar a subjetividade humana aplicando a metodologia BARS, permitir alta customização 
por parte da gestão e automatizar fluxos de dados vitais.

---

## 2. 🛠️ Stack Tecnológica
* **Backend:** PHP 8.x + Laravel (Última versão). Foco em rotas protegidas, API Resources, Policies/Gates de acesso e Form Requests rigorosos.
* **Banco de Dados:** MySQL (utilizando Migrations, Seeders e Eloquent ORM).
* **Frontend:** HTML5 renderizado via Blade Templates.
* **Estilização e UI:** Tailwind CSS para design responsivo (mobile-first) e acessível.
* **Interatividade:** JavaScript (ES6+) e jQuery para manipulação dinâmica do DOM, tooltips (para leitura das âncoras BARS), requisições AJAX e validações *client-side*.
* **Build e Bundling:** Vite.

---

## 3. Integrações Sistêmicas e Barramento de APIs
O sistema deve prever *Jobs/Services* e rotas de consumo (REST/JSON) para garantir a objetividade da avaliação cruzando dados institucionais:
* **Sistema de Ponto Eletrônico:** Integração bidirecional para importar automaticamente dados de faltas injustificadas, atrasos e horas extras.
* **Sistema de RH:** Integração para sincronizar o cadastro de servidores (cargo, nível, lotação, chefia) e importar a carga horária de formação continuada/cursos.
* **Trava de Processo Disciplinar (PAD):** Consulta para alertar a chefia e a comissão caso o servidor avaliado possua processo disciplinar ativo.

---

## 4. Parametrização e Dashboards Dinâmicos
* **Métricas e Pesos:** O administrador define os pesos de cada categoria (ex: Assiduidade peso 2), prazos de início/fim e notas de corte em cada "Ciclo de Avaliação".
* **Módulo de Dashboards:** Utilizar bibliotecas JavaScript (ex: Chart.js ou ApexCharts) para gerar visualizações em tempo real nos seguintes níveis:
    1.  **Individual (Servidor):** Evolução das notas, faltas importadas e status de recursos.
    2.  **Por Categoria (Cargos):** Comparativo de desempenho médio (ex: Saúde vs. Educação).
    3.  **Por Secretaria/Lotação:** Gráficos de barras comparando o desempenho médio entre secretarias.
    4.  **Geral (Executivo):** Visão macro da prefeitura, total de avaliações, média ponderada e volume de incidentes críticos.

---

## 5. Regras de Negócio e Metodologia Core (Segurança Jurídica)
1.  **Metodologia BARS (Escala Gráfica com Ancoragem Comportamental):** É estritamente proibido exibir apenas números frios de 1 a 5 para a chefia. Ao lado ou no "hover" de cada botão de rádio, 
o sistema deve exibir a âncora comportamental padrão da CAPD para balizar a nota:
    * **Nota 1:** *Comportamento reativo, prejudicial ou omisso. Fere normas e necessita de intervenção imediata.*
    * **Nota 2:** *Comportamento inconstante. Necessita de supervisão e cobrança constantes para entregar o mínimo.*
    * **Nota 3:** *Atende plenamente ao esperado. Executa a atribuição com autonomia e qualidade regular.*
    * **Nota 4:** *Acima do esperado. Demonstra proatividade e entrega com qualidade superior e eficiência.*
    * **Nota 5:** *Excelente. Atua como modelo para a equipe, inova, lidera e antecipa riscos sistêmicos.*
2.  **Gatilho de Incidente Crítico (Regra de Ouro):** Se a nota atribuída for **1, 2 ou 5**, o frontend (jQuery) deve bloquear imediatamente a progressão do formulário e abrir um Modal obrigatório.
 Este modal exigirá a aplicação da técnica de incidentes críticos: justificação em texto detalhada e upload de evidência (PDF/JPG). O backend (Laravel Form Request) deve rejeitar sumariamente a
 submissão sem estes dados.
3.  **Recurso Automatizado:** Servidores avaliados podem contestar as notas no painel dentro de um prazo parametrizado, submetendo o fluxo (com upload de defesas) diretamente para a fila da CAPD julgar.

---

## 6. Matriz de Indicadores de Avaliação (Seed do Banco de Dados)
O sistema deve renderizar dinamicamente as perguntas de cada critério (a até h), vinculadas à categoria do servidor avaliado. Utilize os dados abaixo para popular o banco (`evaluation_questions`):

### 🏥 CATEGORIA 1: SAÚDE (Médicos, enfermeiros, técnicos e apoio)
**a) Assiduidade e Pontualidade**
1. O servidor cumpre integralmente sua jornada de trabalho, chegando e saindo nos horários estabelecidos, inclusive nos horários de troca de plantão ou início de atendimentos?
2. Com que frequência o servidor se ausenta do serviço? As ausências ocorrem apenas em casos de extrema necessidade e são comunicadas com antecedência?
3. Durante o horário de expediente, o servidor permanece em seu local de trabalho e está disponível para as demandas da unidade de saúde?
4. O servidor respeita as escalas de trabalho e plantões acordados, evitando solicitações constantes de trocas que possam comprometer a equipe?
5. O servidor demonstra consciência de como seus eventuais atrasos ou faltas impactam o fluxo de atendimento e a sobrecarga dos colegas?
**b) Disciplina**
1. O servidor observa e cumpre as normas regulamentares, protocolos clínicos e procedimentos operacionais padrão (POPs) da instituição?
2. O servidor utiliza corretamente os equipamentos de proteção individual (EPIs) e segue rigorosamente as normas de biossegurança?
3. Como o servidor reage ao receber ordens ou orientações técnicas de seus superiores hierárquicos?
4. O servidor mantém um comportamento condizente com o código de ética de sua profissão e com o estatuto do servidor público?
5. O servidor utiliza os materiais, equipamentos e insumos de saúde de forma consciente, evitando desperdícios e cuidando da conservação do patrimônio público?
**c) Capacidade de Iniciativa**
1. Diante de um problema imprevisto no atendimento ou na rotina da unidade, o servidor busca soluções imediatas ou aguarda passivamente por ordens?
2. O servidor demonstra interesse em se atualizar sobre novas técnicas, medicamentos ou fluxos de atendimento na sua área de atuação?
3. O servidor propõe ideias ou mudanças que visem otimizar o atendimento ao paciente ou a organização do ambiente de trabalho?
4. O servidor demonstra segurança e independência para realizar as tarefas inerentes ao seu cargo dentro de sua competência legal?
5. O servidor consegue prever necessidades de reposição de materiais ou organização do consultório/leito antes que se tornem um problema?
**d) Responsabilidade**
1. O servidor demonstra compromisso com a segurança do paciente, conferindo dados e procedimentos para evitar erros de medicação ou diagnóstico?
2. As tarefas administrativas (preenchimento de prontuários, relatórios, guias) são realizadas com fidedignidade e dentro dos prazos estabelecidos?
3. O servidor mantém o sigilo absoluto sobre as informações e prontuários dos pacientes, conforme as normas legais (LGPD) e éticas?
4. O servidor comparece e se empenha nas capacitações oferecidas para a melhoria do serviço de saúde?
5. O servidor compreende a importância social de sua função na saúde pública e age com o devido zelo e humanização?
**e) Cooperação**
1. O servidor colabora ativamente com os demais membros da equipe multiprofissional (médicos, enfermeiros, técnicos, administrativos)?
2. O servidor contribui para a manutenção de um ambiente de trabalho harmonioso, evitando fofocas ou conflitos desnecessários?
3. O servidor se prontifica a auxiliar os colegas em momentos de alta demanda ou emergências, mesmo que a tarefa não seja estritamente sua?
4. O servidor transmite informações relevantes sobre o estado dos pacientes ou andamento do serviço de forma clara e assertiva para a equipe?
5. O servidor busca resolver divergências de opiniões de forma profissional e construtiva, focando no bem-estar do paciente e da equipe?
**f) Qualidade do Trabalho Executado**
1. Os procedimentos técnicos realizados pelo servidor seguem os padrões de excelência e técnica exigidos para a função?
2. O servidor trata os pacientes, familiares e acompanhantes com empatia, respeito, cortesia e atenção?
3. Os registros feitos pelo servidor (em prontuários ou sistemas) são legíveis, organizados e contêm todas as informações necessárias para a continuidade do cuidado?
4. O servidor consegue realizar suas atividades com qualidade, minimizando o retrabalho e otimizando o tempo de atendimento?
5. O desempenho do servidor reflete positivamente nos indicadores de saúde da unidade e na satisfação dos usuários atendidos?
**g) Participação em Programas de Desenvolvimento de RH**
1. O profissional buscou participar de atualizações sobre protocolos clínicos, normas sanitárias ou sistemas de gestão em saúde no último ciclo?
2. Aplica prontamente no cuidado ao paciente as novas diretrizes técnicas aprendidas em capacitações recentes?
3. Participa de forma ativa dos treinamentos internos sobre o uso de novos equipamentos médicos ou sistemas informatizados da Secretaria?
4. Compartilha com a equipe de plantão os conhecimentos obtidos em congressos, simpósios ou especializações?
5. Demonstra interesse em aprimorar suas competências comportamentais (ex: humanização) em cursos e palestras?
**h) Avaliação pelo Usuário do Serviço Prestado**
1. O usuário relata sentir-se acolhido, respeitado e bem informado pelo profissional quanto aos procedimentos e riscos do tratamento?
2. O profissional recebe avaliações positivas (ou ausência de reclamações) nos canais de Ouvidoria da Saúde do município?
3. Os pacientes e familiares demonstram satisfação com a clareza nas orientações fornecidas durante as consultas/triagens?
4. A presteza do profissional é bem avaliada pelo cidadão em situações de urgência, emergência ou de alta vulnerabilidade emocional?
5. O cidadão sente que sua dignidade, privacidade e valores foram integralmente resguardados durante o atendimento prestado?

### 🛡️ CATEGORIA 2: GUARDA MUNICIPAL (Patrulhamento e Segurança)
**a) Assiduidade e Pontualidade**
1. O servidor apresenta-se uniformizado e equipado para a rendição de postos ou turnos exatamente no horário previsto, evitando atrasos que comprometam o policiamento?
2. O servidor cumpre integralmente sua escala de serviço, incluindo plantões e convocações extraordinárias, mantendo um índice de faltas estritamente justificado?
3. Em situações de emergência ou necessidade de reforço, o servidor demonstra disponibilidade e prontidão quando acionado pela central ou comando?
4. Durante o turno, o servidor permanece em seu setor de patrulhamento ou posto designado, ausentando-se apenas mediante autorização comunicada?
5. O servidor entrega relatórios de ocorrência, documentos de cautela de armas e veículos dentro dos horários estabelecidos ao final do turno?
**b) Disciplina**
1. O servidor demonstra urbanidade e acata prontamente as ordens de seus superiores, seguindo a cadeia de comando estabelecida?
2. O servidor apresenta-se com o uniforme limpo, alinhado e completo, zelando pela correta utilização e porte dos equipamentos de proteção e armamento?
3. O servidor pauta sua conduta pelo Regulamento Disciplinar da Guarda, evitando comportamentos que possam manchar a imagem da instituição?
4. Em situações de estresse ou conflito, o servidor mantém a calma e a disciplina, utilizando a força apenas de forma progressiva e dentro da legalidade?
5. O servidor segue rigorosamente os Procedimentos Operacionais Padrão (POPs) durante abordagens, conduções e patrulhamentos?
**c) Capacidade de Iniciativa**
1. Durante o patrulhamento, o servidor identifica situações de risco potencial (iluminação precária, atitudes suspeitas) e age preventivamente?
2. Diante de situações não previstas nos manuais, o servidor busca soluções criativas e legais para resolver o problema de imediato, garantindo a ordem pública?
3. O servidor propõe mudanças no itinerário de patrulhamento ou na organização do setor que possam aumentar a eficiência da segurança na região?
4. O servidor demonstra interesse em aprimorar suas técnicas de defesa pessoal, legislação ou primeiros socorros de forma proativa?
5. Em ocorrências com múltiplos envolvidos, o servidor assume o protagonismo na organização da cena e na divisão de tarefas até a chegada de apoio?
**d) Responsabilidade**
1. O servidor cuida das viaturas, armamentos e equipamentos de comunicação, realizando as vistorias (check-list) e comunicando imediatamente qualquer avaria?
2. O servidor age com responsabilidade na condução de viaturas e no manuseio de armas, priorizando sempre a segurança dos munícipes e dos colegas?
3. As informações inseridas nos Registros de Ocorrência são verídicas, detalhadas e refletem fielmente os fatos presenciados?
4. O servidor demonstra responsabilidade na preservação de locais de crime e no manuseio de objetos apreendidos, garantindo a integridade da cadeia de custódia?
5. O servidor compreende o impacto de sua função para a segurança da comunidade, agindo com dedicação e profissionalismo em cada chamado?
**e) Cooperação**
1. O servidor mantém uma relação de confiança e apoio mútuo com seu parceiro de viatura ou equipe de posto?
2. O servidor colabora de forma eficiente em operações conjuntas com a Polícia Militar, Polícia Civil ou agentes de trânsito?
3. O servidor compartilha informações relevantes coletadas no campo com os demais turnos e com a inteligência da corporação?
4. O servidor demonstra prontidão para auxiliar colegas de outros setores em situações de perigo ou alta demanda operacional?
5. O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem desunião na tropa ou desrespeito entre pares?
**f) Qualidade do Trabalho Executado**
1. As abordagens realizadas pelo servidor são técnicas, respeitosas e atingem o objetivo de garantir a segurança sem gerar reclamações por abuso?
2. O servidor atende o cidadão com educação, presteza e clareza, orientando-o adequadamente sobre seus direitos e deveres?
3. O servidor demonstra competência técnica no uso de tecnologias não letais e letais, agindo com precisão quando necessário?
4. Os documentos e partes de serviço produzidos são claros, sem erros ortográficos graves e com linguagem técnica adequada?
5. O desempenho do servidor resulta em uma percepção real de segurança no seu setor de atuação, com diminuição de incidentes ou melhoria do ordenamento urbano?
**g) Participação em Programas de Desenvolvimento de RH**
1. O agente participa com assiduidade dos cursos obrigatórios de requalificação anual e treinamentos de tiro fornecidos pela corporação?
2. Aplica corretamente na prática do patrulhamento as atualizações em legislação penal ou de trânsito apreendidas nas formações?
3. Busca aperfeiçoamento contínuo em áreas úteis (como primeiros socorros, defesa pessoal, direitos humanos e mediação de conflitos)?
4. Mostra-se atento e participativo nas instruções teóricas, palestras e preleções pré-turno operacionais?
5. Multiplica o conhecimento tático e operacional assimilado para o restante de sua guarnição?
**h) Avaliação pelo Usuário do Serviço Prestado**
1. O cidadão munícipe avalia positivamente a clareza, educação e firmeza repassada pelo agente em momentos de abordagem ou orientação?
2. Há registros de elogios ao servidor vindos da comunidade do bairro ou setor em que ele atua preventivamente?
3. As populações envolvidas em conflitos ou acidentes relatam que a atuação da guarda municipal foi imparcial, pacificadora e protetora?
4. Em rondas escolares e comunitárias, os pais, docentes e líderes de bairro sentem-se amparados pela atuação do agente?
5. O feedback geral ou dados da Ouvidoria da Guarda indicam ausência de queixas por truculência, omissão ou abuso de autoridade por parte do servidor?

### 🏢 CATEGORIA 3: QUADRO GERAL (Áreas administrativas, operacionais e técnicas)
**a) Assiduidade e Pontualidade**
1. O servidor cumpre sua jornada de trabalho de forma integral, evitando saídas antecipadas ou entradas tardias sem justificativa prévia?
2. Em situações de escalas especiais ou eventos institucionais, o servidor comparece nos dias e horários designados com pontualidade?
3. Quando ocorrem imprevistos, o servidor comunica a chefia imediata em tempo hábil para que o fluxo de trabalho não seja prejudicado?
4. O servidor permanece efetivamente em seu local de atuação durante o expediente, evitando ausências prolongadas e injustificadas de sua mesa ou setor?
5. O servidor observa rigorosamente os tempos destinados a repouso e alimentação, retornando prontamente às suas atividades?
**b) Disciplina**
1. O servidor acata as ordens e orientações de seus superiores com urbanidade e profissionalismo?
2. O servidor segue o estatuto do servidor, as portarias internas e as instruções normativas vigentes na instituição?
3. O servidor mantém um comportamento probo e respeitoso no ambiente de trabalho, tratando colegas e público externo com cortesia?
4. O servidor utiliza os sistemas, internet e e-mail institucional estritamente para fins profissionais e de acordo com a política de segurança da informação?
5. O servidor mantém uma postura adequada ao ambiente público, zelando pela imagem da instituição perante o cidadão?
**c) Capacidade de Iniciativa**
1. Diante de obstáculos rotineiros, o servidor busca alternativas para resolver a questão antes de repassá-la à chefia?
2. O servidor propõe ideias para desburocratizar ou agilizar tarefas administrativas sob sua responsabilidade?
3. O servidor demonstra segurança para tomar decisões dentro do seu limite de competência, sem necessidade de supervisão constante?
4. O servidor demonstra interesse em aprender novas ferramentas, sistemas ou legislações que impactam diretamente sua área de atuação?
5. O servidor antecipa necessidades do setor (como falta de materiais ou prazos vencendo) e age preventivamente?
**d) Responsabilidade**
1. O servidor utiliza materiais de escritório e equipamentos de forma consciente, evitando o desperdício de recursos públicos?
2. O servidor organiza suas demandas de modo a cumprir os cronogramas estabelecidos, evitando o acúmulo de processos ou pendências?
3. O servidor demonstra cautela no manuseio de informações sensíveis ou sigilosas, respeitando a Lei Geral de Proteção de Dados (LGPD)?
4. O servidor executa suas tarefas com atenção à legalidade, garantindo que os atos administrativos não possuam erros que possam gerar nulidades?
5. O servidor assume a responsabilidade pelos erros cometidos, buscando corrigi-los prontamente e aprender com a situação?
**e) Cooperação**
1. O servidor colabora com os colegas de setor, compartilhando conhecimentos e auxiliando na execução de tarefas conjuntas?
2. O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem conflitos ou desmotivação na equipe?
3. O servidor demonstra presteza quando solicitado a colaborar com outros departamentos ou em projetos transversais da prefeitura/órgão?
4. O servidor repassa as informações necessárias para que seus colegas possam dar continuidade ao trabalho em sua ausência ou em processos compartilhados?
5. O servidor recebe críticas construtivas e sugestões de colegas e chefia com abertura e disposição para melhorar?
**f) Qualidade do Trabalho Executado**
1. Os documentos, relatórios e ofícios elaborados pelo servidor apresentam correção gramatical, clareza e conformidade técnica?
2. (Se aplicável) O servidor presta informações corretas, ágeis e educadas aos usuários do serviço público?
3. O servidor mantém seus arquivos (físicos ou digitais) e sua estação de trabalho organizados, facilitando a localização de informações?
4. O servidor mantém um volume de produção compatível com as metas estabelecidas e com a demanda do setor?
5. O servidor demonstra excelência técnica e domínio das ferramentas de trabalho (sistemas, planilhas, equipamentos) necessárias para a execução de suas tarefas?
**g) Participação em Programas de Desenvolvimento de RH**
1. O servidor participou efetivamente de treinamentos, cursos online ou capacitações pertinentes ao seu cargo oferecidos pela administração?
2. O servidor aplica no seu dia a dia os novos conhecimentos adquiridos para agregar melhorias no serviço que executa?
3. Demonstra vontade e proatividade na busca de qualificação profissional, seja através de formação continuada, créditos ou autoaprendizado?
4. Aproveita as ferramentas e reciclagens promovidas pelo RH ou setor de tecnologia para sanar suas dificuldades de adaptação a novos processos?
5. Está disposto a atuar como multiplicador, ensinando novos procedimentos ou sistemas a estagiários e colegas da sua seção?
**h) Avaliação pelo Usuário do Serviço Prestado**
1. Os cidadãos (ou clientes internos) avaliam positivamente a educação, a polidez e a capacidade resolutiva do atendimento prestado por este servidor?
2. As solicitações direcionadas ao servidor geram respostas claras e dentro do prazo, proporcionando a conclusão real da demanda da população?
3. Em registros da Ouvidoria Municipal, as avaliações deste funcionário resultam em ausência de denúncias ou alto índice de elogios?
4. O servidor demonstra imparcialidade, agindo sem favorecimentos e com impessoalidade durante o atendimento às pessoas?
5. O munícipe avalia que as informações prestadas evitaram o "vai e vem" burocrático, sendo precisas desde o primeiro contato?

### 🎓 CATEGORIA 4: EDUCAÇÃO (Magistério e Apoio Pedagógico)
**a) Assiduidade e Pontualidade**
1. O profissional do magistério inicia o seu trabalho, tanto nas regências de classe quanto na hora-atividade (estudos e planejamentos), rigorosamente nos horários definidos pela Unidade Educacional?
2. O educador evita ausências injustificadas que comprometam a sequência didática e a continuidade do processo ensino-aprendizagem dos alunos?
3. Comparece com regularidade e pontualidade às convocações, como Semanas Pedagógicas, conselhos de classe e reuniões com as famílias?
4. Em casos de faltas inevitáveis, a comunicação é feita de imediato à equipe diretiva garantindo que os alunos não fiquem desassistidos?
5. O profissional mantém as suas documentações diárias (registro de faltas, diários de classe) atualizadas pontualmente no sistema escolar?
**b) Disciplina**
1. O profissional segue integralmente as diretrizes do Projeto Político-Pedagógico (PPP) e as instruções normativas da Secretaria Municipal de Educação?
2. Mantém conduta profissional, ética e compatível com a docência no relacionamento com a direção escolar, equipe de pedagogos, alunos e pais?
3. Cumpre os preceitos e as proteções regidas pelo Estatuto da Criança e do Adolescente (ECA) em sala de aula e nas dependências do colégio/CMEI?
4. O docente respeita o uso coletivo e organizado das instalações escolares (laboratórios, bibliotecas, recursos esportivos e tecnológicos)?
5. Acata sem insubordinação indevida as orientações de readequação metodológica solicitadas pela gestão escolar/pedagógica?
**c) Capacidade de Iniciativa**
1. O professor/pedagogo busca ativamente desenvolver ou sugerir novos recursos didáticos e projetos para enriquecer o currículo escolar?
2. Diante de dificuldades de aprendizagem ou problemas disciplinares dos estudantes, propõe ações proativas de recuperação ou de integração?
3. Apresenta autonomia para solucionar imprevistos cotidianos dentro da sala de aula com firmeza e criatividade?
4. Promove a inovação na escola ao utilizar tecnologia, pesquisa e metodologias ativas, visando engajar estudantes desmotivados?
5. Envolve-se ativamente com as decisões comunitárias da escola, ajudando a organizar mostras, grêmios estudantis e eventos com os pais?
**d) Responsabilidade**
1. O profissional assume total compromisso com os prazos de correção de provas, fechamento de notas, e entrega de registros e pareceres avaliativos?
2. Zela incansavelmente pela integridade física, emocional e moral dos alunos (ou bebês/crianças, no caso de Educação Infantil) durante seu turno?
3. Exerce sua função com dedicação para garantir que o tempo em sala seja efetivamente usado para o aprendizado e não para dispersões?
4. Zela pelo bom uso, conservação e economia do material didático, da merenda e do patrimônio mobiliário da Unidade Educacional?
5. Acompanha os alunos e gerencia a segurança e a movimentação deles em ambientes extraclasse ou eventos externos da escola?
**e) Cooperação**
1. O docente ou pedagogo atua de forma colaborativa com seus pares para integrar conteúdos interdisciplinares ou organizar planejamentos conjuntos?
2. Auxilia os colegas de forma voluntária frente a aumentos de demanda ou necessidade de organização de festividades do calendário letivo?
3. Ouve e integra o "feedback" construtivo da pedagogia em relação aos seus planos de aula ou condução de alunos difíceis?
4. Evita a criação de ruídos, conflitos ou posturas excludentes com a equipe de apoio, merendeiras e equipe da limpeza escolar?
5. Atua na mediação de conflitos escolares, fomentando a cultura de paz, respeito e coleguismo entre os estudantes e a comunidade?
**f) Qualidade do Trabalho Executado**
1. O planejamento das aulas e o trabalho diário com os estudantes refletem claro domínio do conteúdo, exatidão e qualidade didática?
2. O aprendizado e a evolução cognitiva ou social dos alunos apresentam nítido avanço em função do trabalho prestado pelo educador?
3. Os métodos de avaliação aplicados pelo professor são compatíveis, justos e alinhados com o diagnóstico de aprendizado promovido pela escola?
4. No papel de pedagogo ou professor, redige os diagnósticos, laudos escolares e atas de classe com gramática e linguagem técnica impecáveis?
5. O educador adapta suas aulas para alcançar alunos com graus diferenciados de cognição (incluindo acessibilidade e Educação Especial)?
**g) Participação em Programas de Desenvolvimento de RH**
1. O profissional atingiu ou superou as metas anuais em cursos de formação continuada ofertados pela Rede de Ensino de Araucária?
2. Demonstra que a capacitação pedagógica (ou especialização acadêmica) cursada se refletiu de forma positiva na sua rotina com as turmas?
3. Busca de forma contínua o progresso em sua habilitação/titulação para enriquecimento intelectual próprio e do município?
4. Atua de maneira engajada durante as Semanas Pedagógicas, oficinas e seminários como multiplicador de saberes para outros docentes?
5. Socializa na Unidade Educacional a confecção ou desenvolvimento de artigos, publicações em congressos e projetos de pesquisa aplicada?
**h) Avaliação pelo Usuário do Serviço Prestado**
1. Os pais e responsáveis demonstram elevado grau de satisfação e confiança na forma como o professor conduz a educação de seus filhos?
2. O educador mantém canais de comunicação éticos, polidos e claros com as famílias nos momentos de reunião, dissipando dúvidas e ansiedades?
3. As crianças ou jovens consideram o professor um exemplo acolhedor e seguro, sem históricos procedentes de tratamentos abusivos, grosseiros ou antiéticos?
4. A comunidade ao entorno da escola percebe e elogia a contribuição do profissional em eventos abertos promovidos pela unidade?
5. Não há incidência de queixas, abaixo-assinados ou representações na Ouvidoria do Município contestando o profissionalismo ou a idoneidade do docente?

---

## 7. Entregáveis Iniciais Exigidos da IA
Para iniciar o projeto, gere o seguinte código baseado nas especificações acima:
1.  **Estrutura de Banco de Dados (Laravel Migrations):** Crie as migrações para Usuários, Ciclos de Avaliação, Perguntas Parametrizáveis (com suporte global ou específico para as colunas de BARS), 
Respostas e Incidentes Críticos. Providencie um Seeder inicial para popular as perguntas.
2.  **Validação e Segurança Backend (PHP 8.x):** Crie o `StoreEvaluationRequest` contendo a validação transacional rigorosa. A regra deve rejeitar a submissão se a nota for 1, 2 ou 5 e não contiver 
o texto de justificação e o anexo correspondente.
3.  **Visualização Dinâmica BARS (Blade + jQuery + Tailwind):** Estruture a view `evaluation.blade.php`. Crie o layout de "Cards" limpo para as perguntas, incluindo *tooltips* ou painéis reveláveis 
para exibir dinamicamente a âncora BARS abaixo de cada botão de rádio. Implemente a lógica jQuery que abre automaticamente o Modal de "Incidente Crítico" nas notas 1, 2 e 5.