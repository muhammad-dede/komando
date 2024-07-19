<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\SpellUtil;
use Illuminate\Http\Request;

class SpellCheckerController extends Controller
{
    public function index(Request $request)
    {
        $words = $request->words;
        $checked = (new SpellUtil)->check($words);

        return response()->json([
            'isValid' => $words === $checked,
            'spell' => $checked,
        ]);
    }
}
