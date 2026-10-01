<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ijazah;
use App\Services\DiplomaHashService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IjazahController extends Controller
{
    public function index(Request $request): View
    {
        $ijazahs = Ijazah::query()
            ->when($request->user()->role === 'akademik', fn ($q) => $q->where('created_by', $request->user()->id))
            ->when($request->filled('q'), fn ($q) => $q->where(function ($inner) use ($request) {
                $inner->where('nama', 'like', '%'.$request->q.'%')
                    ->orWhere('nomor_ijazah', 'like', '%'.$request->q.'%')
                    ->orWhere('nim', 'like', '%'.$request->q.'%');
            }))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('ijazahs.index', compact('ijazahs'));
    }

    public function create(): View
    {
        return view('ijazahs.create', ['ijazah' => new Ijazah()]);
    }

    public function store(Request $request, DiplomaHashService $hashService, \App\Services\OcrService $ocrService): RedirectResponse
    {
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);
        
        $file = $request->file('document');
        $fileHash = hash_file('sha256', $file->getRealPath());
        
        if (Ijazah::where('file_hash', $fileHash)->exists()) {
            return back()->withInput()->withErrors(['document' => 'File ijazah ini sudah pernah didaftarkan di dalam sistem.']);
        }
        
        // Proses AI OCR
        $extractedData = $ocrService->extractData($file->getRealPath(), $file->getClientMimeType());
        if (!$extractedData) {
            return back()->withErrors(['document' => 'Sistem AI gagal mengekstrak data dari dokumen. Pastikan gambar jelas atau periksa GEMINI_API_KEY.']);
        }
        
        $payload_data = [
            'nama' => $extractedData['nama'] ?? 'TERDETEKSI_TIDAK_SEMPURNA',
            'nik' => $extractedData['nik'] ?? null,
            'tempat_tanggal_lahir' => $extractedData['tempat_tanggal_lahir'] ?? '-',
            'nama_institusi' => $extractedData['nama_institusi'] ?? 'UNIVERSITAS MUHAMMADIYAH CIREBON',
            'fakultas' => $extractedData['fakultas'] ?? '-',
            'prodi' => $extractedData['prodi'] ?? '-',
            'gelar' => $extractedData['gelar'] ?? '-',
            'tanggal_lulus' => $extractedData['tanggal_lulus'] ?? now()->format('Y-m-d'),
            'tanggal_diberikan' => $extractedData['tanggal_diberikan'] ?? null,
        ];

        $data = [
            'nim' => $extractedData['nim'] ?? 'TERDETEKSI_TIDAK_SEMPURNA',
            'nomor_ijazah' => $extractedData['nomor_ijazah'] ?? uniqid('TMP-'),
            'payload_data' => $payload_data,
        ];

        if (Ijazah::where('nomor_ijazah', $data['nomor_ijazah'])->exists()) {
            $data['nomor_ijazah'] .= '-' . time(); // Hindari crash saat AI membaca ijazah yang mirip
        }

        $activeWorkflow = \App\Models\Workflow::where('is_active', true)->first();
        if (!$activeWorkflow) {
            return back()->withInput()->withErrors(['document' => 'Belum ada Workflow (Alur Persetujuan) yang diaktifkan oleh Admin.']);
        }

        $steps = $activeWorkflow->steps;
        $firstRole = $steps[0] ?? 'akademik';

        $data['file_hash'] = $fileHash;
        $data['file_path'] = $file->store('ijazahs', 'public');
        $data['created_by'] = $request->user()->id;
        $data['version'] = 1;
        $data['status'] = 'Draft';
        $data['current_approver_role'] = $firstRole;
        $data['workflow_id'] = $activeWorkflow->id;
        $data['approval_data'] = [
            'workflow_steps' => $steps,
        ];
        
        $ijazah = new Ijazah($data);
        $ijazah->hash = $hashService->hash($ijazah);
        $ijazah->save();

        $this->log($request, 'CREATE_IJAZAH', "Data ijazah {$ijazah->nomor_ijazah} dibuat via AI OCR.");

        return redirect()->route('ijazahs.show', $ijazah)->with('success', 'Data berhasil diekstrak oleh AI. Silakan verifikasi dan tandatangani atau revisi jika ada kesalahan baca.');
    }

    public function show(Ijazah $ijazah, DiplomaHashService $hashService): View
    {
        return view('ijazahs.show', [
            'ijazah' => $ijazah,
            'regeneratedHash' => $hashService->hash($ijazah),
            'typedData' => $hashService->typedData($ijazah, 'approve'),
        ]);
    }

    public function edit(Ijazah $ijazah): View
    {
        abort_unless(auth()->user()->isRole('akademik') && in_array($ijazah->status, ['Draft', 'Ditolak'], true), 403);

        return view('ijazahs.edit', compact('ijazah'));
    }

    public function update(Request $request, Ijazah $ijazah, DiplomaHashService $hashService): RedirectResponse
    {
        abort_unless($request->user()->isRole('akademik') && in_array($ijazah->status, ['Draft', 'Ditolak'], true), 403);

        $validated = $this->validated($request, $ijazah);
        
        $payload_data = [
            'nama' => $validated['nama'],
            'nik' => $validated['nik'] ?? null,
            'tempat_tanggal_lahir' => $validated['tempat_tanggal_lahir'],
            'nama_institusi' => $validated['nama_institusi'],
            'fakultas' => $validated['fakultas'],
            'prodi' => $validated['prodi'],
            'gelar' => $validated['gelar'],
            'tanggal_lulus' => $validated['tanggal_lulus'],
            'tanggal_diberikan' => $validated['tanggal_diberikan'] ?? null,
        ];
        
        $data = [
            'nim' => $validated['nim'],
            'nomor_ijazah' => $validated['nomor_ijazah'],
            'payload_data' => $payload_data,
        ];

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $data['file_hash'] = hash_file('sha256', $file->getRealPath());
            
            if (Ijazah::where('file_hash', $data['file_hash'])->where('id', '!=', $ijazah->id)->exists()) {
                return back()->withInput()->withErrors(['document' => 'File ijazah ini sudah pernah didaftarkan di dalam sistem.']);
            }
            
            if ($ijazah->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($ijazah->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ijazah->file_path);
            }
            $data['file_path'] = $file->store('ijazahs', 'public');
        } else {
            $data['file_hash'] = $ijazah->file_hash;
            $data['file_path'] = $ijazah->file_path;
        }
        
        $activeWorkflow = \App\Models\Workflow::where('is_active', true)->first();
        if (!$activeWorkflow) {
            return back()->withInput()->withErrors(['document' => 'Belum ada Workflow (Alur Persetujuan) yang diaktifkan.']);
        }
        $steps = $activeWorkflow->steps;
        $firstRole = $steps[0] ?? 'akademik';

        $data['version'] = $ijazah->version + 1;
        $data['status'] = 'Draft';
        $data['current_approver_role'] = $firstRole;
        $data['workflow_id'] = $activeWorkflow->id;
        $data['approval_data'] = [
            'workflow_steps' => $steps,
        ];
        
        $ijazah->fill($data);
        $ijazah->hash = $hashService->hash($ijazah);
        $ijazah->save();

        $this->log($request, 'UPDATE_IJAZAH', "Data ijazah {$ijazah->nomor_ijazah} direvisi.");

        return redirect()->route('ijazahs.show', $ijazah)->with('success', 'Data direvisi dan hash baru telah digenerate.');
    }

    private function validated(Request $request, ?Ijazah $ijazah = null): array
    {
        return $request->validate([
            'document' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB max
            'nama' => ['required', 'string', 'max:150'],
            'nim' => ['required', 'string', 'max:100', Rule::unique('ijazahs')->ignore($ijazah)],
            'nik' => ['nullable', 'string', 'max:100'],
            'tempat_tanggal_lahir' => ['required', 'string', 'max:150'],
            'nama_institusi' => ['required', 'string', 'max:150'],
            'fakultas' => ['required', 'string', 'max:150'],
            'prodi' => ['required', 'string', 'max:150'],
            'gelar' => ['required', 'string', 'max:150'],
            'nomor_ijazah' => ['required', 'string', 'max:100', Rule::unique('ijazahs')->ignore($ijazah)],
            'tanggal_lulus' => ['required', 'date'],
            'tanggal_diberikan' => ['nullable', 'date'],
        ]);
    }

    private function log(Request $request, string $activity, string $description): void
    {
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
