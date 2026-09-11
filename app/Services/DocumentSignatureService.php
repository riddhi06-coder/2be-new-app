<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Generates the "Certificate of Acknowledgment" for a signed document — a
 * self-contained, nicely designed PDF (rendered from HTML via dompdf) that
 * serves as the proof-of-signature filed under the employee's profile.
 */
class DocumentSignatureService
{
    public function generateSignedPdf(
        Document $document,
        User $user,
        string $signedName,
        ?string $ip,
        \DateTimeInterface $at
    ): string {
        $dir = public_path('uploads/documents/signed');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $base = pathinfo($document->original_name ?: 'document', PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_\-]/', '', preg_replace('/\s+/', '_', trim($base)));
        $base = $base !== '' ? $base : 'document';

        $filename = $base.'_certificate_'.$user->id.'_'.time().'.pdf';
        $dest     = $dir.'/'.$filename;
        $relative = 'uploads/documents/signed/'.$filename;

        $html = view('pdf.acknowledgment_certificate', [
            'document'   => $document,
            'employee'   => $user,
            'signedName' => $signedName,
            'ip'         => $ip,
            'at'         => $at,
            'logo'       => $this->dataUri(public_path('frontend/assets/images/logo-cert.png')),
            'watermark'  => $this->dataUri(public_path('frontend/assets/images/logo-cert-wm.png')),
            'seal'       => $this->dataUri(public_path('frontend/assets/images/cert-seal.png')),
        ])->render();

        // Prefer Chrome (pixel-accurate CSS); fall back to dompdf where Chrome isn't available.
        if (! $this->renderWithChrome($html, $dest)) {
            Pdf::loadHTML($html)->setPaper('a4', 'landscape')->save($dest);
        }

        return $relative;
    }

    /** Render the HTML to a PDF via headless Chrome/Edge. Returns false if unavailable. */
    private function renderWithChrome(string $html, string $dest): bool
    {
        $binary = $this->chromeBinary();
        if (! $binary || ! function_exists('proc_open')) {
            return false;
        }

        $tmpHtml = tempnam(sys_get_temp_dir(), 'cert_').'.html';
        $profile = sys_get_temp_dir().'/cert_profile_'.uniqid();
        file_put_contents($tmpHtml, $html);

        $args = [
            $binary, '--headless=new', '--disable-gpu', '--no-sandbox',
            '--no-pdf-header-footer', '--disable-pdf-tagging',
            '--user-data-dir='.$profile,
            '--print-to-pdf='.$dest,
            'file:///'.str_replace('\\', '/', $tmpHtml),
        ];

        try {
            $proc = proc_open(
                array_map('strval', $args),
                [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
                $pipes
            );
            if (is_resource($proc)) {
                fclose($pipes[1]); fclose($pipes[2]);
                proc_close($proc);
            }
        } catch (\Throwable $e) {
            @unlink($tmpHtml);
            return false;
        }

        @unlink($tmpHtml);
        $this->rrmdir($profile);

        return is_file($dest) && filesize($dest) > 0;
    }

    /** Locate a Chrome/Edge/Chromium binary, or null. Overridable via CHROME_PATH. */
    private function chromeBinary(): ?string
    {
        $env = env('CHROME_PATH');
        if ($env && is_file($env)) {
            return $env;
        }

        $candidates = [
            'C:\Program Files\Google\Chrome\Application\chrome.exe',
            'C:\Program Files (x86)\Google\Chrome\Application\chrome.exe',
            'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe',
            'C:\Program Files\Microsoft\Edge\Application\msedge.exe',
            '/usr/bin/google-chrome',
            '/usr/bin/chromium-browser',
            '/usr/bin/chromium',
            '/usr/bin/google-chrome-stable',
        ];
        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /** Recursively remove a directory (Chrome's throwaway profile). */
    private function rrmdir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            is_dir($path) ? $this->rrmdir($path) : @unlink($path);
        }
        @rmdir($dir);
    }

    /** Read an image file and return it as a base64 data URI (null if missing). */
    private function dataUri(string $path): ?string
    {
        if (! is_file($path)) {
            return null;
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)) ?: 'png';

        return 'data:image/'.$ext.';base64,'.base64_encode(file_get_contents($path));
    }
}
