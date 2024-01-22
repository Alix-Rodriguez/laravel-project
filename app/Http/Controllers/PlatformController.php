<?php

namespace App\Http\Controllers;

use App\Models\platform;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PlatformController extends Controller
{
    // *save in the database
    public function savePlatform(Request $request)
    {
        try {

            $request->validate([
                'title' => 'required',
                'slug' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de imagen
            ]);

            $platform = platform::create([
                'title' => $request->title,
                'slug' => $request->slug,
                //  'icon_path' => $request->file('icon')->store('public/icons'),
                'icon_path' => $request->file('icon')->storeAs('public/icons', $request->file('icon')->getClientOriginalName()),
                // Almacena la imagen en la carpeta 'icons'
                'icon_filename' => $request->file('icon')->getClientOriginalName(), // Almacena el nombre del archivo (opcional)
            ]);

            return response()->json(['platform' => $platform, 'status' => 200, 'mensaje' => 'plataforma registrada con éxito']);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage(); // Obtén el mensaje de error
            $errorCode = $e->getCode(); // Obtén el código de error (si es necesario)
            $errorDetails = [
                'message' => $errorMessage,
                'code' => $errorCode,
            ];
        
            // Log del error (puedes usar Laravel Logging)
            Log::error('Error al guardar la plataforma: ' . json_encode($errorDetails));
        
            // Puedes personalizar la respuesta de error según tus necesidades
            return response()->json(['error' => 'Error al guardar la plataforma', 'details' => $errorDetails, 'status' => 500], 500);
        }
    }

    // *get 

    public function listPlatform()
{
    try {
        $platforms = Platform::all();

        $platforms->each(function ($platform) {
            $platform->icon_url = asset('storage/' . str_replace('public/', '', $platform->icon_path));

        });

        return response()->json(['platforms' => $platforms, 'status' => 200, 'message' => 'Plataformas listadas con éxito']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    // * method updata
    public function updatePlataform(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required',
                'slug' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de imagen
            ]);

            $platform = Platform::findOrFail($id);

            // Actualiza los campos
            $platform->update([
                'title' => $request->title,
                'slug' => $request->slug,
            ]);

            // Actualiza la imagen si se proporciona
            if ($request->hasFile('icon')) {
                $platform->update([
                    'icon_path' => $request->file('icon')->storeAs('public/icons', $request->file('icon')->getClientOriginalName()),
                    'icon_filename' => $request->file('icon')->getClientOriginalName(),
                ]);
            }

            return response()->json($platform, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // * method destroy
    public function destroyPlataform($id)
    {
        try {
            $platform = Platform::findOrFail($id);

            // Elimina la imagen del sistema de archivos si existe
            if ($platform->icon_path) {
                Storage::delete($platform->icon_path);
            }

            // Elimina el registro
            $platform->delete();

            return response()->json("Eliminado con exito", 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
