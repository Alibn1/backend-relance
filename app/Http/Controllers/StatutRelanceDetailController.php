<?php

namespace App\Http\Controllers;

use App\Models\StatutRelanceDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatutRelanceDetailController extends Controller
{
    public function index()
    {
        return response()->json(StatutRelanceDetail::with(['statutprincipal', 'etapes'])->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:10|unique:statut_relance_detail,code',
            'libelle' => 'nullable|string|max:30',
            'couleur' => 'nullable|string|max:10',
            'champ_interface' => 'nullable|string|max:20',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detail = StatutRelanceDetail::create([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', true),
        ]);

        return response()->json($detail, 201);
    }

    public function show($code)
    {
        $detail = StatutRelanceDetail::with(['statutprincipal', 'etapes'])->find($code);

        if (!$detail) {
            return response()->json(['message' => 'Statut détail non trouvé'], 404);
        }

        return response()->json($detail);
    }

    public function update(Request $request, $code)
    {
        $detail = StatutRelanceDetail::find($code);

        if (!$detail) {
            return response()->json(['message' => 'Statut détail non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'libelle' => 'nullable|string|max:30',
            'couleur' => 'nullable|string|max:10',
            'champ_interface' => 'nullable|string|max:20',
            'actif' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $detail->update([
            ...$request->except('actif'),
            'actif' => $request->boolean('actif', $detail->actif),
        ]);

        return response()->json($detail);
    }

    public function destroy($code)
    {
        $detail = StatutRelanceDetail::find($code);

        if (!$detail) {
            return response()->json(['message' => 'Statut détail non trouvé'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Statut détail supprimé avec succès']);
    }
}
