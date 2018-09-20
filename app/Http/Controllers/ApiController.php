<?php

namespace App\Http\Controllers;

use App\Event;
use App\Location;
use App\TicketType;
use App\Transaction;
use App\TransactionDetail;
use App\User;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use DB;

class ApiController extends Controller
{
    // Function untuk API endpoint
    public function createLocation(Request $request){
        $location = Validator::make($request->all(), [
            'name' => 'required|max:50'
        ]);

        if ($location->fails())
        {
            return response()->json($location->errors());
        } else {
            $loc = new Location;
            $loc->name = $request->name;
            if($loc->save()){
                return response('Success create location', 200)
                ->header('Content-Type', 'text/plain');
            } else {
                return response('Failed create location', 500)
                ->header('Content-Type', 'text/plain');
            }
        }
    }
    
    public function createEvent(Request $request){
        date_default_timezone_set('Asia/Jakarta');
        $today = date('m/d/Y h:i:s a', time());

        // validate input
        $event = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'location_id' => 'required|integer',
            'start_at' => 'required|date|after:'.$today,
            'end_at' => 'required|date|after:start_at'
        ]);

        if ($event->fails()){
            return response()->json($event->errors());
        } else {
            $ev = new Event;
            $ev->name = $request->name;
            $ev->location_id = $request->location_id;
            $ev->start_at = $request->start_at;
            $ev->end_at = $request->end_at;
            if($ev->save()){
                return response('Success create event', 200)
                ->header('Content-Type', 'text/plain');
            } else {
                return response('Failed create event', 500)
                ->header('Content-Type', 'text/plain');
            }
        }
    }

    public function createTicket(Request $request){
        $ticket = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'event_id' => 'required|integer',
            'price' => 'required|integer',
            'quota' => 'required|integer'
        ]);

        if ($ticket->fails())
        {
            return response()->json($ticket->errors());
        } else {
            $tic = new TicketType;
            $tic->name = $request->name;
            $tic->event_id = $request->event_id;
            $tic->price = $request->price;
            $tic->quota = $request->quota;
            if($tic->save()){
                return response('Success create ticket', 200)
                    ->header('Content-Type', 'text/plain');
            } else {
                return response('Failed create ticket', 500)
                ->header('Content-Type', 'text/plain');   
            }
        }
    }

    public function getEvent($id){
        $getevent = Event::find($id);
        
        if($getevent){
            $geteventdetails = $getevent->tickettypes;
            return response()->json($getevent);
        } else {
            return response('Event Not Found', 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function createTransaction(Request $request){

        $input = $request->all();

        $transvalidate = Validator::make($input, [
            'user_id' => 'required|integer'
        ]);
        
        if ($transvalidate->fails()){
            return response()->json($transvalidate->errors());
        } else {

            $doublecheck =  array_diff_key($input['ticket_type_id'] ,array_unique($input['ticket_type_id']));

            if(empty($doublecheck)){

                $total = 0;
                $subtotal = 0;

                for ($i=0;$i<sizeof($input['ticket_type_id']);$i++) {
                    $ticketqty = TicketType::where('id', $input['ticket_type_id'][$i])->pluck('quota');
                    $ticketprice = TicketType::where('id', $request->ticket_type_id[$i])->pluck('price');
                    $ticketname = TicketType::where('id', $request->ticket_type_id[$i])->pluck('name');

                    if($ticketqty[0] >= $request->quantity[$i]){
                        $subtotal = $request->quantity[$i]*$ticketprice[0];
                        $total += $subtotal;
                        $data[$i] = array(
                            'existing_qty' => $ticketqty[0],
                            'ticket_type_id' => (int)$input['ticket_type_id'][$i],
                            'quantity' => (int)$input['quantity'][$i],
                            'subtotal' => (int)$input['ticket_type_id'][$i]*$ticketprice[0]
                        );
    
                    } else {
                        return response('Ticket '.$ticketname[0].' exceeds the available capacity', 500)
                            ->header('Content-Type', 'text/plain');
                    }
                }

                $transaction = new Transaction;
                $transaction->user_id = $request->user_id;
                $transaction->total_price = $total;

                if($transaction->save()){

                    $transid = $transaction->id;

                    for($j=0; $j<count($data); $j++){
                        $transactiondetail = TransactionDetail::create([
                            'transaction_id' => $transid,
                            'ticket_type_id' => $data[$j]['ticket_type_id'],
                            'quantity' => $data[$j]['quantity'],
                            'subtotal' => $data[$j]['subtotal']
                        ]);

                        // return $data;
                        $currentqty = $data[$j]['existing_qty'] - $data[$j]['quantity'];
                        $decrease_ticket = TicketType::where('id', $data[$j]['ticket_type_id'])->update(['quota' => $currentqty]);
                    }

                    $transaction->transactionDetail()->save($transactiondetail);

                    return response('Success create transaction', 200)
                        ->header('Content-Type', 'text/plain');
                } else {
                    return response('Failed to create transaction', 500)
                        ->header('Content-Type', 'text/plain');
                }

            } else {
                return response('Duplicate ticket types are not allowed', 500)
                    ->header('Content-Type', 'text/plain');
            }
        }
    }

    public function getTransaction($id){
        $gettransaction = Transaction::find($id);
        
        if($gettransaction){
            $gettransactiondetails = $gettransaction->transactiondetail;
            // $transaction = array_merge($gettransaction, $gettransactiondetails);
            return response()->json($gettransaction);
        } else {
            return response('Transaction Not Found', 404)
                ->header('Content-Type', 'text/plain');
        }
    }

}