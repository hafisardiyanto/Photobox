<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhotoSession;
use App\Models\Photo;
use App\Models\Frame;
use App\Models\Result;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;


class PhotoboxController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function capture()
    {
        return view('capture');
    }

    public function frames($uuid)
    {
        $session = PhotoSession::where('uuid', $uuid)->with('photos')->firstOrFail();
        $frames = Frame::where('is_active', true)->get();
        return view('frames', compact('uuid', 'session', 'frames'));
    }

    public function result($uuid)
    {
        $session = PhotoSession::where('uuid', $uuid)->with('result')->firstOrFail();
        return view('result', compact('uuid', 'session'));
    }

    public function gallery()
    {
        $results = Result::with('session')->orderBy('created_at', 'desc')->get();
        return view('gallery', compact('results'));
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'image' => 'required', // base64 string
            'uuid' => 'required|uuid',
            'sequence' => 'required|integer'
        ]);

        $session = PhotoSession::firstOrCreate(['uuid' => $request->uuid]);

        // Decode base64 image
        $img = $request->image;
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        $filename = 'photos/' . $request->uuid . '/shot_' . $request->sequence . '.jpg';
        Storage::disk('public')->put($filename, $data);

        $photo = Photo::create([
            'photo_session_id' => $session->id,
            'image_path' => $filename,
            'sequence_number' => $request->sequence
        ]);

        return response()->json(['success' => true, 'photo_id' => $photo->id]);
    }

    public function storeResult(Request $request)
    {
        $request->validate([
            'image' => 'required', // base64 string
            'uuid' => 'required|uuid'
        ]);

        $session = PhotoSession::where('uuid', $request->uuid)->firstOrFail();
        
        // Decode base64 image
        $img = $request->image;
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        $filename = 'results/' . $request->uuid . '/final_strip.jpg';
        Storage::disk('public')->put($filename, $data);

        $frame = Frame::first();
        if (!$frame) {
            $frame = Frame::create([
                'name' => 'Minimalist White',
                'template_path' => 'templates/white.png',
                'orientation' => 'vertical',
                'is_active' => true,
            ]);
        }

        $result = Result::updateOrCreate(
            ['photo_session_id' => $session->id],
            [
                'frame_id' => $frame->id,
                'result_path' => $filename
            ]
        );

        $session->update(['status' => 'completed']);

        return response()->json(['success' => true, 'result_id' => $result->id]);
    }

    public function uploadToGallery(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $uuid = (string) Str::uuid();
        $session = PhotoSession::create([
            'uuid' => $uuid,
            'status' => 'completed'
        ]);

        $frame = Frame::first();
        if (!$frame) {
            $frame = Frame::create([
                'name' => 'Minimalist White',
                'template_path' => 'templates/white.png',
                'orientation' => 'vertical',
                'is_active' => true
            ]);
        }

        $file = $request->file('photo');
        $filename = 'results/' . $uuid . '/' . $file->hashName();
        Storage::disk('public')->put($filename, file_get_contents($file));

        Result::create([
            'photo_session_id' => $session->id,
            'frame_id' => $frame->id,
            'result_path' => $filename
        ]);

        return redirect()->route('gallery')->with('success', 'Gambar berhasil diupload ke galeri!');
    }
}
