<?php

namespace App\CentralLogics;

use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Withdraw;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class OrderTrackLogic
{
    public static function createOrderTrack(Order $order, string $status, string $initiator , ?string $details = null, ?array $files = null): OrderTrack
    {

        $data = new OrderTrack();
        $data->order_id = $order->id;
        $data->title = $status;
        $data->initiator = $initiator;
        $data->details = $details;
        if ($files) {
            $uploaded_files = [];
            foreach ($files as $file) {
                $uploaded_files[] = Helpers::upload('order/files/', config('fileformats.image'), $file);
            }
            $data->files = json_encode($uploaded_files);
        }
        $data->save();
        return $data;
    }





}
