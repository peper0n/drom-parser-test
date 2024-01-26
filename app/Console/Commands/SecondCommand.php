<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CarModel;
use App\Models\CarGeneration;
use Goutte\Client;

class SecondCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drom:second';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Second Parse';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('******************');

        $models = CarModel::all();

        foreach ($models as $model) {
            $this->info(" {$model->name} ?");

            $client = new Client();
            $crawler = $client->request('GET', $model->url);

            $crawler->filter('a.e1ei9t6a1')->each(function ($node) use ($model) {
                $this->info($node->attr('href'));
                // сейчас я получаю список всех ссылок на каждой странице
                // В планах создать обьект для каждой модели и выдергивая каждое свойство записывать его на место, потом из обьекта записать все в базу
                dump($node->attr('href'));



                $generationLink = $node->filter('a.e1ei9t6a1');
                $generationNameAndPeriod = trim($generationLink->filter('.css-1089mxj')->text());
                list($generationName, $generationPeriod) = explode(' ', $generationNameAndPeriod, 2);
                $generationImagePath = $generationLink->filter('img')->attr('data-src');
                $detailsPagePath = $generationLink->attr('href');

                $extendedInfo = $node->filter('[data-ftid="component_article_extended-info"]')->children();
                $generationInfo = $extendedInfo->each(function ($infoNode) {
                    return trim($infoNode->text());
                });


//                $generation = new CarGeneration([
//                    'model_id' => $model->id,
//                    'name' => $generationName,
//                    'period' => $generationPeriod,
//                    'image_path' => $generationImagePath,
//                    'details_page_path' => $detailsPagePath,
//                    'info' => implode(', ', $generationInfo), // Пример: "3 поколение, рестайлинг, 443, 44Q, Седан"
//                ]);
//
//                $generation->save();
            });

            $this->info("{$model->name} +");
        }

        $this->info('******************');
    }
}
