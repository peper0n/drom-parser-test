<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarModel;
use Goutte\Client;

class FirstCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drom:first';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First Parse';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('******************');

        $client = new Client();
        $crawler = $client->request('GET', 'https://www.drom.ru/catalog/audi/');

        $crawler->filter('a.e64vuai0')->each(function ($node) {
            $filteredName = $this->filterCss($node->text());
            $modelUrl = $node->attr('href');
            $modelName = $filteredName;

            $carModel = new CarModel([
                'name' => $modelName,
                'url' => $modelUrl,
            ]);
            $carModel->save();
        });

        $this->info('******************');
    }
    // Пишу хелпер в этом файле что бы вам не искать и сэкономить время. Обычно такие фкнкции выношу в хелпер и дергаю его оттуда
    // Хэлпер нужен т.к. у них в тэге A1 вставлены стили по этому парсит некорректно
    public function filterCss($input) {
        $filteredString = strstr($input, '.css', true);

        if ($filteredString === false) {
            return $input;
        }

        return $filteredString;
    }

}
