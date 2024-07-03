<?php

namespace App\Http\Controllers;

use App\Mail\BillMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BillMailController extends Controller
{
    public function sendBill(Request $request)
    {
        $data = [
            'subject' => $request->input('subject'),
            'name' => $request->input('name'),
            'pages' => [1=>1, 2=>2, 3=>3],
            'file' => $request->file ?? ''
        ];

        // $pdf = PDF::loadView('bill.billMail', $data);

        // if($request->hasFile('file')){

        //     $file = $request->file('file');
        //     $filename = $file->getClientOriginalName();
        //     $file->storeAs('pdfs', $filename);
        //     $data['file'] = $filename;

        // }

        Mail::to($request->input('email'))->send(new BillMail($data));

        return "MENSAJE ENVIADO";
    }
}
