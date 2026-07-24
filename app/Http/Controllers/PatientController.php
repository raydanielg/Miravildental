<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,doctor,reception');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $patients = Patient::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('file_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'medical_history' => 'nullable|string',
            'dental_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'conditions' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['file_number'] = $this->generateFileNumber();
        $validated['registered_by'] = auth()->id();
        $validated['new_patient'] = true;

        $patient = Patient::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Mgonjwa amesajiliwa kwa mafanikio.',
                'patient' => [
                    'id' => $patient->id,
                    'file_number' => $patient->file_number,
                    'name' => $patient->name,
                    'phone' => $patient->phone ?? '-',
                    'gender' => $patient->gender ?? '-',
                    'new_patient' => $patient->new_patient,
                    'show_url' => route('patients.show', $patient),
                    'edit_url' => route('patients.edit', $patient),
                    'edit_form_url' => route('patients.edit-form', $patient),
                    'update_url' => route('patients.update', $patient),
                    'delete_url' => route('patients.destroy', $patient),
                ],
            ]);
        }

        return redirect()->route('patients.index')->with('status', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments' => fn ($q) => $q->latest(), 'clinicalRecords' => fn ($q) => $q->latest(), 'documents']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function editForm(Patient $patient)
    {
        return view('patients.edit-form', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'medical_history' => 'nullable|string',
            'dental_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'new_patient' => 'boolean',
        ]);

        $patient->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Taarifa za mgonjwa zimehifadhiwa.',
                'patient' => [
                    'id' => $patient->id,
                    'file_number' => $patient->file_number,
                    'name' => $patient->name,
                    'phone' => $patient->phone ?? '-',
                    'gender' => $patient->gender ?? '-',
                    'new_patient' => $patient->new_patient,
                ],
            ]);
        }

        return redirect()->route('patients.index')->with('status', 'Patient updated successfully.');
    }

    public function destroy(Request $request, Patient $patient)
    {
        $patient->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Mgonjwa amefutwa.']);
        }

        return redirect()->route('patients.index')->with('status', 'Patient deleted successfully.');
    }

    public function documents(Patient $patient)
    {
        return view('patients.documents', compact('patient'));
    }

    public function storeDocument(Request $request, Patient $patient)
    {
        $request->validate([
            'document_type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'file' => 'required|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $file->store('patient_documents/' . $patient->id, 'public');

        PatientDocument::create([
            'patient_id' => $patient->id,
            'document_type' => $request->input('document_type'),
            'title' => $request->input('title') ?? $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'notes' => $request->input('notes'),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('patients.show', $patient)->with('status', 'Document uploaded successfully.');
    }

    public function destroyDocument(PatientDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        return redirect()->back()->with('status', 'Document deleted successfully.');
    }

    private function generateFileNumber(): string
    {
        $latest = Patient::withTrashed()
            ->selectRaw("MAX(CAST(SUBSTRING(file_number, 4) AS UNSIGNED)) as max_num")
            ->value('max_num');

        $number = $latest ? ((int) $latest) + 1 : 1;

        return 'MV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
