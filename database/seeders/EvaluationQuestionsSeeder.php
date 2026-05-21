<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EvaluationQuestionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar tabela para evitar duplicações
        Schema::disableForeignKeyConstraints();
        DB::table('evaluation_questions')->truncate();
        Schema::enableForeignKeyConstraints();

        $now = now();

        $questions = [
            // ==========================================
            // A. QUADRO GERAL (geral) — 40 perguntas
            // ==========================================

            // --- Assiduidade (5) ---
            ['group_type' => 'geral', 'category' => 'assiduidade', 'text' => 'Regularidade da Jornada: O servidor cumpre sua jornada de trabalho de forma integral, evitando saídas antecipadas ou entradas tardias sem justificativa prévia?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'assiduidade', 'text' => 'Cumprimento de Escalas: Em situações de escalas especiais ou eventos institucionais, o servidor comparece nos dias e horários designados com pontualidade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'assiduidade', 'text' => 'Comunicação de Ausências: Quando ocorrem imprevistos, o servidor comunica a chefia imediata em tempo hábil para que o fluxo de trabalho não seja prejudicado?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'assiduidade', 'text' => 'Disponibilidade no Posto: O servidor permanece efetivamente em seu local de atuação durante o expediente, evitando ausências prolongadas e injustificadas de sua mesa ou setor?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'assiduidade', 'text' => 'Respeito aos Intervalos: O servidor observa rigorosamente os tempos destinados a repouso e alimentação, retornando prontamente às suas atividades?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Disciplina (5) ---
            ['group_type' => 'geral', 'category' => 'disciplina', 'text' => 'Respeito à Hierarquia: O servidor acata as ordens e orientações de seus superiores com urbanidade e profissionalismo?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'disciplina', 'text' => 'Observância de Normas: O servidor segue o estatuto do servidor, as portarias internas e as instruções normativas vigentes na instituição?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'disciplina', 'text' => 'Conduta Ética: O servidor mantém um comportamento probo e respeitoso no ambiente de trabalho, tratando colegas e público externo com cortesia?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'disciplina', 'text' => 'Uso de Recursos Tecnológicos: O servidor utiliza os sistemas, internet e e-mail institucional estritamente para fins profissionais e de acordo com a política de segurança da informação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'disciplina', 'text' => 'Apresentação e Postura: O servidor mantém uma postura adequada ao ambiente público, zelando pela imagem da instituição perante o cidadão?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Iniciativa (5) ---
            ['group_type' => 'geral', 'category' => 'iniciativa', 'text' => 'Solução de Problemas: Diante de obstáculos rotineiros, o servidor busca alternativas para resolver a questão antes de repassá-la à chefia?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'iniciativa', 'text' => 'Simplificação de Processos: O servidor propõe ideias para desburocratizar ou agilizar tarefas administrativas sob sua responsabilidade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'iniciativa', 'text' => 'Autonomia: O servidor demonstra segurança para tomar decisões dentro do seu limite de competência, sem necessidade de supervisão constante?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'iniciativa', 'text' => 'Atualização Profissional: O servidor demonstra interesse em aprender novas ferramentas, sistemas ou legislações que impactam diretamente sua área de atuação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'iniciativa', 'text' => 'Visão Sistêmica: O servidor antecipa necessidades do setor (como falta de materiais ou prazos vencendo) e age preventivamente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Responsabilidade (5) ---
            ['group_type' => 'geral', 'category' => 'responsabilidade', 'text' => 'Zelo pelo Patrimônio: O servidor utiliza materiais de escritório e equipamentos de forma consciente, evitando o desperdício de recursos públicos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'responsabilidade', 'text' => 'Gestão de Prazos: O servidor organiza suas demandas de modo a cumprir os cronogramas estabelecidos, evitando o acúmulo de processos ou pendências?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'responsabilidade', 'text' => 'Tratamento de Dados: O servidor demonstra cautela no manuseio de informações sensíveis ou sigilosas, respeitando a Lei Geral de Proteção de Dados (LGPD)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'responsabilidade', 'text' => 'Conformidade Legal: O servidor executa suas tarefas com atenção à legalidade, garantindo que os atos administrativos não possuam erros que possam gerar nulidades?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'responsabilidade', 'text' => 'Comprometimento com Resultados: O servidor assume a responsabilidade pelos erros cometidos, buscando corrigi-los prontamente e aprender com a situação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Cooperação (5) ---
            ['group_type' => 'geral', 'category' => 'cooperacao', 'text' => 'Trabalho em Equipe: O servidor colabora com os colegas de setor, compartilhando conhecimentos e auxiliando na execução de tarefas conjuntas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'cooperacao', 'text' => 'Clima Organizacional: O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem conflitos ou desmotivação na equipe?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'cooperacao', 'text' => 'Disponibilidade Intersetorial: O servidor demonstra presteza quando solicitado a colaborar com outros departamentos ou em projetos transversais da prefeitura/órgão?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'cooperacao', 'text' => 'Troca de Informações: O servidor repassa as informações necessárias para que seus colegas possam dar continuidade ao trabalho em sua ausência ou em processos compartilhados?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'cooperacao', 'text' => 'Atitude Colaborativa: O servidor recebe críticas construtivas e sugestões de colegas e chefia com abertura e disposição para melhorar?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Qualidade (5) ---
            ['group_type' => 'geral', 'category' => 'qualidade', 'text' => 'Exatidão e Precisão: Os documentos, relatórios e ofícios elaborados pelo servidor apresentam correção gramatical, clareza e conformidade técnica?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'qualidade', 'text' => 'Atendimento ao Público: O servidor presta informações corretas, ágeis e educadas aos usuários do serviço público?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'qualidade', 'text' => 'Organização do Trabalho: O servidor mantém seus arquivos (físicos ou digitais) e sua estação de trabalho organizados, facilitando a localização de informações?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'qualidade', 'text' => 'Produtividade: O servidor mantém um volume de produção compatível com as metas estabelecidas e com a demanda do setor?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'qualidade', 'text' => 'Excelência Técnica: O servidor demonstra domínio das ferramentas de trabalho (sistemas, planilhas, editores de texto) necessárias para a execução de suas tarefas com qualidade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Desenvolvimento RH (5) ---
            ['group_type' => 'geral', 'category' => 'desenvolvimento_rh', 'text' => 'O servidor participou efetivamente de treinamentos, cursos online ou capacitações pertinentes ao seu cargo oferecidos pela administração?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'desenvolvimento_rh', 'text' => 'O servidor aplica no seu dia a dia os novos conhecimentos adquiridos para agregar melhorias no serviço que executa?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'desenvolvimento_rh', 'text' => 'Demonstra vontade e proatividade na busca de qualificação profissional, seja através de formação continuada, créditos ou autoaprendizado?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'desenvolvimento_rh', 'text' => 'Aproveita as ferramentas e reciclagens promovidas pelo RH ou setor de tecnologia para sanar suas dificuldades de adaptação a novos processos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'desenvolvimento_rh', 'text' => 'Está disposto a atuar como multiplicador, ensinando novos procedimentos ou sistemas a estagiários e colegas da sua seção?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Avaliação pelo Usuário (5) ---
            ['group_type' => 'geral', 'category' => 'avaliacao_usuario', 'text' => 'Os cidadãos (ou clientes internos) avaliam positivamente a educação, a polidez e a capacidade resolutiva do atendimento prestado por este servidor?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'avaliacao_usuario', 'text' => 'As solicitações direcionadas ao servidor geram respostas claras e dentro do prazo, proporcionando a conclusão real da demanda da população?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'avaliacao_usuario', 'text' => 'Em registros da Ouvidoria Municipal, as avaliações deste funcionário resultam em ausência de denúncias ou alto índice de elogios?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'avaliacao_usuario', 'text' => 'O servidor demonstra imparcialidade, agindo sem favorecimentos e com impessoalidade durante o atendimento às pessoas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'geral', 'category' => 'avaliacao_usuario', 'text' => 'O munícipe avalia que as informações prestadas evitaram o "vai e vem" burocrático, sendo precisas desde o primeiro contato?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // ==========================================
            // B. SAÚDE (saude) — 40 perguntas
            // ==========================================

            // --- Assiduidade (5) ---
            ['group_type' => 'saude', 'category' => 'assiduidade', 'text' => 'Cumprimento de horários: O servidor cumpre integralmente sua jornada de trabalho, chegando e saindo nos horários estabelecidos, inclusive nos horários de troca de plantão ou início de atendimentos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'assiduidade', 'text' => 'Regularidade de presença: O servidor se ausenta do serviço apenas em casos de extrema necessidade e comunica com antecedência?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'assiduidade', 'text' => 'Disponibilidade no posto: Durante o horário de expediente, o servidor permanece em seu local de trabalho e está disponível para as demandas da unidade de saúde?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'assiduidade', 'text' => 'Comprometimento com a escala: O servidor respeita as escalas de trabalho e plantões acordados, evitando solicitações constantes de trocas que possam comprometer a equipe?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'assiduidade', 'text' => 'Impacto da ausência: O servidor demonstra consciência de como seus eventuais atrasos ou faltas impactam o fluxo de atendimento e a sobrecarga dos colegas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Disciplina (5) ---
            ['group_type' => 'saude', 'category' => 'disciplina', 'text' => 'Respeito a normas e protocolos: O servidor observa e cumpre as normas regulamentares, protocolos clínicos e procedimentos operacionais padrão (POPs) da instituição?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'disciplina', 'text' => 'Uso de EPIs: O servidor utiliza corretamente os equipamentos de proteção individual e segue rigorosamente as normas de biossegurança?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'disciplina', 'text' => 'Acato a orientações: O servidor reage com profissionalismo ao receber ordens ou orientações técnicas de seus superiores hierárquicos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'disciplina', 'text' => 'Conduta ética: O servidor mantém um comportamento condizente com o código de ética de sua profissão e com o estatuto do servidor público?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'disciplina', 'text' => 'Zelo pelo patrimônio: O servidor utiliza os materiais, equipamentos e insumos de saúde de forma consciente, evitando desperdícios e cuidando da conservação do patrimônio público?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Iniciativa (5) ---
            ['group_type' => 'saude', 'category' => 'iniciativa', 'text' => 'Proatividade na resolução: Diante de um problema imprevisto no atendimento ou na rotina da unidade, o servidor busca soluções imediatas ou age preventivamente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'iniciativa', 'text' => 'Busca de conhecimento: O servidor demonstra interesse em se atualizar sobre novas técnicas, medicamentos ou fluxos de atendimento na sua área de atuação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'iniciativa', 'text' => 'Sugestão de melhorias: O servidor propõe ideias ou mudanças que visem otimizar o atendimento ao paciente ou a organização do ambiente de trabalho?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'iniciativa', 'text' => 'Autonomia técnica: O servidor demonstra segurança e independência para realizar as tarefas inerentes ao seu cargo dentro de sua competência legal?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'iniciativa', 'text' => 'Antecipação de necessidades: O servidor consegue prever necessidades de reposição de materiais ou organização do consultório/leito antes que se tornem um problema?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Responsabilidade (5) ---
            ['group_type' => 'saude', 'category' => 'responsabilidade', 'text' => 'Qualidade do cuidado: O servidor demonstra compromisso com a segurança do paciente, conferindo dados e procedimentos para evitar erros de medicação ou diagnóstico?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'responsabilidade', 'text' => 'Prazos e tarefas: As tarefas administrativas (preenchimento de prontuários, relatórios, guias) são realizadas com fidedignidade e dentro dos prazos estabelecidos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'responsabilidade', 'text' => 'Sigilo profissional: O servidor mantém o sigilo absoluto sobre as informações e prontuários dos pacientes, conforme as normas legais (LGPD) e éticas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'responsabilidade', 'text' => 'Assiduidade em treinamentos: O servidor comparece e se empenha nas capacitações oferecidas para a melhoria do serviço de saúde?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'responsabilidade', 'text' => 'Consciência do cargo: O servidor compreende a importância social de sua função na saúde pública e age com o devido zelo e humanização?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Cooperação (5) ---
            ['group_type' => 'saude', 'category' => 'cooperacao', 'text' => 'Trabalho em equipe: O servidor colabora ativamente com os demais membros da equipe multiprofissional (médicos, enfermeiros, técnicos, administrativos)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'cooperacao', 'text' => 'Clima organizacional: O servidor contribui para a manutenção de um ambiente de trabalho harmonioso, evitando fofocas ou conflitos desnecessários?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'cooperacao', 'text' => 'Disponibilidade para ajudar: O servidor se prontifica a auxiliar os colegas em momentos de alta demanda ou emergências, mesmo que a tarefa não seja estritamente sua?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'cooperacao', 'text' => 'Compartilhamento de informações: O servidor transmite informações relevantes sobre o estado dos pacientes ou andamento do serviço de forma clara e assertiva para a equipe?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'cooperacao', 'text' => 'Mediação de conflitos: O servidor busca resolver divergências de opiniões de forma profissional e construtiva, focando no bem-estar do paciente e da equipe?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Qualidade (5) ---
            ['group_type' => 'saude', 'category' => 'qualidade', 'text' => 'Precisão técnica: Os procedimentos técnicos realizados pelo servidor seguem os padrões de excelência e técnica exigidos para a função?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'qualidade', 'text' => 'Humanização do atendimento: O servidor trata os pacientes, familiares e acompanhantes com empatia, respeito, cortesia e atenção?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'qualidade', 'text' => 'Organização e clareza: Os registros feitos pelo servidor (em prontuários ou sistemas) são legíveis, organizados e contêm todas as informações necessárias para a continuidade do cuidado?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'qualidade', 'text' => 'Eficiência técnica: O servidor consegue realizar suas atividades com qualidade, minimizando o retrabalho e otimizando o tempo de atendimento?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'qualidade', 'text' => 'Resultados alcançados: O desempenho do servidor reflete positivamente nos indicadores de saúde da unidade e na satisfação dos usuários atendidos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Desenvolvimento RH (5) ---
            ['group_type' => 'saude', 'category' => 'desenvolvimento_rh', 'text' => 'O profissional buscou participar de atualizações sobre protocolos clínicos, normas sanitárias ou sistemas de gestão em saúde no último ciclo?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'desenvolvimento_rh', 'text' => 'Aplica prontamente no cuidado ao paciente as novas diretrizes técnicas aprendidas em capacitações recentes?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'desenvolvimento_rh', 'text' => 'Participa de forma ativa dos treinamentos internos sobre o uso de novos equipamentos médicos ou sistemas informatizados da Secretaria?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'desenvolvimento_rh', 'text' => 'Compartilha com a equipe de plantão os conhecimentos obtidos em congressos, simpósios ou especializações?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'desenvolvimento_rh', 'text' => 'Demonstra interesse em aprimorar suas competências comportamentais (ex: humanização) em cursos e palestras?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Avaliação pelo Usuário (5) ---
            ['group_type' => 'saude', 'category' => 'avaliacao_usuario', 'text' => 'O usuário relata sentir-se acolhido, respeitado e bem informado pelo profissional quanto aos procedimentos e riscos do tratamento?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'avaliacao_usuario', 'text' => 'O profissional recebe avaliações positivas (ou ausência de reclamações) nos canais de Ouvidoria da Saúde do município?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'avaliacao_usuario', 'text' => 'Os pacientes e familiares demonstram satisfação com a clareza nas orientações fornecidas durante as consultas/triagens?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'avaliacao_usuario', 'text' => 'A presteza do profissional é bem avaliada pelo cidadão em situações de urgência, emergência ou de alta vulnerabilidade emocional?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'saude', 'category' => 'avaliacao_usuario', 'text' => 'O cidadão sente que sua dignidade, privacidade e valores foram integralmente resguardados durante o atendimento prestado?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // ==========================================
            // C. GUARDA MUNICIPAL (guarda) — 40 perguntas
            // ==========================================

            // --- Assiduidade (5) ---
            ['group_type' => 'guarda', 'category' => 'assiduidade', 'text' => 'Pontualidade na Rendição: O servidor apresenta-se uniformizado e equipado para a rendição de postos ou turnos exatamente no horário previsto, evitando atrasos que comprometam a continuidade do policiamento?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'assiduidade', 'text' => 'Assiduidade nas Escalas: O servidor cumpre integralmente sua escala de serviço, incluindo plantões e convocações extraordinárias, mantendo um índice de faltas dentro do estritamente justificado?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'assiduidade', 'text' => 'Disponibilidade em Chamadas: Em situações de emergência ou necessidade de reforço, o servidor demonstra disponibilidade e prontidão quando acionado pela central ou comando?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'assiduidade', 'text' => 'Permanência no Posto/Viatura: Durante o turno, o servidor permanece em seu setor de patrulhamento ou posto designado, ausentando-se apenas mediante autorização ou necessidade operacional?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'assiduidade', 'text' => 'Cumprimento de Prazos Administrativos: O servidor entrega relatórios de ocorrência, documentos de cautela de armas e veículos dentro dos horários estabelecidos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Disciplina (5) ---
            ['group_type' => 'guarda', 'category' => 'disciplina', 'text' => 'Respeito à Hierarquia: O servidor demonstra urbanidade e acata prontamente as ordens de seus superiores, seguindo a cadeia de comando estabelecida?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'disciplina', 'text' => 'Uso de Uniforme e Equipamentos: O servidor apresenta-se com o uniforme limpo, alinhado e completo, zelando pela correta utilização e porte dos equipamentos de proteção e armamento?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'disciplina', 'text' => 'Observância do Regulamento Interno: O servidor pauta sua conduta pelo Regulamento Disciplinar da Guarda, evitando comportamentos inadequados?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'disciplina', 'text' => 'Controle Emocional: Em situações de estresse ou conflito, o servidor mantém a calma e a disciplina, utilizando a força apenas de forma progressiva e legal?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'disciplina', 'text' => 'Cumprimento de POPs: O servidor segue rigorosamente os Procedimentos Operacionais Padrão (POPs) durante abordagens, conduções e patrulhamentos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Iniciativa (5) ---
            ['group_type' => 'guarda', 'category' => 'iniciativa', 'text' => 'Antecipação de Riscos: Durante o patrulhamento, o servidor identifica situações de risco potencial (iluminação precária, atitudes suspeitas) e age preventivamente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'iniciativa', 'text' => 'Resolução de Problemas no Campo: Diante de situações não previstas nos manuais, o servidor busca soluções criativas e legais para resolver o problema de imediato?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'iniciativa', 'text' => 'Sugestões de Melhoria: O servidor propõe mudanças no itinerário de patrulhamento ou na organização do setor que possam aumentar a eficiência da segurança?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'iniciativa', 'text' => 'Busca por Treinamento: O servidor demonstra interesse em aprimorar suas técnicas de defesa pessoal, legislação ou primeiros socorros de forma proativa?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'iniciativa', 'text' => 'Liderança Situacional: Em ocorrências com múltiplos envolvidos, o servidor assume o protagonismo na organização da cena e na divisão de tarefas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Responsabilidade (5) ---
            ['group_type' => 'guarda', 'category' => 'responsabilidade', 'text' => 'Zelo com o Patrimônio: O servidor cuida das viaturas, armamentos e equipamentos, realizando vistorias e comunicando imediatamente qualquer avaria?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'responsabilidade', 'text' => 'Segurança de Terceiros: O servidor age com responsabilidade na condução de viaturas e no manuseio de armas, priorizando sempre a segurança dos munícipes e colegas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'responsabilidade', 'text' => 'Fidelidade nos Relatórios: As informações inseridas nos Registros de Ocorrência são verídicas, detalhadas e refletem fielmente os fatos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'responsabilidade', 'text' => 'Guarda de Objetos e Provas: O servidor demonstra responsabilidade na preservação de locais de crime e no manuseio de objetos apreendidos (cadeia de custódia)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'responsabilidade', 'text' => 'Compromisso com o Munícipe: O servidor compreende o impacto de sua função para a segurança da comunidade, agindo com dedicação e profissionalismo?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Cooperação (5) ---
            ['group_type' => 'guarda', 'category' => 'cooperacao', 'text' => 'Trabalho com a Equipe de Guarnição: O servidor mantém uma relação de confiança e apoio mútuo com seu parceiro de viatura ou equipe de posto?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'cooperacao', 'text' => 'Integração com outras Forças: O servidor colabora de forma eficiente em operações conjuntas com a Polícia Militar, Polícia Civil ou agentes de trânsito?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'cooperacao', 'text' => 'Transmissão de Informações: O servidor compartilha informações relevantes coletadas no campo com os demais turnos e inteligência da corporação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'cooperacao', 'text' => 'Apoio a Colegas: O servidor demonstra prontidão para auxiliar colegas de outros setores em situações de perigo ou alta demanda operacional?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'cooperacao', 'text' => 'Clima Interno: O servidor contribui para um ambiente de trabalho saudável, evitando condutas que gerem desunião na tropa?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Qualidade (5) ---
            ['group_type' => 'guarda', 'category' => 'qualidade', 'text' => 'Eficácia nas Abordagens: As abordagens realizadas pelo servidor são técnicas, respeitosas e atingem o objetivo sem gerar abusos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'qualidade', 'text' => 'Atendimento Comunitário: O servidor atende o cidadão com educação, presteza e clareza, orientando-o adequadamente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'qualidade', 'text' => 'Domínio de Técnicas e Armas: O servidor demonstra competência técnica no uso de tecnologias não letais e letais, agindo com precisão quando necessário?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'qualidade', 'text' => 'Redação e Documentação: Os documentos e partes de serviço produzidos são claros, sem erros graves e com linguagem técnica adequada?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'qualidade', 'text' => 'Impacto na Segurança Local: O desempenho do servidor resulta em uma percepção real de segurança no seu setor de atuação, com diminuição de incidentes?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Desenvolvimento RH (5) ---
            ['group_type' => 'guarda', 'category' => 'desenvolvimento_rh', 'text' => 'O agente participa com assiduidade dos cursos obrigatórios de requalificação anual e treinamentos de tiro fornecidos pela corporação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'desenvolvimento_rh', 'text' => 'Aplica corretamente na prática do patrulhamento as atualizações em legislação penal ou de trânsito apreendidas nas formações?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'desenvolvimento_rh', 'text' => 'Busca aperfeiçoamento contínuo em áreas úteis (como primeiros socorros, defesa pessoal, direitos humanos e mediação de conflitos)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'desenvolvimento_rh', 'text' => 'Mostra-se atento e participativo nas instruções teóricas, palestras e preleções pré-turno operacionais?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'desenvolvimento_rh', 'text' => 'Multiplica o conhecimento tático e operacional assimilado para o restante de sua guarnição?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Avaliação pelo Usuário (5) ---
            ['group_type' => 'guarda', 'category' => 'avaliacao_usuario', 'text' => 'O cidadão munícipe avalia positivamente a clareza, educação e firmeza repassada pelo agente em momentos de abordagem ou orientação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'avaliacao_usuario', 'text' => 'Há registros de elogios ao servidor vindos da comunidade do bairro ou setor em que ele atua preventivamente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'avaliacao_usuario', 'text' => 'As populações envolvidas em conflitos ou acidentes relatam que a atuação da guarda municipal foi imparcial, pacificadora e protetora?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'avaliacao_usuario', 'text' => 'Em rondas escolares e comunitárias, os pais, docentes e líderes de bairro sentem-se amparados pela atuação do agente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'guarda', 'category' => 'avaliacao_usuario', 'text' => 'O feedback geral ou dados da Ouvidoria da Guarda indicam ausência de queixas por truculência, omissão ou abuso de autoridade por parte do servidor?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // ==========================================
            // D. EDUCAÇÃO (educacao) — 40 perguntas
            // ==========================================

            // --- Assiduidade (5) ---
            ['group_type' => 'educacao', 'category' => 'assiduidade', 'text' => 'O profissional do magistério inicia o seu trabalho, tanto nas regências de classe quanto na hora-atividade (estudos e planejamentos), rigorosamente nos horários definidos pela Unidade Educacional?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'assiduidade', 'text' => 'O educador evita ausências injustificadas que comprometam a sequência didática e a continuidade do processo ensino-aprendizagem dos alunos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'assiduidade', 'text' => 'Comparece com regularidade e pontualidade às convocações, como Semanas Pedagógicas, conselhos de classe e reuniões com as famílias?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'assiduidade', 'text' => 'Em casos de faltas inevitáveis, a comunicação é feita de imediato à equipe diretiva garantindo que os alunos não fiquem desassistidos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'assiduidade', 'text' => 'O profissional mantém as suas documentações diárias (registro de faltas, diários de classe) atualizadas pontualmente no sistema escolar?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Disciplina (5) ---
            ['group_type' => 'educacao', 'category' => 'disciplina', 'text' => 'O profissional segue integralmente as diretrizes do Projeto Político-Pedagógico (PPP) e as instruções normativas da Secretaria Municipal de Educação?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'disciplina', 'text' => 'Mantém conduta profissional, ética e compatível com a docência no relacionamento com a direção escolar, equipe de pedagogos, alunos e pais?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'disciplina', 'text' => 'Cumpre os preceitos e as proteções regidas pelo Estatuto da Criança e do Adolescente (ECA) em sala de aula e nas dependências do colégio/CMEI?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'disciplina', 'text' => 'O docente respeita o uso coletivo e organizado das instalações escolares (laboratórios, bibliotecas, recursos esportivos e tecnológicos)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'disciplina', 'text' => 'Acata sem insubordinação indevida as orientações de readequação metodológica solicitadas pela gestão escolar/pedagógica?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Iniciativa (5) ---
            ['group_type' => 'educacao', 'category' => 'iniciativa', 'text' => 'O professor/pedagogo busca ativamente desenvolver ou sugerir novos recursos didáticos e projetos para enriquecer o currículo escolar?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'iniciativa', 'text' => 'Diante de dificuldades de aprendizagem ou problemas disciplinares dos estudantes, propõe ações proativas de recuperação ou de integração?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'iniciativa', 'text' => 'Apresenta autonomia para solucionar imprevistos cotidianos dentro da sala de aula com firmeza e criatividade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'iniciativa', 'text' => 'Promove a inovação na escola ao utilizar tecnologia, pesquisa e metodologias ativas, visando engajar estudantes desmotivados?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'iniciativa', 'text' => 'Envolve-se ativamente com as decisões comunitárias da escola, ajudando a organizar mostras, grêmios estudantis e eventos com os pais?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Responsabilidade (5) ---
            ['group_type' => 'educacao', 'category' => 'responsabilidade', 'text' => 'O profissional assume total compromisso com os prazos de correção de provas, fechamento de notas, e entrega de registros e pareceres avaliativos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'responsabilidade', 'text' => 'Zela incansavelmente pela integridade física, emocional e moral dos alunos (ou bebês/crianças, no caso de Educação Infantil) durante seu turno?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'responsabilidade', 'text' => 'Exerce sua função com dedicação para garantir que o tempo em sala seja efetivamente usado para o aprendizado e não para dispersões?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'responsabilidade', 'text' => 'Zela pelo bom uso, conservação e economia do material didático, da merenda e do patrimônio mobiliário da Unidade Educacional?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'responsabilidade', 'text' => 'Acompanha os alunos e gerencia a segurança e a movimentação deles em ambientes extraclasse ou eventos externos da escola?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Cooperação (5) ---
            ['group_type' => 'educacao', 'category' => 'cooperacao', 'text' => 'O docente ou pedagogo atua de forma colaborativa com seus pares para integrar conteúdos interdisciplinares ou organizar planejamentos conjuntos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'cooperacao', 'text' => 'Auxilia os colegas de forma voluntária frente a aumentos de demanda ou necessidade de organização de festividades do calendário letivo?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'cooperacao', 'text' => 'Ouve e integra o feedback construtivo da pedagogia em relação aos seus planos de aula ou condução de alunos difíceis?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'cooperacao', 'text' => 'Evita a criação de ruídos, conflitos ou posturas excludentes com a equipe de apoio, merendeiras e equipe da limpeza escolar?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'cooperacao', 'text' => 'Atua na mediação de conflitos escolares, fomentando a cultura de paz, respeito e coleguismo entre os estudantes e a comunidade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Qualidade (5) ---
            ['group_type' => 'educacao', 'category' => 'qualidade', 'text' => 'O planejamento das aulas e o trabalho diário com os estudantes refletem claro domínio do conteúdo, exatidão e qualidade didática?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'qualidade', 'text' => 'O aprendizado e a evolução cognitiva ou social dos alunos apresentam nítido avanço em função do trabalho prestado pelo educador?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'qualidade', 'text' => 'Os métodos de avaliação aplicados pelo professor são compatíveis, justos e alinhados com o diagnóstico de aprendizado promovido pela escola?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'qualidade', 'text' => 'No papel de pedagogo ou professor, redige os diagnósticos, laudos escolares e atas de classe com gramática e linguagem técnica impecáveis?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'qualidade', 'text' => 'O educador adapta suas aulas para alcançar alunos com graus diferenciados de cognição (incluindo acessibilidade e Educação Especial)?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Desenvolvimento RH (5) ---
            ['group_type' => 'educacao', 'category' => 'desenvolvimento_rh', 'text' => 'O profissional atingiu ou superou as metas anuais em cursos de formação continuada ofertados pela Rede de Ensino de Araucária?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'desenvolvimento_rh', 'text' => 'Demonstra que a capacitação pedagógica (ou especialização acadêmica) cursada se refletiu de forma positiva na sua rotina com as turmas?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'desenvolvimento_rh', 'text' => 'Busca de forma contínua o progresso em sua habilitação/titulação para enriquecimento intelectual próprio e do município?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'desenvolvimento_rh', 'text' => 'Atua de maneira engajada durante as Semanas Pedagógicas, oficinas e seminários como multiplicador de saberes para outros docentes?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'desenvolvimento_rh', 'text' => 'Socializa na Unidade Educacional a confecção ou desenvolvimento de artigos, publicações em congressos e projetos de pesquisa aplicada?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],

            // --- Avaliação pelo Usuário (5) ---
            ['group_type' => 'educacao', 'category' => 'avaliacao_usuario', 'text' => 'Os pais e responsáveis demonstram elevado grau de satisfação e confiança na forma como o professor conduz a educação de seus filhos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'avaliacao_usuario', 'text' => 'O educador mantém canais de comunicação éticos, polidos e claros com as famílias nos momentos de reunião, dissipando dúvidas e ansiedades?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'avaliacao_usuario', 'text' => 'As crianças ou jovens consideram o professor um exemplo acolhedor e seguro, sem históricos procedentes de tratamentos abusivos, grosseiros ou antiéticos?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'avaliacao_usuario', 'text' => 'A comunidade ao entorno da escola percebe e elogia a contribuição do profissional em eventos abertos promovidos pela unidade?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['group_type' => 'educacao', 'category' => 'avaliacao_usuario', 'text' => 'Não há incidência de queixas, abaixo-assinados ou representações na Ouvidoria do Município contestando o profissionalismo ou a idoneidade do docente?', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        // Inserir em batches de 40 para performance
        foreach (array_chunk($questions, 40) as $chunk) {
            DB::table('evaluation_questions')->insert($chunk);
        }
    }
}
