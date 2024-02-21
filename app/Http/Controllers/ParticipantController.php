<?php

namespace App\Http\Controllers;

use App\Imports\ParticipantImport;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participant::query()->with(['event']);

        if ($request->q) {
            $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('employee_code', 'like', "%{$request->q}%");
        }

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        $query->orderBy('created_at', 'desc');

        return inertia('Participant/Index', [
            'query' => $query->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'employee_code' => 'required|string',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'unit' => 'nullable|string',
            'agency' => 'nullable|string',
        ]);

        $participant = Participant::where('employee_code', Str::upper($request->employee_code))
            ->where('event_id', $request->event_id)
            ->exists();

        if ($participant) {
            session()->flash('message', ['type' => 'error', 'message' => 'NP sudah digunakan']);
            return;
        }

        Participant::create([
            'event_id' => $request->event_id,
            'employee_code' => Str::upper($request->employee_code),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'unit' => $request->unit,
            'agency' => $request->agency,
        ]);

        return redirect()->route('participant.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }

    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'employee_code' => 'required|string',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'unit' => 'nullable|string',
            'agency' => 'nullable|string',
        ]);

        $p = Participant::where('employee_code', Str::upper($request->employee_code))
            ->where('event_id', $request->event_id)
            ->where('id', '<>', $participant->id)
            ->exists();

        if ($p) {
            session()->flash('message', ['type' => 'error', 'message' => 'NP sudah digunakan']);
            return;
        }

        $participant->update([
            'event_id' => $request->event_id,
            'employee_code' => Str::upper($request->employee_code),
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'unit' => $request->unit,
            'agency' => $request->agency,
        ]);

        return redirect()->route('participant.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed updated']);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();

        return redirect()->route('participant.index')
            ->with('message', ['type' => 'success', 'message' => 'Item has beed deleted']);
    }

    public function importPage()
    {
        return inertia('Participant/Import');
    }

    public function importProccess(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'file' => 'required|file',
        ]);

        // proccess import
        (new ParticipantImport($request->event_id))->import($request->file);

        return redirect()->route('participant.import')
            ->with('message', ['type' => 'success', 'message' => 'Item imported']);
    }

    public function daftar(Request $request)
    {
        $employee_code = $request->query('np');
        $total = Participant::query()->count();
        return inertia('Participant/Daftar', [
            'total' => $total,
            'employee_code' => $employee_code,
        ]);
    }

    public function daftarProccess(Request $request)
    {
        $request->validate([
            // 'employee_code' => 'string',
            'name' => 'required|string',
            // 'nik' => 'required|numeric',
            // 'phone' => 'required|numeric',
            'nik' => 'required|numeric|unique:participants,nik|digits:16',
            'phone' => 'required|numeric|digits_between:9,14',
            'email' => 'nullable|email',
            'unit' => 'nullable|string',
            'agency' => 'nullable|string',
        ]);
        $event_id = "01hptrhgynemw6w8698kjmy4s0";
        $employee_code = rand(1000, 9999);

        $participant = Participant::where('employee_code', Str::upper($employee_code))
            ->where('event_id', $event_id)
            ->exists();

        if ($participant) {
            session()->flash('message', ['type' => 'error', 'message' => 'Kode Doorptize sudah digunakan']);
            return;
        }
        $phone = $request->phone;
        $name = $request->name;
        $nik = $request->nik;
        if (!preg_match("/[^+0-9]/", trim($phone))) {
            // cek apakah no hp karakter ke 1 dan 2 adalah angka 62
            if (substr(trim($phone), 0, 2) == "62") {
                $hp    = trim($phone);
            }
            // cek apakah no hp karakter ke 1 adalah angka 0
            else if (substr(trim($phone), 0, 1) == "0") {
                $hp    = "62" . substr(trim($phone), 1);
            }
        }

        $response = Http::accept('application/json')->get('https://abkreatorpratama.com/wa');
        $jsonData = $response->json();
        // dd($jsonData);
        if ($jsonData['success'] != true) {
            $response = Http::accept('application/json')->get('https://abkreatorpratama.com/wa');
        } else {
            $response = Http::post('https://abkreatorpratama.com/wa/send-message', [
                'phonenumber' => $hp,
                'message' => 'WhatsAPP notifikasi ABkreator
*3 TAHUN KEPEMIMPINAN BUPATI & WAKIL BUPATI MAROS*

Registrasi anda berhasil dengan :
Nama : ' . $name . '
NIK : ' . $nik . '
Nomor Undian : *' . $employee_code . '*

Terima kasih atas partisipasi Bapak/Ibu di tanggal  *28 Februari 2024*

*Hadiah Utama 2 Paket Umrah dan banyak hadiah menarik Lainnya..*
Info lebih lanjut di www.maroskab.go.id

*Dikirim otomatis oleh sistem, Jangan dibalas!*',
            ]);
            $jsonData = $response->json();
        }
        // dd($jsonData);
        Participant::create([
            'event_id' => $event_id,
            'employee_code' => Str::upper($employee_code),
            'name' => $request->name,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'email' => $request->email,
            'unit' => $request->unit,
            'agency' => $request->agency,
        ]);

        return redirect()->route('participant.sudahdaftar', [
            'np' => $employee_code,
        ])
            ->with('message', ['type' => 'success', 'message' => 'Item has beed saved']);
    }
    public function sudahdaftar(Request $request)
    {
        $employee_code = $request->query('np');
        $total = Participant::query()->count();
        return inertia('Participant/SudahDaftar', [
            'total' => $total,
            'employee_code' => $employee_code,
        ]);
    }
}
