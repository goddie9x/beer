<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Threshold;
use App\Models\Device;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $thresholds = (new Threshold())->getAllThresHoldInfo();
        return view('frontend.alert', compact('thresholds'));
    }
    public function setThreshold(Request $request)
    {
        try {
            $rowEffected = Threshold::where('id', $request->id)->update(['t_ceil' => $request->t_ceil, 't_delta_ceil' => $request->t_delta_ceil, 't_floor' => $request->t_floor, 't_delta_floor' => $request->t_delta_floor]);

            if ($rowEffected > 0) {
                return response()->json(['success' => true, 'message' => 'Threshold updated successfully']);
            } else {
                return response()->json(['error' => false, 'message' => 'Threshold not updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }
}
