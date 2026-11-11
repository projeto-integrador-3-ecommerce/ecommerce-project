<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

        if (
            ! $projectUrl
            || ! $serviceRoleKey
            || ! $bucket
            || filter_var($projectUrl, FILTER_VALIDATE_URL) === false
            || parse_url($projectUrl, PHP_URL_SCHEME) !== 'https'
        ) {
            return response()->json([
                'message' => 'Supabase Storage não está configurado. Defina SUPABASE_URL, SUPABASE_SERVICE_ROLE_KEY e SUPABASE_BUCKET no Render.',
            ], 503);
        }

        $file = $request->file('image');
        $path = $data['folder'].'/'.Str::uuid().'.'.$file->extension();
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $path)));

        $headers = ['apikey' => $serviceRoleKey];

        if (! str_starts_with($serviceRoleKey, 'sb_secret_')) {
            $headers['Authorization'] = 'Bearer '.$serviceRoleKey;
        }

        Http::withHeaders($headers)
            ->timeout(30)
            ->withBody($file->getContent(), $file->getMimeType() ?? 'application/octet-stream')
            ->post(rtrim($projectUrl, '/').'/storage/v1/object/'.rawurlencode($bucket).'/'.$encodedPath)
            ->throw();

        return response()->json([
            'path' => 'supabase:'.$path,
        ]);
    }
}
