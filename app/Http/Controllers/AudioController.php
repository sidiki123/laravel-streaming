<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use App\Jobs\ConvertAudioForStreaming;
use App\Http\Requests\StoreAudioRequest;
use App\Jobs\ConvertAudioForDownloading;

class AudioController extends Controller
{
    /**
     * Affiche la page d'acceuil.
     *
     * @return \Illuminate\View\View
     */
    public function home()
    {
        // Retourne la vue de la page d'acceuil de l'application.
        return view('welcome');
    }


    /**
     * Affiche la liste des fichiers audio enregistrés.
     *
     * @return \Illuminate\View\View
     */
    public function liste()
    {
        // Récupère la liste des fichiers audio depuis la base de données, en ordre décroissant (du plus récent au plus ancien)
        $audios = Audio::latest()->get();

        // Retourne la vue "liste" en passant les fichiers audio à afficher.
        return view('liste', compact('audios'));
    }

    /**
     * Enregistre un nouveau fichier audio pour le streaming.
     *
     * @param  StoreAudioRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stream(StoreAudioRequest $request)
    {
        // Crée un nouvel enregistrement de fichier audio dans la base de données avec les informations fournies par l'utilisateur.
        $audio = Audio::create([
            'disk'          => 'streamable_audios',
            'original_name' => $request->audio->getClientOriginalName(),
            'path'          => $request->audio->store('audios', 'streamable_audios'),
            'title'         => $request->title,
        ]);

        // Déclenche l'exécution de la tâche en arrière-plan pour convertir le fichier audio en un format téléchargeable.
        ConvertAudioForDownloading::dispatch($audio);

        // Déclenche l'exécution de la tâche en arrière-plan pour convertir le fichier audio en un format compatible avec le streaming.
        ConvertAudioForStreaming::dispatch($audio);

        // Redirige l'utilisateur vers la liste des fichiers audio après l'enregistrement réussi.
        return redirect()->route('liste');
    }
}
