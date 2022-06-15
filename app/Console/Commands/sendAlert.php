<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailForAlert;
use App\Models\Alert;
use App\Models\TimeAnalog;
use App\Models\Threshold;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;

class sendAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send alert email';
    private function SendEmail($device_name, $message, $status, $value, $outThreshold, $created_at)
    {
        $messages = new stdClass();
        $messages->device_name = $device_name;
        $messages->message = $message;
        $messages->status = $status;
        $messages->value = $value;
        $messages->outThreshold = $outThreshold;
        $messages->created_at = $created_at;
        $emails = EmailForAlert::select('email')
            ->where('active', '=', true)
            ->get();
        if (count($emails) > 0) {
            foreach($emails as $email){
                Mail::to($email)->send(new MailNotify($messages));
            }
        }
    }
    private function CreateAlert($DeviceID, $device_name, $message, $status, $value, $outThreshold, $created_at)
    {
        Alert::insert([
            'DeviceID' => $DeviceID,
            'device_name' => $device_name,
            'message' => $message,
            'status' => $status,
            'value' => $value,
            'outThreshold' => $outThreshold,
            'created_at' => $created_at,
        ]);
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $analogData = TimeAnalog::select('Device.DeviceID', 'Device.Dev_Name', 'Time_Analog.Value', 'Time_Analog.Recordtime', 'Device.Dev_Unit', 'Threshold.t_ceil', 'Threshold.t_floor', 'Threshold.t_delta_ceil', 'Threshold.t_delta_floor', 'Threshold.is_warning', 'Threshold.current_warning_type')
            ->join('Device', 'Time_Analog.DeviceID', '=', 'Device.DeviceID')
            ->join('Threshold', 'Device.DeviceID', '=', 'Threshold.DeviceID')
            ->orderBy('Recordtime', 'DESC')
            ->first();
        $is_warning = $analogData->is_warning;
        $value = $analogData['Value'];
        $t_ceil = $analogData['t_ceil'];
        $t_floor = $analogData['t_floor'];
        $t_delta_ceil = $analogData['t_delta_ceil'];
        $t_delta_floor = $analogData['t_delta_floor'];

        if ($is_warning == true) {
            if (($analogData->current_warning_type == 0 && $value > $t_ceil + $t_delta_ceil) || ($analogData->current_warning_type == true && $value < $t_floor - $t_delta_floor)) {
                Threshold::where('DeviceID', '=', $analogData->DeviceID)->update(['is_warning' => 0]);
                return;
            }
        } else {
            if ($value < $t_ceil) {
                Threshold::where('DeviceID', $analogData->DeviceID)->update(['is_warning' => true, 'current_warning_type' => false]);
                $this->CreateAlert($analogData->DeviceID, $analogData->Dev_Name, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'lower than ', $value, $t_ceil, $analogData->Recordtime);
                $this->SendEmail($analogData->Dev_Name, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'lower than ', $value, $t_ceil, $analogData->Recordtime);
                return;
            }
            if ($value > $t_floor) {
                Threshold::where('DeviceID', $analogData->DeviceID)->update(['is_warning' => true, 'current_warning_type' => true]);
                $this->CreateAlert($analogData->DeviceID, $analogData->Dev_Name, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'upper than ', $value, $t_floor, $analogData->Recordtime);
                $this->SendEmail($analogData->Dev_Name, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'upper than ', $value, $t_floor, $analogData->Recordtime);
                return;
            }
        }
    }
}
