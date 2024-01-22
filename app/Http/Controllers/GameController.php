<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\platform;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    // *save in the database
    public function saveGame(Request $request)
    {
        try {
        // Validación de la solicitud
        $request->validate([
            'title' => 'required',
            'platform' => 'required',
            'price' => 'required',
            'discount' => 'required',
            'video' => 'required',
            'summary' => 'required',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif',
            'wallpaper' => 'required|image|mimes:jpeg,png,jpg,gif',
            'screenshots' => 'required|image|mimes:jpeg,png,jpg,gif',
            'releaseDate' => 'required|date',
            'slug' => 'required',
        ]);

        // Procesar y guardar imágenes
        $coverPath =  $request->file('cover')->storeAs('public/image', $request->file('cover')->getClientOriginalName());
        $wallpaperPath = $request->file('wallpaper')->storeAs('public/image', $request->file('wallpaper')->getClientOriginalName());
        $screenshotsPath = $request->file('screenshots')->storeAs('public/image', $request->file('screenshots')->getClientOriginalName());

        // Crear nueva instancia del modelo y guardar en la base de datos
        $nuevoGame = new Game([
            'title' => $request->get('title'),
            'platform' => $request->get('platform'),
            'price' => $request->get('price'),
            'discount' => $request->get('discount'),
            'video' => $request->get('video'),
            'summary' => $request->get('summary'),
            'slug' => $request->get('slug'),
            'cover' => $coverPath,
            'wallpaper' => $wallpaperPath,
            'screenshots' => $screenshotsPath,
            'releaseDate' => $request->get('releaseDate'),
        ]);

        $nuevoGame->save();

        return response()->json(['Game' => $nuevoGame, 'status' => 200, 'mensaje' => 'Game registrado con éxito']);
        }  catch(\Exception $e){
            $errorMessage = $e->getMessage(); // Obtén el mensaje de error
            $errorCode = $e->getCode(); // Obtén el código de error (si es necesario)
            $errorDetails = [
                'message' => $errorMessage,
                'code' => $errorCode,
            ];
        
            // Log del error (puedes usar Laravel Logging)
            Log::error('Error al guardar el juego: ' . json_encode($errorDetails));
        
            // Puedes personalizar la respuesta de error según tus necesidades
            return response()->json(['error' => 'Error al guardar el juego', 'details' => $errorDetails, 'status' => 500], 500);
        }
    }


    // *get 
    public function listGame()
    {
        try {
           
            $games = Game::all();

        $games->each(function ($game) {
            $game->cover_url = asset('storage/' . str_replace('public/', '', $game->cover));
            $game->wallpaper_url = asset('storage/' . str_replace('public/', '', $game->wallpaper));
            $game->screenshots_url = asset('storage/' . str_replace('public/', '', $game->screenshots));

            // Obtener solo el campo title de la plataforma correspondiente
            $platformId = $game->platform;
            $platformTitle = Platform::where('id', $platformId)->value('title');

            // Actualizar el campo platform en el objeto $game con el valor de title
            $game->platform = $platformTitle;
        });
    
            return response()->json(['games' => $games, 'status' => 200, 'message' => 'Games listados con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //* filter get method
    
    public function getGameBySlug($slug)
    {
        try {
            // Buscar el juego por el slug
            $game = Game::where('slug', $slug)->first();

            if (!$game) {
                return response()->json(['status' => 404, 'mensaje' => 'Juego no encontrado'], 404);
            }

            $game->cover_url = asset('storage/' . str_replace('public/', '', $game->cover));
            $game->wallpaper_url = asset('storage/' . str_replace('public/', '', $game->wallpaper));
            $game->screenshots_url = asset('storage/' . str_replace('public/', '', $game->screenshots));

            // Obtener solo el campo title de la plataforma correspondiente
            $platformId = $game->platform;
            $platformTitle = Platform::where('id', $platformId)->value('title');
            $platformIcon  = Platform::where('id',$platformId)->value('icon_path');  

            $game->icon_platform_url = $platformIcon;

             $game->icon_platform_url = asset('storage/' . str_replace('public/', '', $game->icon_platform_url));


            // Actualizar el campo platform en el objeto $game con el valor de title
            $game->platform = $platformTitle;
            
            return response()->json(['games' => $game, 'status' => 200, 'mensaje' => 'Juego encontrado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'mensaje' => 'Error interno del servidor'], 500);
        }
    }


    // * method updata 
    public function updateGame(Request $request, $id)
    {
        try {
            // Validación de la solicitud
            $request->validate([
                'title' => 'required',
                'platform' => 'required',
                'price' => 'required',
                'discount' => 'required',
                'video' => 'required',
                'summary' => 'required',
                'slug' => 'required',
                'cover' => 'image|mimes:jpeg,png,jpg,gif',
                'wallpaper' => 'image|mimes:jpeg,png,jpg,gif',
                'screenshots' => 'image|mimes:jpeg,png,jpg,gif',
                'releaseDate' => 'required|date',
            ]);
    
            // Obtener el juego a actualizar
            $game = Game::findOrFail($id);
    
            // Actualizar campos
            $game->title = $request->get('title');
            $game->slug = $request->get('slug');
            $game->platform = $request->get('platform');
            $game->price = $request->get('price');
            $game->discount = $request->get('discount');
            $game->video = $request->get('video');
            $game->summary = $request->get('summary');
            $game->releaseDate = $request->get('releaseDate');
    
            // Procesar y actualizar imágenes si se proporcionan
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->storeAs('public/image', $request->file('cover')->getClientOriginalName());
                $game->cover = $coverPath;
            }
    
            if ($request->hasFile('wallpaper')) {
                $wallpaperPath = $request->file('wallpaper')->storeAs('public/image', $request->file('wallpaper')->getClientOriginalName());
                $game->wallpaper = $wallpaperPath;
            }
    
            if ($request->hasFile('screenshots')) {
                $screenshotsPath = $request->file('screenshots')->storeAs('public/image', $request->file('screenshots')->getClientOriginalName());
                $game->screenshots = $screenshotsPath;
            }
    
            // Guardar el juego actualizado
            $game->save();
    
            return response()->json(['Game' => $game, 'status' => 200, 'mensaje' => 'Game actualizado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el Juego'], 500);
        }
    }
    
    // * method destroy
    public function destroyGame($id)
    {
        try {
            $game = Game::findOrFail($id);

            // Elimina la imagen del sistema de archivos si existe
            if ($game->cover) {
                Storage::delete($game->cover);
            }
            if ($game->wallpaper) {
                Storage::delete($game->wallpaper);
            }
            if ($game->screenshots) {
                Storage::delete($game->screenshots);
            }

            // Elimina el registro
            $game->delete();

            return response()->json("Eliminado con exito", 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
   
}
