<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationQuestion;
use App\Models\BarsAnchor;

class BarsAnchorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Iniciando Seeding das Âncoras BARS...');

        // Limpar tabela bars_anchors antes de popular para evitar duplicidade
        DB::table('bars_anchors')->truncate();

        // Categorias e suas descrições comportamentais para cada nota de 1 a 5
        $anchorsData = [
            'assiduidade' => [
                1 => 'Faltas frequentes sem justificativa, atrasos rotineiros ou saídas antecipadas constantes sem aviso prévio.',
                2 => 'Ausências ou atrasos esporádicos não planejados que exigem monitoramento frequente da chefia.',
                3 => 'Cumpre a jornada de trabalho de forma integral e pontual, comunicando ausências em tempo hábil.',
                4 => 'Apresenta excelente regularidade, antecipando planejamentos de escalas e compensando eventuais atrasos voluntariamente.',
                5 => 'Assiduidade exemplar; serve de referência para a equipe e assume turnos adicionais proativamente para apoiar a unidade.'
            ],
            'disciplina' => [
                1 => 'Recusa sistematicamente orientações hierárquicas, descumpre normas internas de segurança e ética.',
                2 => 'Apresenta dificuldade para seguir instruções sem supervisão direta; comete infrações leves às normas internas.',
                3 => 'Cumpre as obrigações e deveres regulamentares, tratando superiores e cidadãos com urbanidade.',
                4 => 'Age com extremo profissionalismo, disseminando as boas práticas regulamentares do setor entre os colegas.',
                5 => 'Exemplo absoluto de conduta ética e respeito às normas; propõe melhorias nos regulamentos e zela pela integridade institucional.'
            ],
            'iniciativa' => [
                1 => 'Apresenta postura totalmente passiva; aguarda comandos até mesmo para tarefas simples do seu dia a dia.',
                2 => 'Executa tarefas rotineiras, mas demonstra insegurança para agir de forma autônoma frente a imprevistos mínimos.',
                3 => 'Busca soluções autônomas dentro de sua área de atuação antes de repassar problemas cotidianos para a chefia.',
                4 => 'Identifica gargalos no fluxo de trabalho e propõe proativamente ideias viáveis de desburocratização ou melhoria.',
                5 => 'Antecipa problemas sistêmicos futuros de forma preventiva, criando e implementando melhorias de alto impacto para o setor.'
            ],
            'responsabilidade' => [
                1 => 'Desperdiça recursos públicos, descumpre prazos cruciais ou manuseia dados sigilosos com negligência grave.',
                2 => 'Entrega demandas com atraso frequente ou necessita de cobrança constante para manter a qualidade e conformidade legal.',
                3 => 'Cumpre os prazos acordados, trata dados sensíveis com segurança e assume a responsabilidade sobre suas tarefas.',
                4 => 'Organiza suas demandas com excelência, otimizando o uso do patrimônio e garantindo total conformidade jurídica.',
                5 => 'Comprometimento extraordinário; assume a responsabilidade técnica e apoia a equipe a mitigar riscos e sanar falhas complexas.'
            ],
            'cooperacao' => [
                1 => 'Promove atritos constantes com a equipe, recusa-se a colaborar ou retém informações cruciais para o trabalho coletivo.',
                2 => 'Colabora apenas sob solicitação direta; por vezes demonstra indisposição para apoiar demandas intersetoriais.',
                3 => 'Mantém bom relacionamento na equipe, ajuda colegas de forma natural e compartilha dados necessários para o fluxo.',
                4 => 'Altamente colaborativo; apoia ativamente a integração de projetos transversais e ajuda na resolução de conflitos internos.',
                5 => 'Age como um facilitador de clima organizacional positivo; inspira união, mentora colegas e impulsiona o sucesso de toda a equipe.'
            ],
            'qualidade' => [
                1 => 'Entrega relatórios e documentos com erros frequentes, desorganizados e que requerem retrabalho constante.',
                2 => 'O trabalho apresenta conformidade básica, mas falha em detalhes importantes de precisão ou organização sistemática.',
                3 => 'Produz com precisão e clareza, mantendo arquivos organizados e prestando atendimento educado aos cidadãos.',
                4 => 'Entrega com alto padrão técnico e gramatical, otimizando fluxos e reduzindo o retrabalho para níveis mínimos.',
                5 => 'Entrega resultados excepcionais com excelência técnica indiscutível, servindo de modelo e referência de qualidade para o órgão.'
            ],
            'desenvolvimento_rh' => [
                1 => 'Recusa-se a participar de cursos ou capacitações propostas e não demonstra interesse em evoluir profissionalmente.',
                2 => 'Participa de forma burocrática dos treinamentos apenas quando obrigado, aplicando pouco do conhecimento na prática.',
                3 => 'Conclui as capacitações recomendadas pela Escola de Gestão e aplica o aprendizado em suas tarefas do dia a dia.',
                4 => 'Busca qualificações além das obrigatórias e aplica novas ferramentas e metodologias para otimizar os fluxos de trabalho.',
                5 => 'Atua como multiplicador do conhecimento, ministrando oficinas ou orientando ativamente os pares sobre novos aprendizados.'
            ],
            'avaliacao_usuario' => [
                1 => 'Recebe reclamações recorrentes na Ouvidoria por desatenção, impaciência ou falta de clareza no atendimento prestado.',
                2 => 'Presta atendimento funcional, mas sem empatia ou presteza, por vezes gerando idas e vindas burocráticas ao cidadão.',
                3 => 'Bem avaliado pelo cidadão, prestando atendimento claro, respeitoso e resolvendo a demanda dentro dos prazos legais.',
                4 => 'Destaca-se pela resolutividade e cordialidade, evitando burocracias desnecessárias e recebendo elogios informais frequentes.',
                5 => 'Referência em atendimento humanizado e eficiente; coleciona elogios formais na Ouvidoria e atua com impessoalidade exemplar.'
            ],
        ];

        // Descrições de fallback se a categoria for diferente das padrões
        $fallbackDescriptions = [
            1 => 'Desempenho muito abaixo do esperado, necessitando de acompanhamento imediato e corretivo.',
            2 => 'Desempenho inconsistente, atendendo apenas parcialmente aos requisitos mínimos da função.',
            3 => 'Desempenho satisfatório, atendendo plenamente aos padrões de qualidade e prazos estabelecidos.',
            4 => 'Desempenho superior, entregando resultados acima do esperado com autonomia e iniciativa.',
            5 => 'Desempenho excepcional, agindo como referência de excelência, inovando e influenciando positivamente a equipe.'
        ];

        // Buscar todas as perguntas
        $questions = EvaluationQuestion::all();

        $count = 0;
        foreach ($questions as $question) {
            $category = $question->category;
            $descriptions = $anchorsData[$category] ?? $fallbackDescriptions;

            for ($score = 1; $score <= 5; $score++) {
                BarsAnchor::create([
                    'question_id' => $question->id,
                    'score' => $score,
                    'behavioral_description' => $descriptions[$score],
                ]);
                $count++;
            }
        }

        $this->command->info("Seeding das Âncoras BARS concluído. Foram criadas {$count} âncoras para " . $questions->count() . " perguntas.");
    }
}
