<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SupabaseImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'folder' => ['required', 'in:products,categories'],
        ]);

        $projectUrl = config('services.supabase.url');
        $serviceRoleKey = config('services.supabase.service_role_key');
        $bucket = config('services.supabase.bucket');

        if (! $projectUrl || ! $serviceRoleKey || ! $bucket) {
            throw new RuntimeException('Supabase Storage is not configured. Set SUPABASE_URL, SUPABASE_SERVICE_ROLE_KEY, and SUPABASE_BUCKET.');
        }

        $file = $request->file('image');
        $path = $data['folder'].'/'.Str::uuid().'.'.$file->extension();
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));

        Http::withHeaders([
            'apikey' => $serviceRoleKey,
            'Authorization' => 'Bearer '.$serviceRoleKey,
        ])
            ->timeout(30)
            ->withBody($file->getContent(), $file->getMimeType() ?? 'application/octet-stream')
            ->post(rtrim($projectUrl, '/').'/storage/v1/object/'.rawurlencode($bucket).'/'.$encodedPath)
            ->throw();

        return response()->json([
            'path' => 'supabase:'.$path,
        ]);
    }
}
