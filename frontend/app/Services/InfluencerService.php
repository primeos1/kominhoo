<?php

namespace App\Services;

use Illuminate\Support\Str;

class InfluencerService
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/influencers.json');
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->path), true);
        return is_array($data) ? $data : [];
    }

    public function create(array $data): array
    {
        $applications = $this->all();
        $application  = [
            'id'           => Str::uuid()->toString(),
            'name'         => trim($data['name'] ?? ''),
            'email'        => strtolower(trim($data['email'] ?? '')),
            'phone'        => trim($data['phone'] ?? ''),
            'instagram'    => trim($data['instagram'] ?? ''),
            'tiktok'       => trim($data['tiktok'] ?? ''),
            'followers'    => $data['followers'] ?? '',
            'niche'        => $data['niche'] ?? '',
            'location'     => trim($data['location'] ?? ''),
            'skin_type'    => $data['skin_type'] ?? '',
            'message'      => trim($data['message'] ?? ''),
            'status'       => 'pending',
            'notes'        => '',
            'submitted_at' => now()->toISOString(),
        ];
        $applications[] = $application;
        file_put_contents($this->path, json_encode($applications, JSON_PRETTY_PRINT));
        return $application;
    }

    public function updateStatus(string $id, string $status, string $notes = ''): bool
    {
        $applications = $this->all();
        foreach ($applications as &$app) {
            if ($app['id'] === $id) {
                $app['status']     = $status;
                $app['notes']      = $notes !== '' ? $notes : ($app['notes'] ?? '');
                $app['updated_at'] = now()->toISOString();
                file_put_contents($this->path, json_encode($applications, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }

    public function delete(string $id): bool
    {
        $applications = $this->all();
        $filtered     = array_values(array_filter($applications, fn ($a) => $a['id'] !== $id));
        if (count($filtered) === count($applications)) {
            return false;
        }
        file_put_contents($this->path, json_encode($filtered, JSON_PRETTY_PRINT));
        return true;
    }

    public function emailExists(string $email): bool
    {
        $email = strtolower(trim($email));
        foreach ($this->all() as $app) {
            if (($app['email'] ?? '') === $email) {
                return true;
            }
        }
        return false;
    }
}
