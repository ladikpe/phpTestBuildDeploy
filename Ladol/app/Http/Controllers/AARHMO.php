<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;

class AARHMO extends Controller
{
    //
	public function __construct(){
		$this->middleware(['auth'])->except(['getHMOHospitals', 'getHMOs', 'deleteHMO', 'saveHMO', 'saveHMOHospital', 'deleteHMOHospital','importHMOHospitals', 'downloadHMOHospitalsTemplate']);
	}

	public function HMO(Request $request){
		$allHMO = \App\AARHMO::get();
		return view('hmo.hmo-setup', compact('allHMO'));
	}

	
	public function getHMOs()
    {
        $data = \App\AARHMO::all();
        $total = $data->count();
        return response()->json([
            'message'=> 'HMOs retrieved successfully!',
            'data'=> $data,
            'total'=>$total, 
        ]);
    }
	public function importHMOHospitals(Request $request)
    {   
		$document = $request->file('hospitals');
		$hmos = \App\AARHMO::all();
		$hospitalEntries = [];
        // import
        if ($request->hasFile('hospitals')){
			$datas = \Excel::load($document->getrealPath(), function ($reader) {
                $reader->noHeading()->skipRows(1);
            })->get();
			foreach ($datas[0] as $data) {

                if ($data[0]) {
                    $hmoNames = $data[0] ? explode(', ', $data[0]) : []; //make an array of strings, also make them in same 
					$hmoNames = array_map('strtolower', $hmoNames);
                    $selectedHMOs = $hmos->filter(function ($item) use ($hmoNames) {
						return in_array(strtolower($item['hmo']), $hmoNames);
					});
					$selectedHMOIds = $selectedHMOs->map(function ($item) {
						return $item->id;
					})->toArray();
					$entry = new \App\AARHMOHospital();
      
					$entry->hospital = strval($data[1]);
					$entry->category =  strval($data[2]);
					$entry->band =  strval($data[3]);
					$entry->address =  strval($data[4]);
					$entry->contact =  strval($data[5]);
					$entry->save();
					$entry->hmohospitals()->sync($selectedHMOIds);
					
					array_push($hospitalEntries, $entry);
					
                   


                }

            }
          
           

            return response()->json([
                'message' => 'HMO Hospitals imported successfully!',
                'data' => $hospitalEntries,
				
                'success' => true
            ], 200);

        }
      

        // file is not provided
        return response()->json([
            'message' => 'Please upload a file',
            'data' => null,
            'success' => false
        ], 200);
        
        
        
    }
	public function downloadEmployeeHMOs(Request $request){

		
		// $template=['HMO','Name','Category','Band','Address','Contact'];
		// $perspective=\App\AARHMOSelfService::select('id as Internal ID','id')->get()->toArray();

		// return $this->exportexcel('template',['template'=>$template,'perspective'=>$perspective]);
		$view = "hmo.employee-hmo-export-template";
		$employeeHmos = \App\AARHMOSelfService::where('hmo', '<>', NULL)->get();
		return \Excel::create("export", function ($excel) use ($view, $employeeHmos) {

            $excel->sheet("export", function ($sheet) use ($view, $employeeHmos) {
                $sheet->loadView("$view", compact( 'employeeHmos'))
                    ->setOrientation('landscape');
                $sheet->setColumnFormat(array(

                    'C' => '0', 'D' => '0.00', 'E' => '0', 'F' => '0.00', 'G' => '0.00', 'H' => '0.00', 'I' => '0.00', 'J' => '0.00', 'K' => '0.00', 'L' => '0.00', 'M' => '0.00', 'N' => '0', '0' => '0.00', 'P' => '0.00', 'Q' => '0.00', 'R' => '0.00', 'S' => '0.00', 'T' => '0.00', 'U' => '0.00', 'V' => '0.00', 'W' => '0.00', 'X' => '0.00', 'Y' => '0.00', 'Z' => '0.00', 'AA' => '0.00', 'AB' => '0.00', 'AC' => '0.00', 'AD' => '0.00', 'AE' => '0.00', 'AF' => '0.00', 'AG' => '0.00', 'AH' => '0.00', 'AI' => '0.00', 'AJ' => '0.00', 'AK' => '0.00', 'AL' => '0.00', 'AM' => '0.00', 'AN' => '0.00', 'A0' => '0.00', 'AP' => '0.00', 'AQ' => '0.00', 'AR' => '0.00', 'AS' => '0.00', 'AT' => '0.00', 'AU' => '0.00', 'V' => '0.00', 'AW' => '0.00', 'AX' => '0.00', 'AY' => '0.00', 'AZ' => '0.00', 'BA' => '0.00', 'BB' => '0.00', 'BC' => '0.00', 'BD' => '0.00', 'BE' => '0.00', 'BF' => '0.00', 'BG' => '0.00', 'BH' => '0.00', 'BI' => '0.00', 'BJ' => '0.00', 'BK' => '0.00', 'BL' => '0.00', 'BM' => '0.00', 'BN' => '0.00', 'B0' => '0.00', 'BP' => '0.00', 'BQ' => '0.00', 'BR' => '0.00', 'BS' => '0.00', 'BT' => '0.00', 'BU' => '0.00', 'V' => '0.00', 'BW' => '0.00', 'BX' => '0.00', 'BY' => '0.00', 'BZ' => '0.00'
                ));
            });
          
        })->export('xlsx');

	}
	public function downloadHMOHospitalsTemplate(Request $request){

		
		$template=['HMO','Name','Category','Band','Address','Contact'];
		$perspective=\App\AARHMO::select('id as Internal ID','hmo')->get()->toArray();

		return $this->exportexcel('template',['template'=>$template,'perspective'=>$perspective]);

	}
	private function exportexcel($worksheetname,$data)
	{
		return \Excel::create($worksheetname, function($excel) use ($data)
		{
			foreach($data as $sheetname=>$realdata)
			{
				$excel->sheet($sheetname, function($sheet) use ($realdata,$sheetname)
				{
					  
			            $sheet->fromArray($realdata);
			           $sheet->_parent->getSheet(0)->setColumnFormat(array(
							    'G' => '0%'
							));

			      if($sheetname=='perspective'){
			      
		            	$sheet->_parent->addNamedRange(
		            		new \PHPExcel_NamedRange(
		            			'sd', $sheet->_parent->getSheet( 1 ), "B2:B" . $sheet->_parent->getSheet( 1 )->getHighestRow()
		            		)
		            	);
		            
			     for($j=2; $j<=500; $j++){ 
			           $objValidation = $sheet->_parent->getSheet(0)->getCell("A$j")->getDataValidation();
			           $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_CUSTOM);
			           $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
			           $objValidation->setAllowBlank(false);
			           $objValidation->setShowInputMessage(false);
			           $objValidation->setShowErrorMessage(false);
			           $objValidation->setShowDropDown(false);
			           $objValidation->setErrorTitle('Input error');
			           $objValidation->setError('Value is not in list.');
			           $objValidation->setPromptTitle('Pick from Perspective Sheet');
			           $objValidation->setPrompt('Please fill this column with a value from perspective sheet, you can add multiple values by seperating them with a comma');
      				   $objValidation->setFormula1('sd');



			            }
			            }
  		
				});
			}
		})->download('xlsx');
	}
	public function saveHMOHospital(Request $request)
    {   
        // update
        if ($request->filled('hospital') && $request->filled('id')){
            $entry = \App\AARHMOHospital::find($request->id);
      
            $entry->hospital = $request->hospital;
            $entry->category = $request->category;
            $entry->band = $request->band;
            $entry->address = $request->address;
            $entry->contact = $request->contact;
            
			$entry->hmohospitals()->sync($request->hmo);
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'HMO Hospital updated successfully!',
                'data' => $data,
                'success' => true
            ], 200);

        }
      
        // create
        if ($request->filled('hospital')) {
          
            $data = \App\AARHMOHospital::where(['hospital' => $request->hospital])->first();
            if($data){
                return response()->json([
                    'message' => 'Name is already taken!',
                    'data' => null,
                    'success' => false
                ]);

            }
            $entry = new \App\AARHMOHospital();
			$entry->hospital = $request->hospital;
            $entry->category = $request->category;
            $entry->band = $request->band;
            $entry->address = $request->address;
            $entry->contact = $request->contact;
            
            $entry->save();
            $data = $entry;
			$entry->hmohospitals()->sync($request->hmo);
           

            return response()->json([
                'message' => 'HMO Hospital saved successfully!',
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
    public function deleteHMOHospital($id)
    {   
        
        $entry = \App\AARHMOHospital::find($id);
        if(count($entry->users) > 0){
            return response()->json([
                'message' => 'HMO Hospital is in use, and cannot be deleted!',
                'data' => null,
                'success' => false
            ]);
            
        }
        $data = $entry;
        $entry->delete();
        
           


        
        return response()->json([
            'message' => 'HMO deleted successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      
      

       
        
        
        
    }
	public function saveHMO(Request $request)
    {   
        // update
        if ($request->filled('hmo') && $request->filled('id')){
            $entry = \App\AARHMO::find($request->id);
      
            $entry->hmo = $request->hmo;
            $entry->description = $request->description;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'HMO updated successfully!',
                'data' => $data,
                'success' => true
            ], 200);

        }
      
        // create
        if ($request->filled('hmo')) {
          
            $data = \App\AARHMO::where(['hmo' => $request->hmo])->first();
            if($data){
                return response()->json([
                    'message' => 'Name is already taken!',
                    'data' => null,
                    'success' => false
                ]);

            }
            $entry = new \App\AARHMO();
            $entry->hmo = $request->hmo;
            $entry->description = $request->description;
            $entry->save();
            $data = $entry;
           

            return response()->json([
                'message' => 'HMO saved successfully!',
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
    public function deleteHMO($id)
    {   
        
        $entry = \App\AARHMO::find($id);
        if(count($entry->hmohospitals) > 0){
            return response()->json([
                'message' => 'HMO is in use, and cannot be deleted!',
                'data' => null,
                'success' => false
            ]);
            
        }
        $data = $entry;
        $entry->delete();
        
           


        
        return response()->json([
            'message' => 'HMO deleted successfully!',
            'data' => $data,
            'success' => true
        ], 200);
      
      

       
        
        
        
    }
	public function getHMOHospitals()
    {
        $data = \App\AARHMOHospital::all();
        $total = $data->count();
        return response()->json([
            'message'=> 'HMO Hospitals retrieved successfully!',
            'data'=> $data,
            'total'=>$total, 
        ]);
    }
	public function newHMO(Request $request)
	{
		if ($request->hasFile('template')) {
			$uploadedFile = $request->file('template');
			$nameOfHMO = trim($request->name);
			$fileName = $uploadedFile->getClientOriginalName();
			$fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
			$path = $uploadedFile->getRealPath();
			$data = \Excel::load($request->file('template')->getrealPath(), function ($reader) {

			})->get();
			$nameOfSheetAsBand = $fileNameWithoutExtension;
			$hmoReturnId = \App\AARHMO::create(['hmo' => $nameOfHMO, 'description' => "BAND NAME: $fileNameWithoutExtension"])->id;
			if ($hmoReturnId) {
				foreach ($data as $row) {
					
					
					if ($data[0]) { // Check if at least one row is populated
						$hospital = isset($row['hospital']) ? $row['hospital'] : null;
						$category = isset($row['category']) ? $row['category'] : null;
						$coverage = isset($row['coverage']) ? $row['coverage'] : null;
						$address = isset($row['address']) ? $row['address'] : null;
						$contact = isset($row['contact']) ? $row['contact'] : null;
				
						\App\AARHMOHospital::create([
							'hmo' => $hmoReturnId,
							'hospital' => $hospital,
							'band' => $nameOfSheetAsBand,
							'category' => $category,
							'coverage' => $coverage,
							'address' => $address,
							'contact' => $contact,
						]);
					}
					
				}
			}
		}
		return redirect('/hmo-setup');
	}



	public function HMOHospitalsPreview(Request $request){
		$HMOHospitalsPreview = \App\AARHMOHospital::where('hmo',$request->hmoId)->get(); //->paginate(20);
		$bandsCategory = \App\AARHMOHospital::select('band')->distinct()->where('hmo',$request->hmoId)->get();
		return view('hmo.hmo-setup', compact('HMOHospitalsPreview', 'bandsCategory'));
	}

	public function getHMOHospital(Request $request){
		return \App\AARHMOHospital::where('id',$request->id)->first();
	}

	public function patchHMOHospital(Request $request){
		if( $request->delete == '1'){
			$patchHospital = \App\AARHMOHospital::find($request->delhospitalId);
			$patchHospital->delete();
			return redirect()->back();


		}
		$patchHospital = \App\AARHMOHospital::find($request->hospitalId);
		$patchHospital->hospital = $request->hospital;
		$patchHospital->band = $request->band;
		$patchHospital->category = $request->category;
		$patchHospital->address = $request->address;
		$patchHospital->save();
		return redirect()->back();
	}

	public function HMOSelfService(Request $request){
		$request->userId ? $userId = base64_decode($request->userId) : $userId = Auth::user()->id;
		$findUser = \App\AARHMOSelfService::where('userId', $userId)->get()->count();
		if($findUser == 0){
			\App\AARHMOSelfService::create(['userId' => $userId]);
		}
		$HMOSelfService = \App\AARHMOSelfService::where('userId', $userId)->first();
		$AllHMO = \App\AARHMO::get();
		$genotype = ['(A+)', '(A-)', '(B+)', '(B-)', '(O+)', '(O-)', '(AB+)', '(AB-)' ];
		$bloodgroup = ['AA', 'AS', 'SS', 'AC' ];
		return view('hmo.selfservice', compact('AllHMO', 'userId', 'HMOSelfService', 'genotype', 'bloodgroup'));
	}

	public function PostHMOSelfService(Request $request){
		$request->userId ? $userId = base64_decode($request->userId) : $userId = Auth::user()->id;
		$post = [
			'userId' => $userId,
			'hmo' => $request->hmoId,
			'primary_hospital' => $request->hospital1,
			'secondary_hospital' => $request->hospital2,
			'genotype' => $request->genotype,
			'health_plan_type' => $request->healthplantype,
			'bloodgroup' => $request->bloodgroup,
			'health_plan_type' => $request->healthplantype,
			'precondition' => $request->precondition,
			'status' => 1
		];

		\App\User::where('id', $userId)->update(['religion' => $request->religion, 'phone' => $request->userPhone, 'address' => $request->address, 'medical_code' => $request->medical_code]);
		$checker = \App\AARHMOSelfService::where('userId', $userId)->first();
		if($checker){
			$hmoRecord = \App\AARHMOSelfService::where('userId', $userId)->update($post);
		}else{
			$hmoRecord = \App\AARHMOSelfService::create($post);
		}

		//////////////Save Dependants////////////////////////////
		foreach ($request->dependant as $key => $value) {
			$dependantName = trim($request->dependant[$key]);
			$type = $request->type[$key];
			$post = [
				'userId' => $userId,
				'type' => $type,
				'fullname' => $dependantName,
				'date_of_birth' => $request->dob[$key],
				'gender' => $request->gender[$key],
				'primary_hospital' => $request->hospitalLoop1[$key],
				'secondary_hospital' => $request->hospitalLoop2[$key],
				'health_plan_type' =>  $request->dependanthealthplantype[$key],
				'pre_condition' => $request->preCondition[$key],
				'occupation' => $request->occupation[$key],
				'phone' => $request->phone[$key]
			];
			$checker = \App\AARHMOSelfServiceDependents::where('userId', $userId)->where('type', $type)->first();
			if($checker && $dependantName){
				$checker->update($post);
			}elseif(!$checker && $dependantName){
				$dependant = \App\AARHMOSelfServiceDependents::create($post);
			}
			////////////Dependants Passport Processor/////////////
			if(@$request->dependantPassport[$key]){
				$passport = $request->dependantPassport[$key];
				$PassportfileName = md5($type.$userId).'.'.$passport->getClientOriginalExtension();
				$destinationPath = 'assets/hmo/';
				$passport->move($destinationPath, $PassportfileName);
				$passport = ['passport' => $PassportfileName];
				\App\AARHMOSelfServiceDependents::where('userId', $userId)->where('type', $type)->update($passport);
			}
		}

		if(!empty($request->userId))
			$path = "?userId={$request->userId}&path=6534";

		return redirect("/selfservice-hmo".$path);
	}

	public function HMODirectory(Request $request){
		// $HMODirectory = \App\AARHMOSelfService::where('hmo', '<>', NULL)->get();//paginate(20);
		$HMODirectory = \App\User::whereIn('status',[0,1])->get();//paginate(20);
		return view('hmo.hmo-directory', compact('HMODirectory'));
	}

	public function getHMOHospitalsList(Request $request){
		return \App\AARHMO::find($request->id)->hmohospitals;
	}

	public function getHMOHospitalsBand(Request $request){
		return \App\AARHMOHospital::select('band')->distinct()->where('hmo',$request->id)->where('band','<>','')->get();
	}

	public function deleteUserHMO(Request $request){
		$delete = \App\AARHMOSelfService::where('userId', $request->userId)->delete();
		$delete2 = \App\AARHMOSelfServiceDependents::where('userId', $request->userId)->delete();
		if($delete){
			return 'success';
		}
	}









}
