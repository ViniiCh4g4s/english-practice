<?php

namespace Database\Seeders;

use App\Models\Sentence;
use Illuminate\Database\Seeder;

class SentenceSeeder extends Seeder
{
    public function run(): void
    {
        $sentences = [
            // A1 - Básico
            ['text_pt' => 'Eu gosto de pizza.', 'text_en_reference' => 'I like pizza.', 'level' => 'A1', 'topic' => 'food'],
            ['text_pt' => 'Meu nome é João.', 'text_en_reference' => 'My name is João.', 'level' => 'A1', 'topic' => 'introduction'],
            ['text_pt' => 'Ela tem um gato.', 'text_en_reference' => 'She has a cat.', 'level' => 'A1', 'topic' => 'pets'],
            ['text_pt' => 'Eu moro no Brasil.', 'text_en_reference' => 'I live in Brazil.', 'level' => 'A1', 'topic' => 'location'],
            ['text_pt' => 'Ele é meu irmão.', 'text_en_reference' => 'He is my brother.', 'level' => 'A1', 'topic' => 'family'],
            ['text_pt' => 'Nós estudamos inglês.', 'text_en_reference' => 'We study English.', 'level' => 'A1', 'topic' => 'education'],
            ['text_pt' => 'O livro está na mesa.', 'text_en_reference' => 'The book is on the table.', 'level' => 'A1', 'topic' => 'objects'],
            ['text_pt' => 'Eu tenho fome.', 'text_en_reference' => 'I am hungry.', 'level' => 'A1', 'topic' => 'feelings'],
            ['text_pt' => 'Hoje está sol.', 'text_en_reference' => 'It is sunny today.', 'level' => 'A1', 'topic' => 'weather'],
            ['text_pt' => 'Eles são amigos.', 'text_en_reference' => 'They are friends.', 'level' => 'A1', 'topic' => 'relationships'],

            // A2 - Elementar
            ['text_pt' => 'Eu vou à escola todos os dias.', 'text_en_reference' => 'I go to school every day.', 'level' => 'A2', 'topic' => 'routine'],
            ['text_pt' => 'Ela está cozinhando o jantar.', 'text_en_reference' => 'She is cooking dinner.', 'level' => 'A2', 'topic' => 'activities'],
            ['text_pt' => 'Nós visitamos meus avós no fim de semana.', 'text_en_reference' => 'We visit my grandparents on the weekend.', 'level' => 'A2', 'topic' => 'family'],
            ['text_pt' => 'Eu gostaria de um café, por favor.', 'text_en_reference' => 'I would like a coffee, please.', 'level' => 'A2', 'topic' => 'food'],
            ['text_pt' => 'Ele trabalha em uma empresa grande.', 'text_en_reference' => 'He works at a big company.', 'level' => 'A2', 'topic' => 'work'],
            ['text_pt' => 'Eles estão planejando uma viagem.', 'text_en_reference' => 'They are planning a trip.', 'level' => 'A2', 'topic' => 'travel'],
            ['text_pt' => 'Eu acordo às sete horas.', 'text_en_reference' => 'I wake up at seven o\'clock.', 'level' => 'A2', 'topic' => 'routine'],
            ['text_pt' => 'Ela está aprendendo a dirigir.', 'text_en_reference' => 'She is learning to drive.', 'level' => 'A2', 'topic' => 'learning'],
            ['text_pt' => 'Nós gostamos de assistir filmes.', 'text_en_reference' => 'We like watching movies.', 'level' => 'A2', 'topic' => 'entertainment'],
            ['text_pt' => 'O restaurante abre às onze.', 'text_en_reference' => 'The restaurant opens at eleven.', 'level' => 'A2', 'topic' => 'places'],

            // B1 - Intermediário
            ['text_pt' => 'Eu tenho estudado inglês há três anos.', 'text_en_reference' => 'I have been studying English for three years.', 'level' => 'B1', 'topic' => 'education'],
            ['text_pt' => 'Se eu tivesse mais tempo, viajaria mais.', 'text_en_reference' => 'If I had more time, I would travel more.', 'level' => 'B1', 'topic' => 'travel'],
            ['text_pt' => 'Ela disse que viria à festa.', 'text_en_reference' => 'She said she would come to the party.', 'level' => 'B1', 'topic' => 'social'],
            ['text_pt' => 'Eu estava dormindo quando você ligou.', 'text_en_reference' => 'I was sleeping when you called.', 'level' => 'B1', 'topic' => 'daily_life'],
            ['text_pt' => 'Eles têm morado aqui desde 2020.', 'text_en_reference' => 'They have been living here since 2020.', 'level' => 'B1', 'topic' => 'location'],
            ['text_pt' => 'Eu gostaria de saber mais sobre isso.', 'text_en_reference' => 'I would like to know more about it.', 'level' => 'B1', 'topic' => 'learning'],
            ['text_pt' => 'Ela deve estar no trabalho agora.', 'text_en_reference' => 'She must be at work now.', 'level' => 'B1', 'topic' => 'work'],
            ['text_pt' => 'Nós poderíamos tentar uma solução diferente.', 'text_en_reference' => 'We could try a different solution.', 'level' => 'B1', 'topic' => 'problem_solving'],
            ['text_pt' => 'Ele ficou surpreso com a notícia.', 'text_en_reference' => 'He was surprised by the news.', 'level' => 'B1', 'topic' => 'emotions'],
            ['text_pt' => 'Eu prefiro café a chá.', 'text_en_reference' => 'I prefer coffee to tea.', 'level' => 'B1', 'topic' => 'preferences'],

            // B2 - Intermediário Superior
            ['text_pt' => 'Apesar de estar chovendo, decidimos sair.', 'text_en_reference' => 'Despite it raining, we decided to go out.', 'level' => 'B2', 'topic' => 'weather'],
            ['text_pt' => 'Ele deveria ter estudado mais para a prova.', 'text_en_reference' => 'He should have studied more for the test.', 'level' => 'B2', 'topic' => 'education'],
            ['text_pt' => 'Não só ela canta bem, como também toca piano.', 'text_en_reference' => 'Not only does she sing well, but she also plays piano.', 'level' => 'B2', 'topic' => 'talents'],
            ['text_pt' => 'Caso você precise de ajuda, me avise.', 'text_en_reference' => 'In case you need help, let me know.', 'level' => 'B2', 'topic' => 'assistance'],
            ['text_pt' => 'Quanto mais eu pratico, melhor eu fico.', 'text_en_reference' => 'The more I practice, the better I get.', 'level' => 'B2', 'topic' => 'improvement'],
            ['text_pt' => 'Ela teria vindo se soubesse da reunião.', 'text_en_reference' => 'She would have come if she had known about the meeting.', 'level' => 'B2', 'topic' => 'work'],
            ['text_pt' => 'É provável que eles cheguem atrasados.', 'text_en_reference' => 'They are likely to arrive late.', 'level' => 'B2', 'topic' => 'probability'],
            ['text_pt' => 'Eu estava prestes a sair quando o telefone tocou.', 'text_en_reference' => 'I was about to leave when the phone rang.', 'level' => 'B2', 'topic' => 'timing'],
            ['text_pt' => 'Mal começamos o projeto e já encontramos problemas.', 'text_en_reference' => 'No sooner had we started the project than we found problems.', 'level' => 'B2', 'topic' => 'work'],
            ['text_pt' => 'Ela age como se fosse a chefe.', 'text_en_reference' => 'She acts as if she were the boss.', 'level' => 'B2', 'topic' => 'behavior'],

            // C1 - Avançado
            ['text_pt' => 'Raramente tenho visto uma apresentação tão impressionante.', 'text_en_reference' => 'Rarely have I seen such an impressive presentation.', 'level' => 'C1', 'topic' => 'business'],
            ['text_pt' => 'Caso tivesse sabido das consequências, teria agido diferente.', 'text_en_reference' => 'Had I known the consequences, I would have acted differently.', 'level' => 'C1', 'topic' => 'reflection'],
            ['text_pt' => 'Não há como negar que a situação é complexa.', 'text_en_reference' => 'There is no denying that the situation is complex.', 'level' => 'C1', 'topic' => 'analysis'],
            ['text_pt' => 'Dificilmente encontraremos uma solução perfeita.', 'text_en_reference' => 'We will hardly find a perfect solution.', 'level' => 'C1', 'topic' => 'problem_solving'],
            ['text_pt' => 'Ele se acostumou a trabalhar sob pressão.', 'text_en_reference' => 'He has gotten used to working under pressure.', 'level' => 'C1', 'topic' => 'work'],
            ['text_pt' => 'Embora seja difícil, vale a pena tentar.', 'text_en_reference' => 'Although it is difficult, it is worth trying.', 'level' => 'C1', 'topic' => 'motivation'],
            ['text_pt' => 'Não fosse pela sua ajuda, eu teria falhado.', 'text_en_reference' => 'Were it not for your help, I would have failed.', 'level' => 'C1', 'topic' => 'gratitude'],
            ['text_pt' => 'É fundamental que todos entendam as regras.', 'text_en_reference' => 'It is essential that everyone understand the rules.', 'level' => 'C1', 'topic' => 'requirements'],
            ['text_pt' => 'Ela fez questão de esclarecer todos os pontos.', 'text_en_reference' => 'She made a point of clarifying all the points.', 'level' => 'C1', 'topic' => 'communication'],
            ['text_pt' => 'Por mais que eu tente, não consigo entender.', 'text_en_reference' => 'No matter how hard I try, I cannot understand.', 'level' => 'C1', 'topic' => 'difficulty'],
        ];

        foreach ($sentences as $sentence) {
            Sentence::create($sentence);
        }
    }
}
