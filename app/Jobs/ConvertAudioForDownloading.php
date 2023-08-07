<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Audio;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertAudioForDownloading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Le modèle Audio pour lequel j'effectue la conversion.
    public $audio;

    /**
     * Crée une nouvelle instance de ConvertAudioForDownloading.
     *
     * @param  Audio  $audio Le modèle Audio à convertir.
     * @return void
     */
    public function __construct(Audio $audio)
    {
        $this->audio = $audio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Définit le format à utiliser pour la conversion avec un débit binaire bas.
        $lowBitrateFormat = (new X264)->setKiloBitrate(500);

        // Utilise FFMpeg pour ouvrir le fichier audio à partir du disque spécifié.
        FFMpeg::fromDisk($this->audio->disk)
            ->open($this->audio->path)
            // Exporte le fichier audio converti vers un autre disque (downloadable_audios)
            // en utilisant le format avec débit binaire bas.
            ->export()
            ->toDisk('downloadable_audios')
            ->inFormat($lowBitrateFormat)
            // Enregistre le fichier audio converti avec l'extension .mp4 (mais c'est toujours un fichier audio).
            ->save($this->audio->id . '.mp4');

        // Met à jour la base de données pour indiquer que la conversion est terminée.
        $this->audio->update([
            'converted_for_downloading_at' => Carbon::now(),
        ]);
    }
}
