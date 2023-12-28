<?php

namespace App\Listeners;

use App\Events\NewReservation;
use App\Mail\ReservaMailable;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\request as ModelsRequest;
use Illuminate\Support\Facades\Mail;

class ValidatePeriodo implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    // public function handle(NewReservation $event): void
    // {

    //     $reservetion= Reservation::where('dateinit',$event->modelrequest->dateinit)->count();
    //     $rooms= Room::count();
    //     if ($reservetion<$rooms) {
    //         $rooms2=Reservation::where('dateinit',$event->modelrequest->dateinit)->get();
    //         $r=Room::whereNotIn('id',$rooms2->rooms_id);
    //         dd($r);
    //     }else {
    //         //return false;
    //     }
    // }

    public function shouldQueue(NewReservation $event): bool
    {
        $reservetion= Reservation::where('dateinit',$event->modelrequest->dateinit)->count();
        $rooms= Room::count();
        if ($reservetion<$rooms) {
            $reservetion= Reservation::select('room_id')->where('dateinit',$event->modelrequest->dateinit)->get();
            $r= Room::whereNotIn('id',$reservetion)->first();
            $reser= new Reservation;
            $reser->room_id = $r->id;
            $reser->dateinit = $event->modelrequest->dateinit;
            $reser->dateout = $event->modelrequest->dateout;
            $reser->save();
            //dd($reser->id);
            $resq= ModelsRequest::find($event->modelrequest->id);
            $resq->reservation_id = $reser->id;
            $resq->status = 'p';
            $resq->save();
            Mail::to('depablos804@gmai.com')->queue(new ReservaMailable);
            return true;
        }else {
            $resq= ModelsRequest::find($event->modelrequest->id);
            //$resq->reservation_id = $reser->id;
            $resq->status = 'f';
            $resq->save();
            return false;
        }
    }

}
