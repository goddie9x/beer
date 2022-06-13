<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Threshold;
use App\Models\Device;
use App\Models\EmailForAlert;
use App\Models\Alert;
use Illuminate\Http\Request;
use \Illuminate\Database\QueryException;

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
        $alerts = Alert::join('Device', 'Device.DeviceID', '=', 'Alert.DeviceID')
            ->select('Alert.*', 'Device.name as device_name')
            ->orderBy('alerts.created_at', 'desc')
            ->paginate(10);
        return view('frontend.alert.index', compact('alerts'));
    }
    public function getAllThreshold()
    {
        $thresholds = (new Threshold())->getAllThresholdInfo();
        return view('frontend.alert.thresholdConfig', compact('thresholds'));
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
    public function getAddEmailView()
    {
        return view('frontend.alert.addEmail');
    }
    public function GetAllEmailsForAlert(Type $var = null)
    {
        $emails = EmailForAlert::paginate(10);
        return view('frontend.alert.emailManager', compact('emails'));
    }
    public function SetEmailsForAlert(Request $request)
    {
        $emails = $request->emails;
        $names = $request->names;
        foreach ($emails as $key=> $email) {
            EmailForAlert::updateOrCreate([
                'email' => $email,
                'name' => $names[$key],
                'active' => 'true'
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Emails updated successfully']);
    }
    public function ChangeActiveEmail(Request $request)
    {
        try{
            $id = $request->id;
            $active = $request->active;
            EmailForAlert::where('id', $id)->update(['active' => $active]);
            return response()->json(['success' => true, 'message' => 'Email updated successfully']);
        }
        catch(QueryException $e){
            return response()->json(['error' => $e]);
        }
    }
    public function deleteEmail(Request $request)
    {
        try{
            $id = $request->id;
            EmailForAlert::where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Email deleted successfully']);
        }
        catch(QueryException $e){
            return response()->json(['error' => $e]);
        }
    }
}
