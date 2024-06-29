<?php
namespace App\Traits;


use App\Models\Image;
use App\Models\Order;
use Illuminate\Support\Facades\Broadcast;
trait Helper
{

    public function saveImage($image, $file): string
    {
        $newImage = time() . $image->getClientOriginalName();
        $image->move("uploads/$file", $newImage);
        return "uploads/$file/" . $newImage;
    }
    public function saveImages($images,$prevJobsId, $file): void
    {
        foreach ($images as $image){
            $data=new Image();
            $data->fill(
                [
                    'previous_job_id' => $prevJobsId,
                    'image' => $this->saveImage($image,$file)
                ]
            );
            $data->save();
        }
    }
    public function calculateTotalPrice($orderId)
    {
        $order = Order::find($orderId);
        $total = 0;
        foreach ($order->products as $product) {
            $total += $product->pivot->amount * $product->price;
        }
        return $total;
    }

}
