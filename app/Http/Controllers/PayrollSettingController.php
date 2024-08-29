<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\PayrollSetting;
use App\PensionFundAdmin;
use App\TaxAdmin;
use App\PayScale;

class PayrollSettingController extends Controller
{
    use PayrollSetting;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payrollsettings.index');
    }

    public function fetchTaxAdmins()
    {
        $data = TaxAdmin::all();
        $total = $data->count();
        return response()->json([
            'message'=> 'Tax Admins retrieved successfully!',
            'data'=> $data,
            'total'=>$total, 
        ]);
    }
    public function saveTaxAdmin(Request $request)
    {   
        // update
        if ($request->filled('name') && $request->filled('id')){
            $entry = TaxAdmin::find($request->id);
      
            $entry->name = $request->name;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'Tax Admin updated successfully!',
                'data' => $data,
                'success' => true
            ], 200);

        }
      
        // create
        if ($request->filled('name')) {
          
            $data = TaxAdmin::where(['name' => $request->name])->first();
            if($data){
                return response()->json([
                    'message' => 'Name is aleady taken!',
                    'data' => null,
                    'success' => false
                ]);

            }
            $entry = new TaxAdmin();
            $entry->name = $request->name;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'Tax Admin saved successfully!',
                'data' => $data,
                'success' => true
            ], 200);
        }

        // name is not provided
        return response()->json([
            'message' => 'Name is a required field',
            'data' => null,
            'success' => false
        ], 200);
        
        
        
    }
    public function deleteTaxAdmin($id)
    {   
        
        $entry = TaxAdmin::find($id);
        if(count($entry->users) > 0){
            return response()->json([
                'message' => 'Tax Admin is in use, and cannot be deleted!',
                'data' => null,
                'success' => false
            ]);
            
        }
        $data = $entry;
        $entry->delete();
        
           


        
        return response()->json([
            'message' => 'Tax Admin deleted successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      
      

       
        
        
        
    }
    public function fetchPensionFundAdmins()
    {
        $data = PensionFundAdmin::all();
        $total = $data->count();
        return response()->json([
            'message'=> 'Pension Fund Admins retrieved successfully!',
            'data'=> $data,
            'total'=>$total, 
        ]);
    }
    public function fetchPayscales()
    {
        $data = PayScale::all();
        $total = $data->count();
        return response()->json([
            'message'=> 'Pay Scales retrieved successfully!',
            'data'=> $data,
            'total'=>$total, 
        ]);
    }
    public function savePayscale(Request $request)
    {   
        // update
        if ( $request->filled('id')){
            $entry = Payscale::find($request->id);
            
            $entry->level_code = $request->level_code;
            $entry->min_val = $request->min_val;
            $entry->max_val = $request->max_val;
            
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'Pay scale updated successfully!',
                'data' => $data,
                'success' => true
            ], 200);

        }
      
        // create
        
        $entry = new PayScale();
        $entry->level_code = $request->level_code;
        $entry->min_val = $request->min_val;
        $entry->max_val = $request->max_val;
        $entry->save();
        $data = $entry;
        

        return response()->json([
            'message' => 'Pay scale saved successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      

       
        
        
        
    }
    public function savePensionFundAdmin(Request $request)
    {   
        // update
        if ($request->filled('name') && $request->filled('id')){
            $entry = PensionFundAdmin::find($request->id);
         
            $entry->name = $request->name;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'Pension Fund Admin updated successfully!',
                'data' => $data,
                'success' => true
            ], 200);

        }
      
        // create
        if ($request->filled('name')) {
          
            $data = PensionFundAdmin::where(['name' => $request->name])->first();
            if($data){
                return response()->json([
                    'message' => 'Name is aleady taken!',
                    'data' => null,
                    'success' => false
                ]);

            }
            $entry = new PensionFundAdmin();
            $entry->name = $request->name;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'Pension Fund Admin saved successfully!',
                'data' => $data,
                'success' => true
            ], 200);
        }

        // name is not provided
        return response()->json([
            'message' => 'Name is a required field',
            'data' => null,
            'success' => false
        ], 200);
        
        
        
    }
    public function deletePensionFundAdmin($id)
    {   
        
        $entry = PensionFundAdmin::find($id);
        if(count($entry->users) > 0){
            return response()->json([
                'message' => 'Pension Fund Admin is in use, and cannot be deleted!',
                'data' => null,
                'success' => false
            ]);
            
        }
        $data = $entry;
        $entry->delete();
        
           


        
        return response()->json([
            'message' => 'Pension Fund Admin deleted successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      
      

       
        
        
        
    }
    public function deletePayscale($id)
    {   
        
        $entry = PayScale::find($id);
        if(count($entry->grades) > 0){
            return response()->json([
                'message' => 'Pay Scale is in use, and cannot be deleted!',
                'data' => null,
                'success' => false
            ]);
            
        }
        $data = $entry;
        $entry->delete();
        
           


        
        return response()->json([
            'message' => 'Pay Scale deleted successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      
      

       
        
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
     
        return $this->processPost($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        //
        return $this->processGet($id,$request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
