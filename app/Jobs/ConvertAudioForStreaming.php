<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Audio;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertAudioForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Le modèle Audio pour lequel j'effetue la conversion.
    public $audio;

    /**
     * Crée une nouvelle instance de ConvertAudioForStreaming.
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
        // Définit les formats à utiliser pour la conversion avec différents débits binaires.
        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);

        // Utilise FFMpeg pour ouvrir le fichier audio à partir du disque spécifié.
        FFMpeg::fromDisk($this->audio->disk)
            ->open($this->audio->path)

            // Exporte le fichier audio converti pour HLS (HTTP Live Streaming).
            ->exportForHLS()
            ->toDisk('streamable_audios')

            // Ajoute les formats de différents débits binaires à utiliser pour la conversion HLS.
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)

            // Enregistre le fichier audio converti pour le streaming avec l'extension .m3u8 (HLS).
            ->save($this->audio->id . '.m3u8');

        // Met à jour la base de données pour indiquer que la conversion est terminée.
        $this->audio->update([
            'converted_for_streaming_at' => Carbon::now(),
            'stream' => $this->audio->id . '.m3u8', // Enregistre le nom du fichier pour le streaming dans la colonne 'stream'.
        ]);
    }
}
