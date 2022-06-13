<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailForAlert;
use App\Models\TimeAnalog;
use Jobs\SendAlertEmail;

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
    private function SendEmail($status, $value, $outThreshold, $created_at)
    {
        $message = [
            'status' => $status,
            'value' => $value,
            'outThreshold' => $outThreshold,
            'created_at' => $created_at,
        ];
        $emails = EmailForAlert::select('email')
            ->where('status', '=', 1)
            ->get();
        $emails = $emails->toArray();
        if (count($emails) > 0) {
            SendAlertEmail::dispatch($message, $emails);
        }
    }
    private function CreateAlert($DeviceID, $message, $status, $value, $outThreshold, $created_at)
    {
        $alert = new Alert();
        $alert->DeviceID = $DeviceID;
        $alert->message = $message;
        $alert->status = $status;
        $alert->value = $value;
        $alert->outThreshold = $outThreshold;
        $alert->created_at = $created_at;
        $alert->save();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $analogData = TimeAnalog::select('Device.DeviceID', 'Device.Dev_Name', 'Time_Analog.Value', 'Time_Analog.Recordtime', 'Device.Dev_Unit', 'Threshold.t_ceil', 'Threshold.t_floor', 'Threshold.t_delta_ceil', 'Threshold.t_delta_floor', 'Threshold.is_warning', 'Threshold.current_warning_type')
            ->join('Device', 'TimeDigital.Dev_ID', '=', 'Device.DeviceID')
            ->join('Threshold', 'Device.DeviceID', '=', 'Threshold.DeviceID')
            ->first();
            $is_warning = $analogData->is_warning;
            $value = $analogData['Value'];
            $t_ceil = $analogData['t_ceil'];
            $t_floor = $analogData['t_floor'];
            $t_delta_ceil = $analogData['t_delta_ceil'];
            $t_delta_floor = $analogData['t_delta_floor'];

        if ($is_warning == 1) {
            if (($analogData->current_warning_type == 0&&$value>$t_ceil+$t_delta_ceil)||($analogData->current_warning_type == 1&&$value<$t_floor-$t_delta_floor)) {
                Threshold::where('DeviceID', '=', $analogData->DeviceID)
                    ->update(['is_warning' => 0]);
                return;
            }
        }
        else{
            if ( $value< $t_ceil) {
                Threshold::where('DeviceID', $analogData->DeviceID)->update(['is_warning' => 1, 'current_warning_type' => 0]);
                CreateAlert($analogData->DeviceID, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'lower than ', $value, $t_ceil, $analogData->Recordtime);
                SendEmail('warning', $value, $t_ceil, $analogData->Recordtime);
                return;
            }
            if ($value > $t_floor) {
                Threshold::where('DeviceID', $analogData->DeviceID)->update(['is_warning' => 1, 'current_warning_type' => 1]);
                CreateAlert($analogData->DeviceID, 'The device ' . $analogData->Dev_Name . ' is out of threshold', 'upper than ', $value, $t_floor, $analogData->Recordtime);
                SendEmail('warning', $value, $t_floor, $analogData->Recordtime);
                return;
            }
        }
    }
}
