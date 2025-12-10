<?php

namespace App\Http\Controllers\Module;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function list()
    {
        $taxes = Tax::where('host_id', auth()->id())->latest()->get();
        return view(template().'vendor.taxes.list', compact('taxes'));
    }
    public function store(Request $request){

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_type' => 'required|in:percentage,fixed',
        ]);

        try {
            $tax = new Tax();
            $tax->host_id = auth()->id();
            $tax->title = $request->title;
            $tax->amount = $request->amount;
            $tax->type = $request->tax_type;
            $tax->save();

            return back()->with('success','Tax Stored Successfully');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function update(Request $request){

        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'tax_type' => 'required|in:percentage,fixed',
        ]);

        try {
            $tax = Tax::where('host_id', auth()->id())->where('id', $request->tax_id)->first();
            $tax->title = $request->title;
            $tax->amount = $request->amount;
            $tax->type = $request->tax_type;
            $tax->status = $request->is_enabled;
            $tax->save();

            return back()->with('success','Tax Updated Successfully');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $tax = Tax::where('host_id', auth()->id())->where('id', $request->tax_id)->first();

            if (!$tax) {
                return back()->with('error', 'Tax Not Found');
            }
            $tax->delete();

            return back()->with('success', 'Tax Deleted Successfully');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
