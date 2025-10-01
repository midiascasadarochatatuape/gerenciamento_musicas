<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Devocional;
use App\Models\User;

class DevocionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Busca um usuário admin ou cria um se necessário
        $admin = User::where('type_user', 'admin')->first();

        if (!$admin) {
            // Se não houver admin, pega o primeiro usuário disponível
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->warn('Nenhum usuário encontrado. Execute o UserSeeder primeiro.');
            return;
        }

        $devocionais = [
            [
                'title' => 'A Importância da Oração na Vida Cristã',
                'excerpt' => 'A oração é o meio pelo qual nos comunicamos com Deus e fortalecemos nossa fé.',
                'content' => '<h2>A Oração como Comunicação com Deus</h2>
                <p>A oração é muito mais que simplesmente pedir coisas a Deus. É uma forma íntima de comunicação com nosso Criador, um momento de comunhão e fortalecimento espiritual.</p>

                <h3>O Que Jesus Nos Ensinou</h3>
                <p>Jesus nos deixou o exemplo perfeito de uma vida de oração. Ele frequentemente se retirava para lugares solitários para orar, demonstrando a importância deste tempo com o Pai.</p>

                <blockquote>
                <p>"Não andeis ansiosos por coisa alguma; antes, em tudo, pela oração e pela súplica, com ações de graças, sejam as vossas petições conhecidas diante de Deus."</p>
                </blockquote>

                <h3>Como Desenvolver uma Vida de Oração</h3>
                <ul>
                <li>Reserve um tempo específico para oração</li>
                <li>Encontre um local tranquilo para se concentrar</li>
                <li>Comece com adoração e gratidão</li>
                <li>Apresente suas petições com fé</li>
                <li>Termine ouvindo a voz de Deus</li>
                </ul>

                <p>A oração transforma não apenas nossas circunstâncias, mas principalmente nosso coração. Ela nos alinha com a vontade de Deus e nos dá força para enfrentar os desafios diários.</p>',
                'bible_references' => ['Filipenses 4:6-7', 'Mateus 6:9-13', 'Lucas 18:1'],
                'devotional_date' => now()->subDays(5),
                'is_published' => true,
            ],
            [
                'title' => 'Confiando em Deus nos Momentos Difíceis',
                'excerpt' => 'Quando passamos por dificuldades, nossa fé é testada, mas também fortalecida.',
                'content' => '<h2>A Fé que Supera as Adversidades</h2>
                <p>A vida cristã não é isenta de dificuldades. Pelo contrário, Jesus nos alertou que no mundo teríamos aflições, mas também nos deu a certeza de que Ele venceu o mundo.</p>

                <h3>O Propósito das Provações</h3>
                <p>As dificuldades não são sinais de que Deus nos abandonou. Elas servem para:</p>
                <ul>
                <li>Fortalecer nossa fé</li>
                <li>Desenvolver perseverança</li>
                <li>Nos aproximar mais de Deus</li>
                <li>Nos preparar para ajudar outros</li>
                </ul>

                <blockquote>
                <p>"E sabemos que todas as coisas contribuem juntamente para o bem daqueles que amam a Deus, daqueles que são chamados por seu decreto."</p>
                </blockquote>

                <h3>Como Manter a Confiança</h3>
                <p>Durante os momentos difíceis, podemos manter nossa confiança em Deus:</p>
                <ul>
                <li>Lembrando das promessas bíblicas</li>
                <li>Buscando apoio na comunidade cristã</li>
                <li>Mantendo a disciplina espiritual</li>
                <li>Focando no caráter fiel de Deus</li>
                </ul>

                <p>Lembre-se: Deus está trabalhando mesmo quando não conseguimos ver. Sua fidelidade permanece para sempre.</p>',
                'bible_references' => ['Romanos 8:28', 'João 16:33', 'Tiago 1:2-4'],
                'devotional_date' => now()->subDays(3),
                'is_published' => true,
            ],
            [
                'title' => 'O Amor de Deus Demonstrado na Cruz',
                'excerpt' => 'O maior ato de amor da história foi demonstrado quando Jesus morreu por nossos pecados.',
                'content' => '<h2>Amor Sacrificial</h2>
                <p>Não há amor maior que aquele demonstrado por Jesus Cristo na cruz do Calvário. Ali, Ele tomou sobre si os nossos pecados e pagou o preço que jamais poderíamos pagar.</p>

                <h3>A Magnitude do Sacrifício</h3>
                <p>Para compreendermos o amor de Deus, precisamos entender o que custou nossa salvação:</p>
                <ul>
                <li>Jesus deixou a glória celestial</li>
                <li>Se fez homem para nos alcançar</li>
                <li>Viveu uma vida sem pecado</li>
                <li>Morreu a morte que merecíamos</li>
                <li>Ressuscitou vencendo a morte</li>
                </ul>

                <blockquote>
                <p>"Porque Deus amou o mundo de tal maneira que deu o seu Filho unigênito, para que todo aquele que nele crê não pereça, mas tenha a vida eterna."</p>
                </blockquote>

                <h3>Nossa Resposta ao Amor</h3>
                <p>Diante de tamanho amor, nossa resposta deve ser:</p>
                <ul>
                <li>Gratidão sincera</li>
                <li>Entrega completa da vida</li>
                <li>Amor aos irmãos</li>
                <li>Testemunho do que Cristo fez</li>
                </ul>

                <p>O amor de Deus não é apenas um sentimento, mas uma ação concreta que mudou a história e pode transformar sua vida hoje.</p>',
                'bible_references' => ['João 3:16', '1 João 4:9-10', 'Romanos 5:8'],
                'devotional_date' => now()->subDay(),
                'is_published' => true,
            ],
            [
                'title' => 'Vivendo uma Vida de Santidade',
                'excerpt' => 'Somos chamados para ser santos como Deus é santo, vivendo de forma que O glorifique.',
                'content' => '<h2>Chamados para a Santidade</h2>
                <p>A santidade não é opcional na vida cristã. Deus nos chama para sermos santos porque Ele é santo. Isso não significa perfeição, mas uma vida separada para Deus.</p>

                <h3>O Que Significa Ser Santo</h3>
                <p>Santidade envolve:</p>
                <ul>
                <li>Separação do pecado</li>
                <li>Consagração a Deus</li>
                <li>Transformação do caráter</li>
                <li>Obediência à Palavra</li>
                </ul>

                <blockquote>
                <p>"Mas, como é santo aquele que vos chamou, sede vós também santos em toda a vossa maneira de viver; porquanto está escrito: Sede santos, porque eu sou santo."</p>
                </blockquote>

                <h3>Como Crescer em Santidade</h3>
                <p>O crescimento em santidade é um processo que envolve:</p>
                <ul>
                <li>Estudo regular da Bíblia</li>
                <li>Oração constante</li>
                <li>Comunhão com outros cristãos</li>
                <li>Obediência ao Espírito Santo</li>
                <li>Arrependimento genuíno quando pecarmos</li>
                </ul>

                <p>Lembre-se: a santidade não é algo que alcançamos por esforço próprio, mas pela obra do Espírito Santo em nossa vida.</p>',
                'bible_references' => ['1 Pedro 1:15-16', 'Levítico 19:2', 'Efésios 4:22-24'],
                'devotional_date' => now(),
                'is_published' => false, // Este é um rascunho
            ]
        ];

        foreach ($devocionais as $devocionalData) {
            Devocional::create(array_merge($devocionalData, [
                'user_id' => $admin->id,
                'views' => rand(10, 100)
            ]));
        }

        $this->command->info('Devocionais de exemplo criados com sucesso!');
    }
}
